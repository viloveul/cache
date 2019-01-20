<?php

namespace Viloveul\Cache\Contracts;

interface Adapter
{
    public function clear();

    /**
     * @param string $key
     */
    public function delete(string $key);

    /**
     * @param string     $key
     * @param $default
     */
    public function get(string $key, $default = null);

    public function getDefaultLifeTime();

    public function getPrefix(): string;

    /**
     * @param string $key
     */
    public function has(string $key): bool;

    /**
     * @param string   $key
     * @param $value
     * @param int      $ttl
     */
    public function set(string $key, $value, int $ttl = 3600);

    /**
     * @param int $ttl
     */
    public function setDefaultLifeTime(int $ttl);

    /**
     * @param string $key
     */
    public function setPrefix(string $key);
}
