<?php

namespace Jhg\ComposerCiTools\Tests\Unitary;

use Composer\Script\Event;
use Mockery as m;

/**
 * Class ScriptTestCase
 */
abstract class ScriptTestCase extends \PHPUnit_Framework_TestCase
{
    protected $composerMock;
    protected $ioMock;
    protected $ioOutput = '';
    protected $packageMock;

    /**
     * Prepares composer mocks
     */
    public function setup()
    {
        // configure io
        $this->ioMock = m::mock('Composer\IO\IOInterface');
        $ioOutput = &$this->ioOutput;
        $this->ioMock->shouldReceive('write')->andReturnUsing(function ($message) use (&$ioOutput) {
            $ioOutput .= $message;
        });

        // configure package
        $this->packageMock = m::mock('Composer\Package\RootPackageInterface');
        $this->packageMock->shouldReceive('getExtra')->andReturn([])->byDefault();

        // configure composer
        $this->composerMock = m::mock('Composer\Composer');
        $this->composerMock->shouldReceive('getPackage')->andReturn($this->packageMock);
    }

    /**
     * @param string $name
     *
     * @return Event
     */
    protected function createEvent($name = '')
    {
        return new Event($name, $this->composerMock, $this->ioMock);
    }

    /**
     * @param array $ciToolsConfig
     */
    protected function setMockExtra(array $ciToolsConfig = [])
    {
        $this->packageMock->shouldReceive('getExtra')->andReturn(['ci-tools'=>$ciToolsConfig]);
    }

    /**
     * @param string $expectedContent
     * @param string $message
     */
    protected function assertOutputContains($expectedContent, $message = '')
    {
        $this->assertRegExp('/'.preg_quote($expectedContent).'/i', $this->ioOutput, $message);
    }

    /**
     * @param string $expectedContent
     * @param string $message
     */
    protected function assertOutputEquals($expectedContent, $message = '')
    {
        $this->assertEquals($expectedContent, $this->ioOutput, $message);
    }
}