<?php

namespace Jhg\ComposerCiTools\Tests\Functional;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Class ScriptFunctionalTestCase
 */
abstract class ScriptFunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $commandLine
     * @param string $path
     */
    public static function exec($commandLine, $path)
    {
        $process = new Process($commandLine, $path.'/');
        $process->mustRun();
    }

    /**
     * @param string $path
     */
    protected function cleanComposerFiles($path)
    {
        $path = rtrim($path, '/');

        $fs = new Filesystem();
        $fs->remove($path.'/vendor');
        $fs->remove($path.'/composer.lock');
        $fs->remove($path.'/composer.phar');
    }

    /**
     * @param string $command
     * @param string $path
     *
     * @return Process
     */
    protected function runComposer($command, $path)
    {
        $path = rtrim($path, '/');

        $composer = new Process('php '.TEST_COMPOSER_PHAR.' '. $command, $path);
        $composer->run();

        return $composer;
    }

    /**
     * @param Process $composer
     */
    protected function assertExitCodeSuccess(Process $composer)
    {
        $this->assertEquals(0, $composer->getExitCode(), 'Expected success error code');
    }

    /**
     * @param Process  $composer
     * @param int|null $error
     */
    protected function assertExitCodeError(Process $composer, $error = null)
    {
        if ($error !== null) {
            $this->assertEquals($error, $composer->getExitCode(), 'Expected '.$error.' error code');
        } else {
            $this->assertNotEquals(0, $composer->getExitCode(), 'Expected not success error code');
        }
    }

    /**
     * @param Process $composer
     */
    protected function assertEmptyErrorOutput(Process $composer)
    {
        $this->assertEmpty($composer->getErrorOutput(), $composer->getErrorOutput());
    }

    /**
     * @param string  $expectedContent
     * @param Process $composer
     * @param string  $message
     */
    protected function assertOutputContains($expectedContent, Process $composer, $message = '')
    {
        $this->assertNotEquals(0, preg_match('/'.preg_quote($expectedContent).'/i', $composer->getOutput()), $message ? $message : sprintf('Failed asserting that output does contain "%s"', $expectedContent));
    }

    /**
     * @param string  $expectedContent
     * @param Process $composer
     * @param string  $message
     */
    protected function assertOutputNotContains($expectedContent, Process $composer, $message = '')
    {
        $this->assertEquals(0, preg_match('/'.preg_quote($expectedContent).'/i', $composer->getOutput()), $message ? $message : sprintf('Failed asserting that output does not contain "%s"', $expectedContent));
    }

    /**
     * @param string  $expectedContent
     * @param Process $composer
     * @param string  $message
     */
    protected function assertErrorOutputContains($expectedContent, Process $composer, $message = '')
    {
        $this->assertNotEquals(0, preg_match('/'.preg_quote($expectedContent).'/i', $composer->getErrorOutput()), $message ? $message : sprintf('Failed asserting that output does contain "%s"', $expectedContent));
    }

    /**
     * @param string  $expectedContent
     * @param Process $composer
     * @param string  $message
     */
    protected function assertErrorOutputNotContains($expectedContent, Process $composer, $message = '')
    {
        $this->assertEquals(0, preg_match('/'.preg_quote($expectedContent).'/i', $composer->getErrorOutput()), $message ? $message : sprintf('Failed asserting that output does not contain "%s"', $expectedContent));
    }
}