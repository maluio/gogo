<?php

namespace App\Twig;

use App\Repository\ItemRepository;
use App\Utils\DateTimeFormatHelper;
use App\Utils\ItemFilters;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    /**
     * @var DateTimeFormatHelper
     */
    private $dateTimeFormatHelper;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var ItemFilters
     */
    private $itemFilters;

    public function __construct(
        DateTimeFormatHelper $dateTimeFormatHelper, ItemRepository $itemRepository, ItemFilters $itemFilters
    )
    {
        $this->dateTimeFormatHelper = $dateTimeFormatHelper;
        $this->itemRepository = $itemRepository;
        $this->itemFilters = $itemFilters;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('due_since', [$this, 'renderDueDiff']),
            new TwigFilter('hide_words', [$this, 'hideWords'])
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('due_amount', [$this, 'getNumberOfDueCards'])
        ];
    }

    public function renderDueDiff(\DateTime $dueDate): string
    {
        return $this->dateTimeFormatHelper->formatDueDiff($dueDate);
    }

    public function getNumberOfDueCards(): int
    {
        return $this->itemRepository->getNumberOfDueItems();
    }

    public function hideWords(string $text, $maskCharacter=null, $tagClass='badge badge-success', $tagname='span'): string
    {
        return $this->itemFilters->replaceMarkerWithHtmlTag($text, $maskCharacter, $tagname, $tagClass);
    }
}
