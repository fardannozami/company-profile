<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Split the legacy `company_profiles.about` blob into structured rows.
 *
 * Idempotent: matched by unique slug via updateOrCreate, safe to re-run.
 * - Intro before the first <h2> (migration marker) is skipped when empty;
 *   when non-empty it is prepended to the first section so no content is lost.
 * - Sections with an empty body (same rule as the old Blade split) are skipped.
 */
class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        $aboutHtml = trim(CompanyProfile::first()?->about ?? '');

        if ($aboutHtml === '' || ! preg_match('/<h2[\s>]/i', $aboutHtml)) {
            return;
        }

        $chunks = preg_split(
            '/(<h2[\s>].*?<\/h2\s*>)/is',
            $aboutHtml,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        $intro = '';
        $sections = [];
        $current = null;

        foreach ($chunks as $chunk) {
            if (preg_match('/<h2[^>]*>(.*?)<\/h2\s*>/is', $chunk, $m)) {
                if (is_array($current) && self::hasContent($current['body'])) {
                    $sections[] = $current;
                }
                $heading = trim(strip_tags(html_entity_decode($m[1], ENT_QUOTES, 'UTF-8')));
                $current = ['title' => $heading !== '' ? $heading : 'Bagian', 'body' => ''];
            } elseif (is_array($current)) {
                $current['body'] .= $chunk;
            } else {
                $intro .= $chunk;
            }
        }

        if (is_array($current) && self::hasContent($current['body'])) {
            $sections[] = $current;
        }

        // Non-empty intro is preserved by folding it into the first section.
        if (trim(strip_tags($intro)) !== '' && count($sections) > 0) {
            $sections[0]['body'] = $intro.$sections[0]['body'];
        }

        $order = 1;
        foreach ($sections as $section) {
            $slug = self::uniqueSlug(Str::slug($section['title']) ?: 'bagian', $section['title']);

            AboutSection::updateOrCreate(
                ['slug' => $slug],
                [
                    'title'     => $section['title'],
                    'body'      => $section['body'],
                    'order'     => $order++,
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Same content rule as the legacy Blade split: text or rich media.
     */
    private static function hasContent(string $body): bool
    {
        return trim(strip_tags($body)) !== ''
            || (bool) preg_match('/<(img|table|audio|iframe|figure)\b/i', $body);
    }

    /**
     * Keep slugs unique when two headings slugify identically.
     * A row already carrying this title keeps its slug (idempotent re-runs).
     */
    private static function uniqueSlug(string $base, string $title): string
    {
        $slug = $base;
        $i = 2;

        while (AboutSection::where('slug', $slug)->where('title', '!=', $title)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
