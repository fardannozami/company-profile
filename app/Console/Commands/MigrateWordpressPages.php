<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * FASE 2: Migrate published WordPress pages into the singleton
 * `company_profiles.about` (keputusan user: "Gabung ke about").
 *
 * Bounded & idempotent:
 * - Only UPDATES the `about` column on the singleton row (id=1).
 * - Never touches other columns (vision/mission/company_name/etc),
 *   never inserts duplicate rows, never touches fase-1 files.
 * - Default run does nothing when about already contains migrated
 *   content (marker present) — use `--force` to rewrite.
 *
 * Usage:
 *   php artisan wp:migrate-pages
 *   php artisan wp:migrate-pages --force
 */
class MigrateWordpressPages extends Command
{
    protected $signature = 'wp:migrate-pages
                            {--force : Rewrite about even when migrated content is already present}';

    protected $description = 'FASE 2: migrate published WP pages into company_profiles.about (idempotent)';

    private string $sourceDb = 'u683986283_pemudamudiy';

    /** Page IDs to migrate, ascending (Sejarah, Lambang, Mars, Struktur, Progja, Sekretariat). */
    private array $pageIds = [11, 13, 16, 18, 19, 20];

    private string $marker = '<!-- wp-migrate-pages -->';

    private string $backupPath;

    private string $manifestPath;

    public function handle(): int
    {
        $this->backupPath = storage_path('app/wp-migrate-about-backup.txt');
        $this->manifestPath = storage_path('app/wp-migrate-pages-manifest.json');

        $this->registerWordpressConnection();

        // 1. Singleton check: row id=1 must exist; never insert duplicates.
        $profile = DB::table('company_profiles')->where('id', 1)->first();
        if (! $profile) {
            $this->error('company_profiles row id=1 not found — aborting (refusing to insert).');

            return self::FAILURE;
        }

        // 2. Idempotency: default run only when about is still original.
        $current = (string) ($profile->about ?? '');
        if (! $this->option('force') && $this->alreadyMigrated($current)) {
            $this->line('About already contains migrated pages (marker found). Nothing to do. Use --force to rewrite.');

            return self::SUCCESS;
        }

        // 3. Fetch pages in ID-ascending order.
        $wp = DB::connection('wordpress');

        $pages = $wp->table('wp_posts')
            ->where('post_type', 'page')
            ->where('post_status', 'publish')
            ->whereIn('ID', $this->pageIds)
            ->orderBy('ID')
            ->get(['ID', 'post_title', 'post_content']);

        $foundIds = $pages->pluck('ID')->all();
        $missing = array_values(array_diff($this->pageIds, $foundIds));
        foreach ($missing as $mid) {
            $this->warn("Page ID {$mid} not found/published — skipped.");
        }

        if ($pages->isEmpty()) {
            $this->error('No target pages found — aborting without changes.');

            return self::FAILURE;
        }

        // 4. Site name/tagline as opening heading.
        $options = $wp->table('wp_options')
            ->whereIn('option_name', ['blogname', 'blogdescription'])
            ->pluck('option_value', 'option_name');
        $blogname = trim((string) ($options['blogname'] ?? ''));
        $blogdesc = trim((string) ($options['blogdescription'] ?? ''));

        // 5. Combine into ONE HTML: <h2>{title}</h2>{content} per section.
        $parts = [$this->marker];
        if ($blogname !== '') {
            $parts[] = '<h2>' . e($blogname) . '</h2>';
            if ($blogdesc !== '') {
                $parts[] = '<p>' . e($blogdesc) . '</p>';
            }
        }
        foreach ($pages as $page) {
            $parts[] = '<h2>' . e($page->post_title) . '</h2>' . $this->rewriteContent((string) $page->post_content);
        }
        $about = implode("\n", $parts);

        // 6. Backup old about BEFORE update (first run only — never
        // overwrite the original backup on --force rewrites).
        if (! is_file($this->backupPath)) {
            file_put_contents($this->backupPath, $current);
            $this->line('Backup of previous about written to storage/app/wp-migrate-about-backup.txt (' . strlen($current) . ' chars).');
        } else {
            $this->line('Existing backup kept at storage/app/wp-migrate-about-backup.txt.');
        }

        // 7. Update ONLY the about column on id=1.
        DB::table('company_profiles')->where('id', 1)->update([
            'about' => $about,
            'updated_at' => now(),
        ]);

        file_put_contents(
            $this->manifestPath,
            json_encode([
                'page_ids' => $foundIds,
                'skipped_ids' => $missing,
                'about_chars' => strlen($about),
                'migrated_at' => now()->toDateTimeString(),
                'phase' => 'fase-2-pages-to-about',
            ], JSON_PRETTY_PRINT)
        );

        $this->newLine();
        $this->info('=== WP migration report (FASE 2: pages -> about) ===');
        $this->line('Migrated pages: ' . $pages->count() . ' (IDs: ' . implode(', ', $foundIds) . ')');
        $this->line('Skipped (not found): ' . (empty($missing) ? '-' : implode(', ', $missing)));
        $this->line('About length : ' . strlen($about) . ' chars');
        $this->line('Verify: curl -s ' . rtrim(config('app.url'), '/') . '/about | grep -c Sejarah');

        return self::SUCCESS;
    }

    private function alreadyMigrated(string $about): bool
    {
        return str_contains($about, $this->marker)
            || is_file($this->manifestPath);
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
     * Rewrite every `wp-content/uploads/` occurrence (absolute URLs on
     * ANY domain, or relative paths with/without leading slash) to the
     * absolute-site path `/storage/wp/` — same convention as FASE 1.
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
}
