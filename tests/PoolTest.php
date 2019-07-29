<?php
/**
 * Created by PhpStorm.
 * User: myron
 * Date: 2019/7/29
 * Time: 下午6:53
 */
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    public function testIsPoolInstance()
    {
        go(function () {
            $redis = pool('Redis');
            $this->assertInstanceOf(\Mecyu\SwoolePool\Async\Pools\RedisPool::class, $redis);

            $mysql = pool('Mysql');
            $this->assertInstanceOf(\Mecyu\SwoolePool\Async\Pools\MysqlPool::class, $mysql);
            //redis connection test
            $redis->request(function ($redis) {
                $password = config('redis')['password'];
                $this->assertTrue($redis->auth($password));
            });
            // mysql connection test
            $mysql->request(function ($mysql) {
                $this->assertEquals($mysql->connect_errno, 0);
            });
        });
    }
}