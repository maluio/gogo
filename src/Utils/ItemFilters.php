<?php


namespace App\Utils;


class ItemFilters
{

    const HIDDEN_WORD_BEGIN_MARKER = '[';
    const HIDDEN_WORD_END_MARKER = ']';

    public function replaceMarkerWithHtmlTag(
        string $text,
        ?string $maskCharacter = null,
        string $tagName='span',
        string $tagClass='marker-replaced'
    ):string
    {
        $openingTag = sprintf(
            '<%s%s>',
            $tagName,
            $tagClass ? ' class="' . $tagClass . '"' : ''
        );

        $closingTag = sprintf(
            '</%s>',
            $tagName
        );

        $pattern = sprintf(
            '/(%s[^\[]+(%s))/',
            // preg_quote escapes reqex reserved characters
            preg_quote(self::HIDDEN_WORD_BEGIN_MARKER),
            preg_quote(self::HIDDEN_WORD_END_MARKER)
         );

       return preg_replace_callback(
            $pattern,
            function($match) use ($maskCharacter, $openingTag, $closingTag){
                $out = '';
                foreach (str_split($match[0]) as $char){
                   if (
                       self::HIDDEN_WORD_BEGIN_MARKER !== $char
                       && self::HIDDEN_WORD_END_MARKER !== $char
                   )
                    $out .= $maskCharacter ? $maskCharacter : $char;
                }

                return  $openingTag . $out . $closingTag;
            },
            $text
        );
    }

}