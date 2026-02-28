<?php

namespace App\Services;

class SimplePurifier
{
    /**
     * Simple HTML sanitizer without external dependencies
     * Removes dangerous tags and attributes while preserving safe HTML
     *
     * @param string $html The HTML content to sanitize
     * @param string $profile The sanitization profile (default or custom_editor)
     * @return string The sanitized HTML
     */
    public static function clean(string $html, string $profile = 'default'): string
    {
        if (empty($html)) {
            return '';
        }

        // Define allowed tags based on profile
        $allowedTags = self::getAllowedTags($profile);

        // Remove script and style tags completely
        $html = preg_replace(['@<script[^>]*?>.*?</script>@siu', '@<style[^>]*?>.*?</style>@siu'], '', $html);

        // Allow only safe tags
        $html = strip_tags($html, '<' . implode('><', $allowedTags) . '>');

        // Remove dangerous attributes
        $html = preg_replace('/\s+on[a-zA-Z]+\s*=\s*["\']?[^"\']*["\']?/i', '', $html);

        // Remove javascript: protocol
        $html = preg_replace('/javascript:/i', '', $html);

        // Remove data: protocol (except for safe data:image)
        $html = preg_replace('/data:(?!image)/i', '', $html);

        return trim($html);
    }

    /**
     * Get allowed tags based on profile
     *
     * @param string $profile The sanitization profile
     * @return array Array of allowed HTML tags
     */
    private static function getAllowedTags(string $profile = 'default'): array
    {
        $profiles = [
            'default' => [
                'p', 'br', 'strong', 'b', 'em', 'i', 'u',
                'ul', 'ol', 'li',
                'a', 'img',
                'h1', 'h2', 'h3',
                'blockquote',
                'table', 'thead', 'tbody', 'tr', 'td', 'th',
            ],
            'custom_editor' => [
                'p', 'br', 'strong', 'b', 'em', 'i', 'u',
                'ul', 'ol', 'li',
                'a', 'img',
                'h1', 'h2', 'h3',
                'blockquote',
                'table', 'thead', 'tbody', 'tr', 'td', 'th',
                'figure', 'figcaption',
            ],
        ];

        return $profiles[$profile] ?? $profiles['default'];
    }
}
