<?php

namespace Jhg\ComposerCiTools\Tests\Functional;

use \Mockery as m;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class GitHooksInstallerTest
 */
class GitHooksInstallerTest extends ScriptFunctionalTestCase
{
    /**
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::applyPatchMsg
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::commitMsg
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::postUpdate
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::preApplyPatch
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::preCommit
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::prepareCommit
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::preparePush
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::prepareRebase
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::prePush
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::preRebase
     * Tests Jhg\ComposerCiTools\GitHooksInstaller::update
     */
    public function testGitHooksInstaller()
    {
        // create git repository
        $fs = new Filesystem();
        $tmpPath = sys_get_temp_dir() .'/'. uniqid('git_hooks_installer_test');
        $fs->mkdir($tmpPath);
        self::exec('git init', $tmpPath);

        // prepare composer.json
        $srcPath = realpath(__DIR__.'/../../src');
        $composerJson = file_get_contents(__DIR__.'/Data/git-hooks-installer/composer.json');
        $fixPathComposerJson = preg_replace('/COMPOSER_CI_TOOLS_SRC_PATH/', $srcPath, $composerJson);
        $fs->dumpFile($tmpPath.'/composer.json', $fixPathComposerJson);

        // install and create composer.lock (Event needs an existing composer.lock, see https://getcomposer.org/doc/articles/scripts.md#command-events)
        $this->runComposer('install', $tmpPath);

        // run command
        $composer = $this->runComposer('install', $tmpPath);

        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::applyPatchMsg', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/applypatch-msg'), '"applypatch-msg" hook was not success installed');

        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::commitMsg', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/commit-msg'), '"commit-msg" hook was not success installed');

        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::postUpdate', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/post-update'), '"post-update" hook was not success installed');

        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::preApplyPatch', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/pre-applypatch'), '"pre-applypatch" hook was not success installed');
        
        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::preCommit', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/pre-commit'), '"pre-commit" hook was not success installed');
        
        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::prepareCommit', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/prepare-commit'), '"prepare-commit" hook was not success installed');
        
        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::preparePush', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/prepare-push'), '"prepare-push" hook was not success installed');
        
        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::prepareRebase', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/prepare-rebase'), '"prepare-rebase" hook was not success installed');
        
        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::prePush', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/pre-push'), '"pre-push" hook was not success installed');

        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::preRebase', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/pre-rebase'), '"pre-rebase" hook was not success installed');
        
        $this->assertErrorOutputContains('Jhg\\ComposerCiTools\\GitHooksInstaller::update', $composer);
        $this->assertTrue(file_exists($tmpPath.'/.git/hooks/update'), '"update" hook was not success installed');

        $fs->remove($tmpPath);
    }
}