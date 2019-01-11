<?php

namespace Bitfumes\Visits;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get($key)
    {
        return array_map('json_decode', Redis::zrevrange($key, 0, 4));
    }

    public function set($key, $item)
    {
        if ($this->checkIp($item->id)) {
            return false;
        }

        Redis::zincrby($key, 1, json_encode([
            'title' => $item->title,
            'path'  => $item->path,
        ]));
        $this->storeIp($item->id);
    }

    public function reset($key)
    {
        Redis::del($key);
    }

    public function removeFromList($key, $item=null)
    {
        Redis::zrem($key, json_encode([
            'title' => $item->title,
            'path'  => $item->path
        ]));
    }

    protected function cacheKey()
    {
        return 'items';
    }

    protected function IpKey($itemId)
    {
        return "{$_SERVER['REMOTE_ADDR']}.{$this->cacheKey()}.{$itemId}";
    }

    protected function storeIp($itemId)
    {
        Redis::set($this->IpKey($itemId), true);
        $this->setTimeout($itemId);
    }

    protected function checkIp($itemId)
    {
        return Redis::get($this->IpKey($itemId));
    }

    private function setTimeout($itemId): void
    {
        Redis::expire($this->IpKey($itemId), 1800);
    }
}
