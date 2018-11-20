<?php

namespace Viloveul\Cache\Contracts;

interface Adapter
{
    public function clear();

    /**
     * @param $key
     */
    public function delete($key);

    /**
     * @param $key
     * @param $default
     */
    public function get($key);

    public function getDefaultLifeTime();

    public function getPrefix();

    /**
     * @param $key
     */
    public function has($key);

    /**
     * @param $key
     * @param $value
     * @param $ttl
     */
    public function set($key, $value, $ttl = 3600);

    /**
     * @param $ttl
     */
    public function setDefaultLifeTime($ttl);

    /**
     * @param $key
     */
    public function setPrefix($key);
}
