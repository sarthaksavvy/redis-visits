<?php

namespace Bitfumes\Visits\Tests\Unit;

use Bitfumes\Visits\Tests\TestCase;
use Bitfumes\Visits\Tests\Dummy\Models\Item;
use Bitfumes\Visits\Visits;

class VisitsTest extends TestCase
{
    /** @test */
    public function it_can_count_item_visits()
    {
        $item   = factory(Item::class)->create();
        $visits = new Visits($item);
        $visits->record();
        $this->assertEquals(1, $visits->count());
    }

    /** @test */
    public function it_can_reset_visit_count()
    {
        $item           = factory(Item::class)->create();
        $visits         = new Visits($item);
        $visits->record();
        $this->assertEquals(1, $visits->count());
        $visits->reset();
        $this->assertEquals(0, $visits->count());
    }
}
