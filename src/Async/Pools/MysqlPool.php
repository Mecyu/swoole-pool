<?php
/**
 * Created by PhpStorm.
 * User: myron
 * Date: 2019/7/29
 * Time: 下午5:54
 */
namespace Mecyu\SwoolePool\Async\Pools;

class MysqlPool extends Pool
{
    const DEFAULT_PORT = 3306;

    public function __construct($config = [], $maxConnection = 100)
    {
        $config = $config ? $config :config('mysql');
        if (empty($config['host'])) {
            throw new \Exception("require mysql host option.");
        }
        if (empty($config['port'])) {
            $config['port'] = self::DEFAULT_PORT;
        }
        parent::__construct($config, $maxConnection);
        $this->create(array($this, 'connect'));
    }

    protected function connect()
    {

        $db = new \Co\Mysql();
        /*
        $db->on('close', function ($db)
        {
            $this->remove($db);
        });
        */

        $res = $db->connect($this->config);
        if ($res == false) {
            $this->failure();
            throw new \Exception("connect to mysql server[{$this->config['host']}:{$this->config['port']}] 
                                    failed. Error: {$db->connect_error}[{$db->connect_errno}].");
        } else {
            $this->join($db);
        }

        return $db;
    }

    public function query($sql, callable $callabck)
    {
        $this->request(function (\Co\Mysql $db) use ($callabck, $sql) {
            return $db->query($sql, function (\Co\Mysql $db, $result) use ($callabck) {
                call_user_func($callabck, $db, $result);
                $this->release($db);
            });
        });
    }

    public function isFree()
    {
        return $this->taskQueue->count() == 0 and $this->idlePool->count() == count($this->resourcePool);
    }

    /**
     * 关闭连接池
     */
    public function close()
    {
        foreach ($this->resourcePool as $conn) {
            /**
             * @var $conn \Co\Mysql
             */
            $conn->close();
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}