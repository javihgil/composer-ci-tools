<?php

namespace Jhg\ComposerCiTools\Tests\Functional;

/**
 * Class GulpTest
 */
class GulpTest extends ScriptFunctionalTestCase
{
    /**
     * @var string
     */
    protected $path;

    /**
     * Prepares tests
     */
    public function setup()
    {
        $this->path = __DIR__.'/Data/gulp';

        // prepare composer
        $this->runComposer('install', $this->path);
    }

    /**
     * Clean up files
     */
    public function tearDown()
    {
        $this->cleanComposerFiles($this->path);
    }

    /**
     * Tests Jhg\ComposerCiTools\Gulp::__callStatic
     */
    public function testSuccessTask()
    {
        $composer = $this->runComposer('gulp-test1', $this->path);

        $this->assertExitCodeSuccess($composer);
        $this->assertOutputContains('gulp-mock-script.php gulp:test1 --param1=1 --param2=2 --option1', $composer);
    }

    /**
     * Tests Jhg\ComposerCiTools\Gulp::__callStatic
     */
    public function testMissingTask()
    {
        $composer = $this->runComposer('gulp-missing-task', $this->path);

        $this->assertExitCodeError($composer);
    }
}