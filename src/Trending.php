<?php

namespace Bitfumes\Visits;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public $key;

    public function forKey($key = null)
    {
        $this->key = $key;
        return $this;
    }

    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->key, 0, 4));
    }

    public function record($item)
    {
        if ($this->checkIp($item->id)) {
            return false;
        }

        Redis::zincrby($this->key, 1, json_encode([
            'title' => $item->title,
            'path'  => $item->path,
        ]));
        $this->storeIp($item->id);
    }

    public function reset()
    {
        Redis::del($this->key);
    }

    public function removeFromList($item=null)
    {
        Redis::zrem($this->key, json_encode([
            'title' => $item->title,
            'path'  => $item->path
        ]));
    }

    protected function IpKey($itemId)
    {
        return "{$_SERVER['REMOTE_ADDR']}.{$this->key}.{$itemId}";
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
