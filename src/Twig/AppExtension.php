<?php

namespace App\Twig;

use App\Utils\DateTimeProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{


    /**
     * @var DateTimeProvider
     */
    private $dateTimeProvider;

    public function __construct(DateTimeProvider $dateTimeProvider)
    {
        $this->dateTimeProvider = $dateTimeProvider;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('due_since', [$this, 'calcDue'])
        ];
    }

    public function calcDue(\DateTime $dueDate): string
    {
        $now = $this->dateTimeProvider->now();
        $diff = $now->diff($dueDate);

        $format = '%r %i minutes';
        if ($diff->h > 1){
            $format = '%r %h hours';
        }
        if ($diff->days > 0){
            $format = '%r %d days';
        }

        return $diff->format($format);
    }
}
