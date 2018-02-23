<?php

namespace App\Twig;

use App\Utils\DateTimeFormatHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

    /**
     * @var DateTimeFormatHelper
     */
    private $dateTimeFormatHelper;

    public function __construct(DateTimeFormatHelper $dateTimeFormatHelper)
    {
        $this->dateTimeFormatHelper = $dateTimeFormatHelper;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('due_since', [$this, 'renderDueDiff'])
        ];
    }

    public function renderDueDiff(\DateTime $dueDate): string
    {
        return $this->dateTimeFormatHelper->formatDueDiff($dueDate);
    }
}
