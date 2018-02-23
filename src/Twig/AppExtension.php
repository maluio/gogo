<?php

namespace App\Twig;

use App\Repository\ItemRepository;
use App\Utils\DateTimeFormatHelper;
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

    public function __construct(
        DateTimeFormatHelper $dateTimeFormatHelper, ItemRepository $itemRepository
    )
    {
        $this->dateTimeFormatHelper = $dateTimeFormatHelper;
        $this->itemRepository = $itemRepository;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('due_since', [$this, 'renderDueDiff'])
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
}
