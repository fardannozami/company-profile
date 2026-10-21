<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Sanitizes rich-text (Trix) content before it is rendered unescaped.
 *
 * Content is authored in the admin panel, but it is still treated as
 * untrusted: a compromised or malicious admin account must not be able to
 * inject scripts, event handlers, or javascript: URLs into public pages.
 */
class HtmlSanitizer
{
    private static ?HTMLPurifier $purifier = null;

    /**
     * Return HTML that is safe to output with {!! !!}.
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return self::purifier()->purify($html);
    }

    private static function purifier(): HTMLPurifier
    {
        if (self::$purifier instanceof HTMLPurifier) {
            return self::$purifier;
        }

        $config = HTMLPurifier_Config::createDefault();

        // Writability matters on shared hosting: fall back to the system temp
        // directory instead of throwing when storage/ is not writable.
        $cachePath = storage_path('framework/cache/purifier');
        if (! is_dir($cachePath)) {
            @mkdir($cachePath, 0775, true);
        }
        $config->set(
            'Cache.SerializerPath',
            is_dir($cachePath) && is_writable($cachePath) ? $cachePath : sys_get_temp_dir()
        );

        // Allowlist matching what the Trix editor produces, plus common
        // editorial markup (headings, tables, images).
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.Allowed', implode(',', [
            'p', 'br', 'hr', 'div', 'span',
            'strong', 'b', 'em', 'i', 'u', 's', 'del', 'ins', 'sub', 'sup',
            'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'a[href|title|target|rel]',
            'img[src|alt|width|height]',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'th[colspan|rowspan]', 'td[colspan|rowspan]',
        ]));

        // No javascript:, data:, or file: URLs.
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
        ]);

        // Keep author-specified target="_blank" but let HTMLPurifier add
        // rel="noopener noreferrer" automatically (HTML.TargetNoopener).
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('Cache.DefinitionImpl', 'Serializer');

        return self::$purifier = new HTMLPurifier($config);
    }
}
