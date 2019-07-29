<?php
/**
 * Created by PhpStorm.
 * User: myron
 * Date: 2019/7/29
 * Time: 下午6:29
 */

if (!function_exists('config')) {
    /**
     * @param string $key
     * @return mixed
     */
    function config(string $key)
    {
        $config = require(__DIR__.'/../Config/pool.php');

        return $config[$key];
    }
}

if (!function_exists('pool')) {
    function pool($abstract = '')
    {
        $pool = new Mecyu\SwoolePool\Pool();
        if ($abstract === '') {
            return $pool;
        } else {
            return $pool->make($abstract);
        }
    }
}