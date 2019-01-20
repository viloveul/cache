<?php

namespace Viloveul\Cache\Contracts;

use ArrayAccess;
use Psr\SimpleCache\CacheInterface as ICache;
use Viloveul\Cache\Contracts\Adapter as ICacheAdapter;

interface Cache extends ICache, ArrayAccess
{
    public function getAdapter(): ICacheAdapter;

    /**
     * @param ICacheAdapter $adapter
     */
    public function setAdapter(ICacheAdapter $adapter);
}
