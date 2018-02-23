<?php


namespace App\Utils;


class DateTimeProvider
{
    public function now(): \DateTime
    {
        return new \DateTime();
    }

    public function fromString(string $st): \DateTime
    {
        return new \DateTime($st);
    }
}