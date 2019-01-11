<?php

namespace Bitfumes\Visits;

use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $model;

    /**
     * Visits constructor.
     * @param $model
     * @param $name
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->name  = strtolower((new \ReflectionClass($model))->getShortName());
    }

    public function record()
    {
        if ($this->checkIp()) {
            return false;
        }
        Redis::incr($this->cacheKey());
        $this->storeIp();
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    public function cacheKey()
    {
        return "{$this->name}.{$this->model->id}.reads";
    }

    protected function IpKey()
    {
        return "{$_SERVER['REMOTE_ADDR']}.{$this->cacheKey()}";
    }

    public function storeIp()
    {
        Redis::set($this->IpKey(), true);
        $this->setTimeout();
    }

    public function checkIp()
    {
        return Redis::get($this->IpKey());
    }

    private function setTimeout(): void
    {
        Redis::expire($this->IpKey(), 1800);
    }
}
