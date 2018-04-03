<?php


namespace App\Mail;


use App\AppConstants;
use App\Entity\Item;
use App\Entity\Category;
use App\Utils\ItemFilters;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class DueItemReminderMail
{
    /**
     * @var MarkdownParserInterface
     */
    private $markdownParser;
    /**
     * @var ItemFilters
     */
    private $itemFilters;

    public function __construct(MarkdownParserInterface $markdownParser, ItemFilters $itemFilters)
    {
        $this->markdownParser = $markdownParser;
        $this->itemFilters = $itemFilters;
    }

    /**
     * @var $items Item[]
     * @return string
     */
    public function createContent(array $items): string
    {

        $content = '';

        if(getenv('HOSTNAME_OUTER_HOST')){
            $content = 'gogo.' . getenv('HOSTNAME_OUTER_HOST') . PHP_EOL;
        }

        foreach ($items as $item) {
            /** @var $item Item */
            $renderedItem = $this->renderItem($item);
            $renderedItem = $this->markdownParser->transformMarkdown($renderedItem);
            $content .= $renderedItem;
        }

        return $content;
    }

    /**
     * @param Category[] $categories
     * @return string
     */
    private function createCategoryContent(array $categories): string
    {
        $parsed = array_map(
            function ($cat) {
                return $cat->getName();
            },
            $categories
        );
        $parsed = implode($parsed, ', ');
        return '[' . $parsed . '] ';
    }

    /**
     * @param $item
     * @return string
     */
    public function renderItem($item): string
    {
        $renderedItem = $this->itemFilters->replaceMarker($item->getQuestion(), AppConstants::MASK_CHARACTER);

        $stringBeforeLineBreak = strstr($renderedItem, PHP_EOL, true);
        $renderedItem = $stringBeforeLineBreak ? $stringBeforeLineBreak : $renderedItem;

        $renderedItem = substr($renderedItem, 0, 75) . ' ...';

        if ($cats = $item->getCategories()->getValues()) {
            $renderedItem = $this->createCategoryContent($cats) . $renderedItem;
        }

        $renderedItem = '* ' . $renderedItem;
        return $renderedItem;

    }
}