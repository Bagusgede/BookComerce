<?php

namespace App\Services;

class HtmlProcessor
{
    /**
     * Ensure external links open in new tab and have rel="noopener noreferrer"
     * This expects sanitized HTML (run Purifier first).
     */
    public static function externalLinksNewTab(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();

        // Wrap in HTML structure to preserve fragments
        $loaded = $doc->loadHTML(mb_convert_encoding('<div>' . $html . '</div>', 'HTML-ENTITIES', 'UTF-8'));
        if ($loaded === false) {
            libxml_clear_errors();
            return $html;
        }

        $anchors = $doc->getElementsByTagName('a');
        $toModify = [];

        // Collect nodes first (avoid live NodeList issues)
        foreach ($anchors as $a) {
            $toModify[] = $a;
        }

        foreach ($toModify as $a) {
            $href = $a->getAttribute('href');
            if (!$href) {
                continue;
            }

            // Only treat absolute links as external (http/https)
            if (preg_match('#^https?://#i', $href)) {
                $a->setAttribute('target', '_blank');
                $a->setAttribute('rel', 'noopener noreferrer');
            }
        }

        $body = $doc->getElementsByTagName('div')->item(0);
        $inner = '';
        foreach ($body->childNodes as $child) {
            $inner .= $doc->saveHTML($child);
        }

        libxml_clear_errors();
        return $inner;
    }
}
