<?php


namespace App\Utils;


class ItemFilters
{

    const HIDDEN_WORD_BEGIN_MARKER = '[';
    const HIDDEN_WORD_END_MARKER = ']';

    private $tagName;
    private $tagClass;

    public function replaceMarkerWithHtmlTag(
        string $text,
        ?string $maskCharacter = null,
        string $tagName='span',
        string $tagClass='marker-replaced'
    ):string
    {
        $this->tagName = $tagName;
        $this->tagClass = $tagClass;

        $pattern = sprintf(
            '/(%s[^%s]+(%s))/',
            // preg_quote escapes reqex reserved characters
            preg_quote(self::HIDDEN_WORD_BEGIN_MARKER),
            preg_quote(self::HIDDEN_WORD_BEGIN_MARKER),
            preg_quote(self::HIDDEN_WORD_END_MARKER)
         );

       return preg_replace_callback(
            $pattern,
            function($match) use ($maskCharacter){
                // it's match[0] only because I suck at regExing
                $sentence = $match[0];
                $words = $this->splitSentenceIntoWords($sentence);
                $out = '';
                foreach ($words as $word){
                    $maskedWord = '';
                    foreach (str_split($word) as $char){
                        if (
                            self::HIDDEN_WORD_BEGIN_MARKER !== $char
                            && self::HIDDEN_WORD_END_MARKER !== $char
                        )
                            $maskedWord .= $maskCharacter ? $maskCharacter : $char;
                    }
                    $out .= $this->addTag($maskedWord) . ' ';
                }
                return trim($out);
            },
            $text
        );
    }

    protected function splitSentenceIntoWords(string $sentence): array {
        return explode(' ', $sentence);
    }

    protected function addTag(string $word): string {
        $openingTag = sprintf(
            '<%s%s>',
            $this->tagName,
            $this->tagClass ? ' class="' . $this->tagClass . '"' : ''
        );

        $closingTag = sprintf(
            '</%s>',
            $this->tagName
        );

        return  $openingTag . $word . $closingTag;
    }

}