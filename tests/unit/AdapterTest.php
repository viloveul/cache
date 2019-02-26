<?php 

class AdapterTest extends \Codeception\Test\Unit
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
        $this->tester->assertInstanceOf(Viloveul\Cache\Contracts\Adapter::class, $adapter);
    }

    public function testSetValue()
    {
        $adapter = new Viloveul\Cache\RedisAdapter();
        $adapter->set('foo', 'bar');
        $this->tester->assertTrue($adapter->has('foo'));
    }

    public function testGetValue()
    {
        $adapter = new Viloveul\Cache\RedisAdapter();
        $adapter->set('foo', 'bar');
        $this->tester->assertEquals('bar', $adapter->get('foo'));
    }

    public function testDelete()
    {
        $adapter = new Viloveul\Cache\RedisAdapter();
        $adapter->set('foo', 'bar');
        $adapter->delete('foo');
        $this->tester->assertFalse($adapter->has('foo'));
    }
}