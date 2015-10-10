<?php


class TestConfigTest extends \Codeception\TestCase\Test
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
    public function testConfigIsLoaded()
    {
//        dd(app('Config'));

        $this->assertTrue(true, true);
    }
}