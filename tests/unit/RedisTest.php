<?php 

class RedisTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $myCache;

    protected function _before()
    {
        $adapter = new Viloveul\Cache\RedisAdapter();
        $this->myCache = new Viloveul\Cache\Cache($adapter);
    }

    protected function _after()
    {
    }

    // tests
    public function testAdapterInstance()
    {
        $this->tester->assertInstanceOf(
            Viloveul\Cache\Contracts\Adapter::class,
            $this->myCache->getAdapter()
        );
    }

    public function testSetValue()
    {
        $this->myCache->set('foo', 'bar');
        $this->tester->assertTrue(
            $this->myCache->has('foo')
        );
    }

    public function testGetValue()
    {
        $this->tester->assertEquals('bar', $this->myCache->get('foo'));
    }

    public function testDelete()
    {
        $this->myCache->delete('foo');
        $this->tester->assertFalse($this->myCache->has('foo'));
    }
}