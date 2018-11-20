<?php

namespace Viloveul\Cache\Contracts;

use Psr\SimpleCache\CacheInterface as ICache;
use Viloveul\Cache\Contracts\Adapter as ICacheAdapter;

interface Cache extends ICache
{
    public function getAdapter();

    /**
     * @param ICacheAdapter $adapter
     */
    public function setAdapter(ICacheAdapter $adapter);
}
