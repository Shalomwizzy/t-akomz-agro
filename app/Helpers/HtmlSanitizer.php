<?php

namespace App\Helpers;

class HtmlSanitizer
{
    // Tags whose full content (including children) must be removed
    private const BLOCK_ELEMENTS = ['script', 'style', 'iframe', 'object', 'embed', 'applet', 'form', 'svg', 'math', 'link', 'meta'];

    // Tags that may remain after stripping
    private const ALLOWED_TAGS = '<p><br><strong><em><b><i><u><s><del><ins>'
        . '<h1><h2><h3><h4><h5><h6>'
        . '<ul><ol><li>'
        . '<blockquote><pre><code>'
        . '<a><img>'
        . '<table><thead><tbody><tfoot><tr><th><td><caption>'
        . '<hr><div><span><figure><figcaption>';

    public static function clean(string $html): string
    {
        if ($html === '') {
            return '';
        }

        // 1. Strip dangerous block elements and their entire content
        $tags = implode('|', self::BLOCK_ELEMENTS);
        $html = preg_replace('#<(' . $tags . ')\b[^>]*>.*?</\1>#is', '', $html);
        $html = preg_replace('#<(' . $tags . ')\b[^>]*/?\s*>#is', '', $html);

        // 2. Strip all tags not in the allowed list
        $html = strip_tags($html, self::ALLOWED_TAGS);

        // 3. Strip event-handler attributes (onclick, onload, onerror, etc.)
        $html = preg_replace('/\s+on[a-z]+\s*=\s*"[^"]*"/i', '', $html);
        $html = preg_replace('/\s+on[a-z]+\s*=\s*\'[^\']*\'/i', '', $html);
        $html = preg_replace('/\s+on[a-z]+\s*=[^\s>]*/i', '', $html);

        // 4. Block javascript: and vbscript: in href / src / action
        $html = preg_replace('/\b(href|src|action)\s*=\s*"(["\']?)\s*(javascript|vbscript):[^"]*"/i', '$1="#"', $html);
        $html = preg_replace('/\b(href|src|action)\s*=\s*\'(["\']?)\s*(javascript|vbscript):[^\']*\'/i', '$1=\'#\'', $html);

        // 5. Block data: URIs in src (common XSS vector via <img src="data:...">)
        $html = preg_replace('/\bsrc\s*=\s*"["\']?\s*data:[^"]*"/i', '', $html);
        $html = preg_replace('/\bsrc\s*=\s*\'["\']?\s*data:[^\']*\'/i', '', $html);

        return $html;
    }
}
