<?php 

class WrapperTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateInstance()
    {
        $adapter = new Viloveul\Cache\RedisAdapter();
        $cache = new Viloveul\Cache\Cache($adapter);
        $this->tester->assertInstanceOf(Viloveul\Cache\Contracts\Cache::class, $cache);
    }
}