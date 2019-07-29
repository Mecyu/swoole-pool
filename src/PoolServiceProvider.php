<?php
/**
 * Created by PhpStorm.
 * User: myron
 * Date: 2019/7/29
 * Time: 下午6:11
 */
namespace Mecyu\SwoolePool;

class PoolServiceProvider
{
    protected $pool;

    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    public function register()
    {
        $this->pool->bind('Redis', function ($pay) {
            return new \Mecyu\SwoolePool\Async\Pools\RedisPool();
        });
        $this->pool->bind('Mysql', function ($pay) {
            return new \Mecyu\SwoolePool\Async\Pools\MysqlPool();
        });
    }

    public function boot()
    {
        //do some code
    }
}