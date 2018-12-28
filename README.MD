# Installation
make sure your php version > 7.0
```bash
composer require viloveul/cache
```
## How
```php
require __DIR__ . '/vendor/autoload.php';
$adapter = new Viloveul\Cache\ApcuAdapter();
// or
// $adapter = new Viloveul\Cache\RedisAdapter($host, $port);
$adapter->setDefaultLifeTime(3600);
$adapter->setPrefix('secret_maybe');
$cache = new Viloveul\Cache\Cache($adapter);
$cache->set('hello', 'world');
var_dump($cache->get('hello', 'default_value'));
```
