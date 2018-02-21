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
        $diff = $dueDate->diff($now);

        $format = '%R %i minutes';
        if ($diff->h > 1){
            $format = '%R %h hours';
        }
        if ($diff->days > 0){
            $format = '%R %d days';
        }

        return $diff->format($format);
    }
}
