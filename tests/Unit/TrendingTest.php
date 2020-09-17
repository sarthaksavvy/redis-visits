<?php

namespace Bitfumes\Visits\Tests\Unit;

use Bitfumes\Visits\Trending;
use Bitfumes\Visits\Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Bitfumes\Visits\Tests\Dummy\Models\Item;

class TrendingTest extends TestCase
{
    public function setup():void
    {
        parent::setUp();
        Redis::del('127.0.0.1.testing_item.1');
        Redis::del('127.0.0.1.testing_item.2');
        Redis::del('testing_item');
    }

    /** @test */
    public function it_can_set_value_on_redis_with_proper_key()
    {
        $trending  = new Trending();
        $item      = factory(Item::class)->create();
        $item2     = factory(Item::class)->create();
        $trending->forKey('testing_item')->record($item);
        $trending->forKey('testing_item')->record($item2);
        $this->assertEquals(2, count($trending->get('items')));
    }

    /** @test */
    public function it_can_reset_trending_key()
    {
        $trending  = new Trending();
        $item      = factory(Item::class)->create();
        $trending->forKey('testing_item')->record($item);
        $trending->reset('items');
        $this->assertEquals(0, count($trending->get('items')));
    }

    /** @test */
    public function it_can_remove__trending_key()
    {
        $trending          = new Trending();
        $item              = factory(Item::class)->create();
        $trending->forKey('testing_item')->record($item);

        $item2              = factory(Item::class)->create();
        $trending->forKey('testing_item')->record($item2);

        $this->assertEquals(2, count($trending->get('items')));
        $trending->removeFromList($item);
        $this->assertEquals(1, count($trending->get('items')));
    }
}
