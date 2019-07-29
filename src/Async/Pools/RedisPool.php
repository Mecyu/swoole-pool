<?php
/**
 * Created by PhpStorm.
 * User: myron
 * Date: 2019/7/29
 * Time: 下午5:54
 */
namespace Mecyu\SwoolePool\Async\Pools;

class RedisPool extends Pool
{
    const DEFAULT_PORT = 6379;

    public function __construct($config = array(), $poolSize = 100)
    {
        $config = $config ? $config : config('redis');

        if (empty($config['host'])) {
            throw new \Exception("require redis host option.");
        }
        if (empty($config['port'])) {
            $config = self::DEFAULT_PORT;
        }
        parent::__construct($config, $poolSize);
        $this->create(array($this, 'connect'));
    }

    protected function connect()
    {
        $redis = new \Co\Redis();

        /*
        $redis->on('close', function ($redis)
        {
            $this->remove($redis);
        });
        */

        $res = $redis->connect($this->config['host'], $this->config['port']);

        if ($res == false) {
            $this->failure();
            throw new \Exception("connect to redis server[{$this->config['host']}:{$this->config['port']}] 
                                            failed. Error: {$redis->errMsg}[{$redis->errCode}].");
        } else {
            $this->join($redis);
        }

        return $redis;
    }

    public function __call($call, $params)
    {
        return $this->request(function (\Co\Redis $redis) use ($call, $params) {
            call_user_func_array(array($redis, $call), $params);
            //必须要释放资源，否则无法被其他重复利用
            $this->release($redis);
        });
    }
}