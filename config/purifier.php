<?php

use Illuminate\Support\Arr;

return [
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | You can add multiple named settings. Use these names in Purifier::clean(
    | ..., 'custom_name') when cleaning input.
    |
    */
    'settings' => [
        'default' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.SafeIframe' => false,
            'HTML.Allowed' => 'p,br,strong,b,em,i,u,ul,ol,li,a[href|title|target|rel],img[src|alt|width|height],h1,h2,h3,blockquote,table,thead,tbody,tr,td,th',
            'CSS.AllowedProperties' => '',
            'AutoFormat.AutoParagraph' => true,
        ],

        // Secure editor profile for user-generated blog content
        'custom_editor' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            // Only allow common structural tags and images/links; no script, no style attributes
            'HTML.Allowed' => 'p,br,strong,b,em,i,u,ul,ol,li,a[href|title|target|rel],img[src|alt|width|height],h1,h2,h3,blockquote,table,thead,tbody,tr,td,th,figure,figcaption',
            'HTML.AllowedAttributes' => [
                'a.href',
                'a.title',
                'a.target',
                'a.rel',
                'img.src',
                'img.alt',
                'img.width',
                'img.height'
            ],
            // Disallow inline style/CSS to minimize XSS risk; enable only if needed and sanitized
            'CSS.AllowedProperties' => '',
            'AutoFormat.AutoParagraph' => true,
            'Attr.AllowedFrameTargets' => ['_blank'],
            // Make URIs absolute to avoid javascript: URIs (Purifier handles this)
            'URI.MakeAbsolute' => false,
        ],
    ],
];
