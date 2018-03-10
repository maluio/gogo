<?php

namespace App\Tests\Utils;

use App\Utils\ItemFilters;
use PHPUnit\Framework\TestCase;


class ItemFiltersTest extends TestCase
{
    /**
     * @var ItemFilters
     */
    private $itemFilters;

    public function setUp()
    {
        $this->itemFilters = new ItemFilters();
    }

    public function testTagOnly()
    {
        $input = 'Hi, please [[hide]] me';

        $this->assertEquals(
            'Hi, please <span class="marker-replaced">****</span> me',
            $this->itemFilters->replaceMarker($input, '*', 'span', 'marker-replaced')
        );
    }

    public function testNoMasking()
    {
        $input = 'Hi, please [[hide]] me';

        $this->assertEquals(
            'Hi, please <span class="marker-replaced">hide</span> me',
            $this->itemFilters->replaceMarker(
                $input, null, 'span', 'marker-replaced'
            )
        );
    }

    public function testTagAndClass()
    {
        $input = 'Hi, please [[hide]] me';

        $this->assertEquals(
            'Hi, please <span class="classy">****</span> me',
            $this->itemFilters->replaceMarker($input, '*', 'span', 'classy')
        );
    }

    public function testTwoWords()
    {
        $input = 'Hi, please [[hide]] me and [[me]], too';

        $this->assertEquals(
            'Hi, please <span class="marker-replaced">****</span> me and <span class="marker-replaced">**</span>, too',
            $this->itemFilters->replaceMarker($input, '*', 'span', 'marker-replaced')
        );
    }

    public function testSentenceIsSplitUp()
    {
        $input = 'Hi, please [[split me up]]';

        $this->assertEquals(
            'Hi, please <span class="marker-replaced">*****</span> <span class="marker-replaced">**</span> <span class="marker-replaced">**</span>',
            $this->itemFilters->replaceMarker($input, '*', 'span', 'marker-replaced')
        );
    }

    public function testSplitMarkerString()
    {
        $input = 'Hi, please [[hide]] me and [[me]] [[too]] and [[me too]], please';

        $this->assertEquals(
            [
                0 => [
                   'string' => 'Hi, please ',
                    'hidden' => false
                ],
                1 => [
                    'string' => 'hide',
                    'hidden' => true

                ],
                2 => [
                    'string' => ' me and ',
                    'hidden' => false
                ],
                3 => [
                    'string' => 'me',
                    'hidden' => true
                ],
                4 => [
                    'string' => ' ',
                    'hidden' => false
                ],
                5 => [
                    'string' => 'too',
                    'hidden' => true
                ],
                6 => [
                    'string' => ' and ',
                    'hidden' => false
                ],
                7 => [
                    'string' => 'me',
                    'hidden' => true
                ],
                8 => [
                    'string' => ' ',
                    'hidden' => false
                ],
                9 => [
                    'string' => 'too',
                    'hidden' => true
                ],
                10 => [
                    'string' => ', please',
                    'hidden' => false
                ],
            ],
            $this->itemFilters->splitMarkerString($input)
        );
    }

}
