<?php

namespace Jhg\ComposerCiTools\Tests\Functional;

/**
 * Class SecurityCheckerTest
 */
class SecurityCheckerTest extends ScriptFunctionalTestCase
{

    /**
     * Tests Jhg\ComposerCiTools\SecurityChecker::check
     */
    public function testSuccessWithNoVulnerabilities()
    {
        $path = __DIR__.'/Data/security-checker-ok';

        // prepare composer
        $this->runComposer('install', $path);

        $composer = $this->runComposer('test', $path);

        // cleanup data
        $this->cleanComposerFiles($path);

        $this->assertOutputContains('0 packages have known vulnerabilities', $composer);
        $this->assertExitCodeSuccess($composer);
    }

    /**
     * Tests Jhg\ComposerCiTools\SecurityChecker::check
     */
    public function testFailWithVulnerability()
    {
        $path = __DIR__.'/Data/security-checker-fail';

        // prepare composer
        $this->runComposer('install', $path);

        $composer = $this->runComposer('test', $path);

        // cleanup data
        $this->cleanComposerFiles($path);

        $this->assertOutputContains('1 package has known vulnerabilities', $composer);
        $this->assertOutputContains('yaml (v2.0.21)', $composer);
        $this->assertExitCodeError($composer);
    }
}