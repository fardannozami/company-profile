<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * FASE 1: Migrate published WordPress posts (post_type='post')
 * from database `u683986283_pemudamudiy` into `companyprofile.articles`.
 *
 * Bounded & idempotent:
 * - Only ADDS rows to `articles` + files under
 *   storage/app/public/articles and storage/app/public/wp.
 * - Never touches migrations, models, routes, views, or WP pages.
 * - Default run SKIPS slugs that already exist.
 * - `--fresh` removes ONLY the previous migration result
 *   (tracked in storage/app/wp-migrate-manifest.json) then re-imports.
 *
 * Usage:
 *   php artisan wp:migrate-posts
 *   php artisan wp:migrate-posts --fresh
 */
class MigrateWordpressPosts extends Command
{
    protected $signature = 'wp:migrate-posts
                            {--fresh : Delete the previous migration result (manifest-tracked) and re-import}';

    protected $description = 'FASE 1: migrate published WP posts + uploads into articles (idempotent)';

    private string $sourceDb = 'u683986283_pemudamudiy';

    private string $uploadsSrc;

    private string $wpDestDir;

    private string $articlesDestDir;

    private string $manifestPath;

    public function handle(): int
    {
        $this->uploadsSrc = rtrim(
            env('WP_UPLOADS_PATH', '/run/media/xcode/DATA/coding/php/pmdiy/public_html/wp-content/uploads'),
            '/'
        );
        $this->wpDestDir = storage_path('app/public/wp');
        $this->articlesDestDir = storage_path('app/public/articles');
        $this->manifestPath = storage_path('app/wp-migrate-manifest.json');

        $this->registerWordpressConnection();

        if (! is_dir($this->uploadsSrc)) {
            $this->error("Uploads source not found: {$this->uploadsSrc}");
            $this->error('Set WP_UPLOADS_PATH in .env to override.');

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            $this->removePreviousMigration();
        }

        // 1. Copy SELURUH uploads/ -> storage/app/public/wp (preserve YYYY/MM).
        [$copiedFiles, $copiedBytes] = $this->copyUploads();

        // 2. Migrate posts.
        $result = $this->migratePosts();

        // 3. Report.
        $this->newLine();
        $this->info('=== WP migration report (FASE 1: posts only) ===');
        $this->line("Imported : {$result['imported']}");
        $this->line("Skipped  : {$result['skipped']} (slug already exists)");
        $this->line("Failed   : {$result['failed']}");
        $this->line("Media    : {$copiedFiles} files copied to storage/app/public/wp (" . $this->humanBytes($copiedBytes) . ')');
        $this->line('Missing featured files: ' . count($result['missing_files']));
        $this->line('Slug collisions       : ' . count($result['collisions']));
        $this->line('Elementor posts in scope: ' . $result['elementor_count'] . ' (ignored per spec — classic HTML migrated as-is)');

        if ($result['failed'] > 0) {
            $this->newLine();
            $this->warn('Failures:');
            foreach ($result['errors'] as $e) {
                $this->line("  - {$e}");
            }
        }

        if (! empty($result['missing_files'])) {
            $this->newLine();
            $this->warn('Missing featured-image files (thumbnail set to null):');
            foreach (array_slice($result['missing_files'], 0, 20) as $m) {
                $this->line("  - {$m}");
            }
            if (count($result['missing_files']) > 20) {
                $this->line('  ... and ' . (count($result['missing_files']) - 20) . ' more');
            }
        }

        if (! empty($result['collisions'])) {
            $this->newLine();
            $this->warn('Slug collisions (suffixed -2, -3, ...):');
            foreach (array_slice($result['collisions'], 0, 20) as $c) {
                $this->line("  - {$c}");
            }
        }

        $this->newLine();
        $this->info('Sample URLs (APP_URL=' . config('app.url') . '):');
        foreach (array_slice($result['samples'], 0, 3) as $s) {
            $this->line("  - {$s['slug']} => /articles/{$s['slug']}");
            $this->line('    thumbnail: ' . ($s['thumbnail'] ? "/storage/{$s['thumbnail']}" : '(none)'));
        }

        $this->newLine();
        $this->info('Verify: curl -s -o /dev/null -w "%{http_code}" ' . rtrim(config('app.url'), '/') . '/articles');

        return $result['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Register an on-the-fly `wordpress` DB connection reusing the
     * current MySQL credentials but pointing at the WP source database.
     */
    private function registerWordpressConnection(): void
    {
        $base = config('database.connections.mysql');

        config(['database.connections.wordpress' => array_merge($base, [
            'database' => $this->sourceDb,
        ])]);

        DB::purge('wordpress');
    }

    /**
     * --fresh: delete ONLY what the previous migration created,
     * as tracked in the manifest (article IDs + copied files).
     * Existing non-migrated articles/files are never touched.
     */
    private function removePreviousMigration(): void
    {
        $manifest = $this->readManifest();

        $ids = $manifest['article_ids'] ?? [];
        if (! empty($ids)) {
            // Chunk deletes to stay safe on large sets.
            foreach (array_chunk($ids, 500) as $chunk) {
                DB::table('articles')->whereIn('id', $chunk)->delete();
            }
            $this->line('Fresh: deleted ' . count($ids) . ' previously migrated article(s).');
        } else {
            $this->line('Fresh: no previously migrated articles tracked.');
        }

        foreach ($manifest['thumbnails'] ?? [] as $rel) {
            $full = storage_path('app/public/' . ltrim($rel, '/'));
            // Only delete inside articles/ to stay bounded.
            if (str_starts_with($rel, 'articles/') && is_file($full)) {
                @unlink($full);
            }
        }

        if (is_dir($this->wpDestDir)) {
            File::deleteDirectory($this->wpDestDir);
            $this->line('Fresh: removed storage/app/public/wp (will be re-copied).');
        }

        @unlink($this->manifestPath);
    }

    /**
     * Recursively copy uploads/ -> storage/app/public/wp.
     * Skips files that already exist with the same size (idempotent).
     *
     * @return array{0:int,1:int} [files copied-or-present, total bytes on disk]
     */
    private function copyUploads(): array
    {
        $this->line('Copying uploads/ -> storage/app/public/wp ...');

        $files = 0;
        $freshCopies = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->uploadsSrc, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            /** @var \SplFileInfo $item */
            $relative = substr($item->getPathname(), strlen($this->uploadsSrc) + 1);
            $target = $this->wpDestDir . '/' . $relative;

            if ($item->isDir()) {
                if (! is_dir($target)) {
                    mkdir($target, 0755, true);
                }
                continue;
            }

            $files++;
            if (! is_file($target) || filesize($target) !== $item->getSize()) {
                if (! is_dir(dirname($target))) {
                    mkdir(dirname($target), 0755, true);
                }
                copy($item->getPathname(), $target);
                $freshCopies++;
            }

            if ($files % 400 === 0) {
                $this->line("  ... {$files} files scanned ({$freshCopies} copied)");
            }
        }

        $bytes = $this->dirSize($this->wpDestDir);
        $this->line("  done: {$files} files present, {$freshCopies} newly copied (" . $this->humanBytes($bytes) . ' total).');

        return [$files, $bytes];
    }

    /**
     * Migrate all published posts. Uses the query builder (not the
     * Article model) so the model's creating-hook slug/excerpt logic
     * cannot overwrite the WP post_name mapping.
     */
    private function migratePosts(): array
    {
        $wp = DB::connection('wordpress');

        $posts = $wp->table('wp_posts')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->orderBy('post_date')
            ->get();

        $users = $wp->table('wp_users')->pluck('display_name', 'ID');

        $thumbIds = $wp->table('wp_postmeta')
            ->where('meta_key', '_thumbnail_id')
            ->pluck('meta_value', 'post_id');

        $attachFiles = $wp->table('wp_postmeta')
            ->where('meta_key', '_wp_attached_file')
            ->pluck('meta_value', 'post_id');

        $elementorCount = (int) $wp->table('wp_posts as p')
            ->join('wp_postmeta as m', 'm.post_id', '=', 'p.ID')
            ->where('p.post_type', 'post')
            ->where('p.post_status', 'publish')
            ->where('m.meta_key', '_elementor_data')
            ->count();

        // Existing slugs (DB + this batch) for uniqueness checks.
        $usedSlugs = DB::table('articles')->pluck('slug')->flip()->toArray();
        $batchSlugs = [];

        $imported = 0;
        $skipped = 0;
        $failed = 0;
        $errors = [];
        $missingFiles = [];
        $collisions = [];
        $samples = [];
        $manifestIds = [];
        $manifestThumbs = [];

        // Persist manifest from previous runs so --fresh can clean up
        // even if this run skips everything.
        $existingManifest = $this->readManifest();
        $manifestIds = $existingManifest['article_ids'] ?? [];
        $manifestThumbs = $existingManifest['thumbnails'] ?? [];

        if (! is_dir($this->articlesDestDir)) {
            mkdir($this->articlesDestDir, 0755, true);
        }

        $bar = $this->output->createProgressBar(count($posts));
        $bar->start();

        foreach ($posts as $post) {
            try {
                $baseSlug = trim((string) $post->post_name) !== ''
                    ? Str::slug($post->post_name)
                    : Str::slug($post->post_title);

                if ($baseSlug === '') {
                    $baseSlug = 'post-' . $post->ID;
                }

                // Default: skip slug yang sudah ada (idempotent).
                // - Slug yang sudah dipakai batch ini => tabrakan antar
                //   post WP => beri suffix -2, -3, ...
                // - Slug yang sudah ada di DB (hasil migrasi sebelumnya
                //   atau data lain) => skip, jangan pernah overwrite.
                if (isset($batchSlugs[$baseSlug])) {
                    $i = 2;
                    while (isset($usedSlugs["{$baseSlug}-{$i}"])) {
                        $i++;
                    }
                    $slug = "{$baseSlug}-{$i}";
                    $collisions[] = "{$baseSlug} => {$slug} (wp post ID {$post->ID})";
                } elseif (isset($usedSlugs[$baseSlug])) {
                    $skipped++;
                    $bar->advance();
                    continue;
                } else {
                    $slug = $baseSlug;
                }
                $usedSlugs[$slug] = true;
                $batchSlugs[$slug] = true;

                // Featured image.
                $thumbnail = null;
                $thumbId = $thumbIds[$post->ID] ?? null;
                if ($thumbId && isset($attachFiles[(int) $thumbId])) {
                    $rel = ltrim((string) $attachFiles[(int) $thumbId], '/');
                    $src = $this->uploadsSrc . '/' . $rel;
                    if (is_file($src)) {
                        $destName = $slug . '-' . basename($rel);
                        $destName = preg_replace('/[^A-Za-z0-9._-]/', '-', $destName);
                        $destFull = $this->articlesDestDir . '/' . $destName;
                        if (! is_file($destFull) || filesize($destFull) !== filesize($src)) {
                            copy($src, $destFull);
                        }
                        $thumbnail = 'articles/' . $destName;
                        $manifestThumbs[] = $thumbnail;
                    } else {
                        $missingFiles[] = "post '{$baseSlug}' (ID {$post->ID}): {$rel}";
                    }
                }

                // Excerpt: post_excerpt or first 200 plain-text chars.
                $excerpt = trim((string) ($post->post_excerpt ?? ''));
                if ($excerpt === '') {
                    $plain = html_entity_decode(strip_tags((string) $post->post_content), ENT_QUOTES, 'UTF-8');
                    $plain = trim(preg_replace('/\s+/u', ' ', $plain));
                    $excerpt = mb_substr($plain, 0, 200);
                }

                $id = DB::table('articles')->insertGetId([
                    'title' => $post->post_title,
                    'slug' => $slug,
                    'excerpt' => $excerpt,
                    'content' => $this->rewriteContent((string) $post->post_content),
                    'thumbnail' => $thumbnail,
                    'author' => $users[$post->post_author] ?? null,
                    'published_at' => $post->post_date,
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $manifestIds[] = $id;
                $imported++;

                if (count($samples) < 10) {
                    $samples[] = ['slug' => $slug, 'thumbnail' => $thumbnail];
                }
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = "post ID {$post->ID} ('" . mb_substr((string) $post->post_title, 0, 60) . "'): " . $e->getMessage();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->writeManifest(array_values(array_unique($manifestIds)), array_values(array_unique($manifestThumbs)));

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'failed' => $failed,
            'errors' => $errors,
            'missing_files' => $missingFiles,
            'collisions' => $collisions,
            'elementor_count' => $elementorCount,
            'samples' => array_slice($samples, 0, 3),
        ];
    }

    /**
     * Rewrite every `wp-content/uploads/` occurrence (absolute URLs on
     * ANY domain, or relative paths with/without leading slash) to the
     * absolute-site path `/storage/wp/` so images render on all pages.
     */
    private function rewriteContent(string $html): string
    {
        // 1. Absolute URLs: http(s)://<any-host>/wp-content/uploads/...
        $html = (string) preg_replace(
            '#https?://[^/\"\'\s<>\)\]]+/wp-content/uploads/#i',
            '/storage/wp/',
            $html
        );

        // 2. Root-relative: /wp-content/uploads/...
        $html = str_replace('/wp-content/uploads/', '/storage/wp/', $html);

        // 3. Bare relative: wp-content/uploads/...
        $html = str_replace('wp-content/uploads/', '/storage/wp/', $html);

        return $html;
    }

    private function readManifest(): array
    {
        if (! is_file($this->manifestPath)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($this->manifestPath), true);

        return is_array($decoded) ? $decoded : [];
    }

    private function writeManifest(array $ids, array $thumbs): void
    {
        file_put_contents(
            $this->manifestPath,
            json_encode([
                'article_ids' => $ids,
                'thumbnails' => $thumbs,
                'migrated_at' => now()->toDateTimeString(),
                'phase' => 'fase-1-posts-only',
            ], JSON_PRETTY_PRINT)
        );
    }

    private function dirSize(string $dir): int
    {
        if (! is_dir($dir)) {
            return 0;
        }

        $size = 0;
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($it as $file) {
            /** @var \SplFileInfo $file */
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    private function humanBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }
}
