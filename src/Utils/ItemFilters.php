<?php


namespace App\Utils;


class ItemFilters
{

    const HIDDEN_WORD_BEGIN_MARKER = '[';
    const HIDDEN_WORD_END_MARKER = ']';

    private $tagName;
    private $tagClass;

    public function splitMarkerString(string $inputStr): array
    {

        $output = [];

        $currentPart['hidden'] = false;
        $currentPart['string'] = '';

        $chars = str_split($inputStr);

        for ($i=0; $i<count($chars); $i++) {
            $char = $chars[$i];
            if ($char === self::HIDDEN_WORD_BEGIN_MARKER
                && isset($chars[$i+1])
                && $chars[$i+1] === self::HIDDEN_WORD_BEGIN_MARKER
            ) {
                $output[] = $currentPart;
                $currentPart['hidden'] = true;
                $currentPart['string'] = '';
                $i++;
                continue;
            }

            if ($char === self::HIDDEN_WORD_END_MARKER
                && isset($chars[$i+1])
                && $chars[$i+1] === self::HIDDEN_WORD_END_MARKER

            ) {
                $output[] = $currentPart;
                $currentPart['hidden'] = false;
                $currentPart['string'] = '';
                $i++;
                continue;
            }
            if ($currentPart['hidden'] && $char == ' '){
                $output[] = $currentPart;
                $currentPart['hidden'] = false;
                $currentPart['string'] = $char;
                $output[] = $currentPart;
                $currentPart['hidden'] = true;
                $currentPart['string'] = '';
                continue;
            }

            $currentPart['string'] .= $char;
        }

        if($currentPart['string']){
            $output[] = $currentPart;
        }

        return $output;
    }

    public function replaceMarkerWithHtmlTag(
        string $text,
        ?string $maskCharacter = null,
        string $tagName = 'span',
        string $tagClass = 'marker-replaced'
    ): string
    {
        $this->tagName = $tagName;
        $this->tagClass = $tagClass;

        $splitText = $this->splitMarkerString($text);

        $output = '';

        foreach ($splitText as $part){
           if($part['hidden']){
               $word = $maskCharacter ? $this->maskString($part['string'], $maskCharacter) : $part['string'];
               $output .= $this->addTag($word);
               continue;
           }

           $output .= $part['string'];
        }

        return $output;
    }

    protected function maskString(string $input, string $maskCharacter): string {
       $count = count(str_split($input));
        return str_repeat($maskCharacter, $count);
    }


    protected function addTag(string $word): string
    {
        $openingTag = sprintf(
            '<%s%s>',
            $this->tagName,
            $this->tagClass ? ' class="' . $this->tagClass . '"' : ''
        );

        $closingTag = sprintf(
            '</%s>',
            $this->tagName
        );

        return $openingTag . $word . $closingTag;
    }

}