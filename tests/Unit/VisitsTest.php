<?php

namespace Bitfumes\Visits\Tests\Unit;

use Bitfumes\Visits\Tests\TestCase;
use Bitfumes\Visits\Tests\Dummy\Models\Item;
use Bitfumes\Visits\Visits;
use Illuminate\Support\Facades\Redis;

class VisitsTest extends TestCase
{
    public function setup()
    {
        parent::setUp();
        Redis::del('testing_item.1.reads');
        Redis::del('127.0.0.1.testing_item.1.reads');
    }

    /** @test */
    public function it_can_count_item_visits()
    {
        $item   = factory(Item::class)->create();
        $visits = new Visits($item, 'testing_item');
        $visits->record();
        $this->assertEquals(1, $visits->count());
    }

    /** @test */
    public function it_can_reset_visit_count()
    {
        $item           = factory(Item::class)->create();
        $visits         = new Visits($item, 'testing_item');
        $visits->record();
        $this->assertEquals(1, $visits->count());
        $visits->reset();
        $this->assertEquals(0, $visits->count());
    }
}
