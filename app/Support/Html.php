<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Cleans rich text before it is stored. The article editors save whatever HTML the browser builds,
 * and other people's browsers (readers, editors, the EIC) later load it, so anything that can run
 * code (scripts, onclick=..., javascript: links, iframes, forms) must never reach the database.
 */
class Html
{
    private static ?HTMLPurifier $purifier = null;

    /** Formatting the editors can produce; everything else is removed (its text is kept). */
    private const ALLOWED = 'p,br,b,strong,i,em,u,s,strike,span[style],font[color],div,'
        . 'a[href],ul,ol,li,blockquote,h1,h2,h3,h4,h5,h6';

    /** Removes unsafe HTML (scripts, event handlers...) from user-written article content, keeping the formatting. */
    public static function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        return self::purifier()->purify($html);
    }

    /** Builds the HTML cleaner once, with the tags and attributes articles may use, and reuses it. */
    private static function purifier(): HTMLPurifier
    {
        if (self::$purifier) {
            return self::$purifier;
        }

        $cache = storage_path('app/purifier');
        if (!is_dir($cache)) {
            @mkdir($cache, 0775, true);
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional'); // allows <u>, <s>, <strike>, <font>
        $config->set('HTML.Allowed', self::ALLOWED);
        // Highlight / colour from the copyreader toolbar are inline styles; nothing else is allowed
        $config->set('CSS.AllowedProperties', ['color', 'background-color', 'font-weight', 'font-style', 'text-decoration', 'text-align']);
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
        $config->set('AutoFormat.RemoveEmpty', false);
        $config->set('Cache.SerializerPath', is_dir($cache) && is_writable($cache) ? $cache : null);
        if (!is_dir($cache) || !is_writable($cache)) {
            $config->set('Cache.DefinitionImpl', null);
        }

        return self::$purifier = new HTMLPurifier($config);
    }
}
