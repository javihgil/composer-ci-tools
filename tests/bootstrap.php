<?php
require_once __DIR__.'./../vendor/autoload.php';

Jhg\ComposerCiTools\Tests\Functional\ScriptFunctionalTestCase::exec('php -r "copy(\'https://getcomposer.org/installer\', \'composer-setup.php\');"', __DIR__.'/');
Jhg\ComposerCiTools\Tests\Functional\ScriptFunctionalTestCase::exec('php composer-setup.php', __DIR__.'/');
Jhg\ComposerCiTools\Tests\Functional\ScriptFunctionalTestCase::exec('php -r "unlink(\'composer-setup.php\');"', __DIR__.'/');

define('TEST_COMPOSER_PHAR', realpath(__DIR__.'/composer.phar'));