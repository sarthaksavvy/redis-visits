<?php

namespace Bitfumes\Visits;

use Illuminate\Support\Facades\Redis;

abstract class RedisBase
{
    protected function storeIp()
    {
        Redis::set($this->IpKey(), true);
        $this->setTimeout();
    }

    protected function checkIp()
    {
        return Redis::get($this->IpKey());
    }

    private function setTimeout(): void
    {
        Redis::expire($this->IpKey(), 1800);
    }
}
