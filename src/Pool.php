<?php
/**
 * Created by PhpStorm.
 * User: myron
 * Date: 2019/7/29
 * Time: 下午5:39
 */
namespace Mecyu\SwoolePool;

class Pool
{
    protected $binds;

    protected $instances;

    /**
     * Pay constructor.
     */
    public function __construct()
    {
        $this->serviceRegister();
    }

    /**
     * bind service
     *
     * @param $abstract
     * @param $concrete
     */
    public function bind($abstract, $concrete)
    {
        if ($concrete instanceof \Closure) {
            $this->binds[$abstract] = $concrete;
        } else {
            $this->instances[$abstract] = $concrete;
        }
    }

    /**
     * make class
     *
     * @param $abstract
     * @param array $parameters
     *
     * @return mixed
     */
    public function make($abstract, $parameters = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        array_unshift($parameters, $this);

        return call_user_func_array($this->binds[$abstract], $parameters);
    }

    /**
     * register service
     */
    protected function serviceRegister()
    {
        $service = new PoolServiceProvider($this);
        $service->register();
    }
}
