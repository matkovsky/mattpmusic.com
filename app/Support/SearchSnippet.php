<?php

namespace App\Support;

use Illuminate\Support\Str;

class SearchSnippet
{
    public static function snippet(string $body, string $query, int $contextWords = 30): string
    {
        $plain = trim(html_entity_decode(strip_tags(Str::markdown($body)), ENT_QUOTES | ENT_HTML5));
        $plain = preg_replace('/\s+/u', ' ', $plain);

        if ($query === '') {
            return e(Str::words($plain, 60, '…'));
        }

        $position = mb_stripos($plain, $query);

        if ($position === false) {
            return e(Str::words($plain, 60, '…'));
        }

        $before = mb_substr($plain, 0, $position);
        $beforeWords = preg_split('/\s+/u', $before, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $beforeSlice = array_slice($beforeWords, -$contextWords);
        $leadingEllipsis = count($beforeWords) > count($beforeSlice) ? '… ' : '';

        $after = mb_substr($plain, $position + mb_strlen($query));
        $afterWords = preg_split('/\s+/u', $after, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $afterSlice = array_slice($afterWords, 0, $contextWords);
        $trailingEllipsis = count($afterWords) > count($afterSlice) ? ' …' : '';

        $matched = mb_substr($plain, $position, mb_strlen($query));

        $snippet = $leadingEllipsis
            . implode(' ', $beforeSlice)
            . ($beforeSlice ? ' ' : '')
            . '__MARK_OPEN__' . $matched . '__MARK_CLOSE__'
            . ($afterSlice ? ' ' : '')
            . implode(' ', $afterSlice)
            . $trailingEllipsis;

        $escaped = e($snippet);

        return str_replace(
            ['__MARK_OPEN__', '__MARK_CLOSE__'],
            ['<mark>', '</mark>'],
            $escaped
        );
    }

    public static function highlight(string $text, string $query): string
    {
        $escaped = e($text);

        if ($query === '') {
            return $escaped;
        }

        $pattern = '/' . preg_quote(e($query), '/') . '/iu';

        return preg_replace($pattern, '<mark>$0</mark>', $escaped) ?? $escaped;
    }
}
