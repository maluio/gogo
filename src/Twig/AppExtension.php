<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('due_since', [$this, 'calcDue'])
        ];
    }

    public function calcDue(\DateTime $dueDate): string
    {
        $now = new \DateTime();
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
