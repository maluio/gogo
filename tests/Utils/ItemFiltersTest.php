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
        $input = 'Hi, please [hide] me';

        $this->assertEquals(
            'Hi, please <span class="marker-replaced">****</span> me',
            $this->itemFilters->replaceMarkerWithHtmlTag($input, '*')
        );
    }

    public function testNoMasking()
    {
        $input = 'Hi, please [hide] me';

        $this->assertEquals(
            'Hi, please <span class="marker-replaced">hide</span> me',
            $this->itemFilters->replaceMarkerWithHtmlTag(
                $input
            )
        );
    }

    public function testTagAndClass()
    {
        $input = 'Hi, please [hide] me';

        $this->assertEquals(
            'Hi, please <span class="classy">****</span> me',
            $this->itemFilters->replaceMarkerWithHtmlTag($input, '*', 'span', 'classy')
        );
    }

    public function testTwoWords()
    {
        $input = 'Hi, please [hide] me and [me], too';

        $this->assertEquals(
            'Hi, please <span class="marker-replaced">****</span> me and <span class="marker-replaced">**</span>, too',
            $this->itemFilters->replaceMarkerWithHtmlTag($input, '*')
        );
    }

    public function testSentenceIsSplitUp()
    {
        $input = 'Hi, please [split me up]';

        $this->assertEquals(
            'Hi, please <span class="marker-replaced">*****</span> <span class="marker-replaced">**</span> <span class="marker-replaced">**</span>',
            $this->itemFilters->replaceMarkerWithHtmlTag($input, '*')
        );
    }

}
