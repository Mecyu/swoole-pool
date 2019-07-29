<?php
/**
 * Created by PhpStorm.
 * User: myron
 * Date: 2019/7/29
 * Time: 下午8:05
 */
namespace Mecyu\SwoolePool;

require_once __DIR__.'/../vendor/autoload.php';

/**
 * Class AsyncPoolServer
 *
 * @package Mecyu\SwoolePool
 */
class AsyncPoolServer
{
    /**
     * @var
     */
    protected $server;

    /**
     * @var Pool|mixed
     */
    protected $pool;

    /**
     * AsyncPoolServer constructor.
     */
    public function __construct()
    {
        $this->server = new \Co\Http\Server('0.0.0.0', 9501);
        $this->pool   = pool('Mysql');
    }

    /**
     * return void
     */
    public function run()
    {
        $this->server->handle('/', function ($request, $response) {
            $pool = $this->pool;
            $this->pool->request(function ($mysql) use ($pool) {
                $strs = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
                $name = substr(str_shuffle($strs), 11, 6);

                $sql = "insert into test1 (`name`, `status`) VALUES ('{$name}', 1)";
                $res = $mysql->query($sql);

                $pool->join($mysql);
            });
            $response->end("Well Done !");
        });

        $this->server->start();
    }
}

go(function () {
    $server = new AsyncPoolServer();
    $server->run();
});