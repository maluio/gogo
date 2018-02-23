<?php

namespace App\Tests\Twig;


use App\Twig\AppExtension;
use App\Utils\DateTimeProvider;
use PHPUnit\Framework\TestCase;

class AppExtensionTest extends TestCase
{
    public function testCalcDuePastDates()
    {

        $dtProviderStub = $this->createMock(DateTimeProvider::class);
        $fakeNow = new \DateTime('5 march 2017 7pm');
        $dtProviderStub->method('now')->willReturn($fakeNow);
        $appExtension = new AppExtension($dtProviderStub);


        $dueDate = new \DateTime('3 march 2017 3pm');

        self::assertEquals(
            '- 2 days',
            trim($appExtension->calcDue($dueDate)),
            'DueDate 2 days in the past should show day diff'
        );

        $dueDate = new \DateTime('5 march 2017 3pm');

        self::assertEquals(
            '- 4 hours',
            trim($appExtension->calcDue($dueDate)),
            'DueDate 4 hours in the past should show hour diff'
        );

        $dueDate = new \DateTime('5 march 2017 6:30pm');

        self::assertEquals(
            '- 30 minutes',
            trim($appExtension->calcDue($dueDate)),
            'DueDate 30 minutes in the past should show minute diff'
        );
    }

    public function testCalcDueFutureDates()
    {

        $dtProviderStub = $this->createMock(DateTimeProvider::class);
        $fakeNow = new \DateTime('5 march 2017 7pm');
        $dtProviderStub->method('now')->willReturn($fakeNow);
        $appExtension = new AppExtension($dtProviderStub);

        $dueDate = new \DateTime('5 march 2017 7:30pm');

        self::assertEquals(
            '30 minutes',
            trim($appExtension->calcDue($dueDate)),
            'DueDate 30 minutes in the future should show minute diff'
        );
    }

}
