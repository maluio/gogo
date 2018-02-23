<?php


namespace App\Utils;


class DateTimeFormatHelper
{
    const DATE_TIME_DEFAULT = 'Y-m-d H:i:s';

    const DB_DATE_TIME = 'Y-m-d H:i:s';

    /**
     * @var DateTimeProvider
     */
    private $dateTimeProvider;

    public function __construct(DateTimeProvider $dateTimeProvider)
    {
        $this->dateTimeProvider = $dateTimeProvider;
    }

    public function formatDueDiff(\DateTime $dueDate): string
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