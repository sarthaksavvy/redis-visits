<?php

namespace Bitfumes\Visits\Tests\Unit;

use Bitfumes\Visits\Tests\TestCase;
use Bitfumes\Visits\Trending;
use Bitfumes\Visits\Tests\Dummy\Models\Item;

class TrendingTest extends TestCase
{
    /** @test */
    public function it_can_set_value_on_redis_with_proper_key()
    {
        $trending  = new Trending;
        $item      = factory(Item::class)->create();
        $item2     = factory(Item::class)->create();
        $trending->set('items', $item);
        $trending->set('items', $item2);
        $this->assertEquals(2, count($trending->get('items')));
    }

    /** @test */
    public function it_can_reset_trending_key()
    {
        $trending  = new Trending;
        $item      = factory(Item::class)->create();
        $trending->set('items', $item);
        $trending->reset('items');
        $this->assertEquals(0, count($trending->get('items')));
    }

    /** @test */
    public function it_can_remove__trending_key()
    {
        $trending          = new Trending;
        $item              = factory(Item::class)->create();
        $trending->set('items', $item);

        $item2              = factory(Item::class)->create();
        $trending->set('items', $item2);

        $this->assertEquals(2, count($trending->get('items')));
        $trending->removeFromList('items', $item);
        $this->assertEquals(1, count($trending->get('items')));
    }
}
