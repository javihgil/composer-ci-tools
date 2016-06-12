# Composer CI Tools

This library provides a easy way to integrate some common developing and testing tasks in the middle of composer workflow.

The advantage of include your CI scripts into composer configuration is to use a unique tool (that is also
 installed) that integrates the development and testing logic.

## Configure

    $ composer require javihgil/composer-ci-tools:~1.0 --dev

## Usage

This is an example composer.json:

    {
        "extra": {
            "ci-tools": {
                "global": {
                    "log-format": "  <fg=cyan>> %s</>"
                },
                "phpunit": {
                    "report-config": "phpunit-reports.xml"
                },
                "git": {
                    "commit-msg-regex": "/^[a-z\\s\\-0-9\\.]{20,}$/i",
                    "commit-msg-error": "The commit message must be at least 20 characters long"
                },
                "phpcs" : {
                    "standard": "PSR2",
                }
            }
        },
        "scripts": {
            "post-install-cmd": [
                "Jhg\\ComposerCiTools\\GitHooksInstaller::preCommit",
                "Jhg\\ComposerCiTools\\GitHooksInstaller::commitMsg"
            ],
            "pre-commit-hook": [
                "Jhg\\ComposerCiTools\\Lint::php",
                "Jhg\\ComposerCiTools\\PhpUnit::test"
            ],
            "commit-msg-hook": [
                "Jhg\\ComposerCiTools\\Git::commitMsgRegex"
            ],
            "test": [
                "@composer validate",
                "Jhg\\ComposerCiTools\\PhpCsFixer::fix",
                "Jhg\\ComposerCiTools\\Lint::phpLazy",
                "Jhg\\ComposerCiTools\\PhpUnit::test",
                "Jhg\\ComposerCiTools\\SecurityChecker::check"
            ],
            "report": [
                "@composer install",
                "Jhg\\ComposerCiTools\\PhpUnit::report"
                "Jhg\\ComposerCiTools\\PhpCs::report"
            ]
        }
    }

This composer.json provides a test and a report task. These can be executed with:

    $ composer test

    ./composer.json is valid
    > Jhg\ComposerCiTools\Lint::php
      > No syntax errors detected in ./src/Script/Lint.php
      > No syntax errors detected in ./src/Script/SecurityChecker.php
      > No syntax errors detected in ./src/Script/Gulp.php
      > No syntax errors detected in ./src/Script/AbstractScriptHandler.php
      > No syntax errors detected in ./src/Script/Write.php
      > No syntax errors detected in ./src/Script/GitHooksInstaller.php
      > No syntax errors detected in ./src/Script/PhpCpd.php
      > No syntax errors detected in ./src/Script/PhpCsFixer.php
      > No syntax errors detected in ./src/Script/PhpUnit.php
      > No syntax errors detected in ./src/Script/PhpCs.php
    > Jhg\ComposerCiTools\PhpUnit::test
      > PHPUnit 4.8.24 by Sebastian Bergmann and contributors.
      > Time: 41 ms, Memory: 4.50Mb
      > No tests executed!

    $ composer report

    > Jhg\ComposerCiTools\PhpUnit::report
      > PHPUnit 4.8.24 by Sebastian Bergmann and contributors.
      > Time: 850 ms, Memory: 7.00Mb
      >
      > No tests executed!
      > Generating code coverage report in HTML format ...
      > done


Also uses a preCommit GIT hook, that is configured after install command execution. Every pre-commit Hook
 lauched by GIT will execute the pre-commit-hook task. In this example, the behaviour is that before
 commiting in git php syntax will be verified and PhpUnit tests passed.

## Reference

**Global configurations**
- [Global configuration](docs/01-global-configuration.md)

**Commands**
- [Git Hooks Installer](docs/10-git-hooks-installer.md)
- [SecurityChecker](docs/11-security-checker.md)
- [Lint](docs/12-lint.md)
- [PhpUnit](docs/13-phpunit.md)
- [PhpCpd](docs/14-phpcpd.md)
- [PhpCs](docs/15-phpcs.md)
- [PhpCsFixer](docs/16-php-cs-fixer.md)
- [Gulp](docs/17-gulp.md)
- [Write](docs/18-write.md)

**Misc**
- [Create custom script](docs/30-create-custom-script.md)
- [Composer useful configurations](docs/31-composer-configurations.md)
- [Symfony usual configuration](docs/32-symfony-usual-configuration.md)
- [Common problems](docs/33-common-problems.md)
- [Examples](docs/34-examples.md)

## Note for Windows users

Windows platform is not fully supported. This tool is not tested under Windows environments.

## License

This project is licensed under the terms of the MIT license. See the LICENSE file.
