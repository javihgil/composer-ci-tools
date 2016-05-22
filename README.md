# Composer CI Tools

This library provides a easy way to integrate some common developing and testing tasks in the middle of composer workflow.

The advantage of include your CI scripts into composer configuration is to use a unique tool (that is also
 installed) that integrates the development and testing logic.

## Configure

    $ composer require javihgil/composer-ci-tools:dev-master --dev
    

## Usage

This is an example composer.json:

    {
        "extra": {
            "ci-tools": {
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
                "@composer validate",
                "Jhg\\ComposerCiTools\\PhpCsFixer::fix",
                "Jhg\\ComposerCiTools\\Lint::phpLazy",
                "Jhg\\ComposerCiTools\\SecurityChecker::check"
            ],
            "commit-msg-hook": [
                "Jhg\\ComposerCiTools\\Git::commitMsgRegex"
            ],
            "report": [
                "@composer install",
                "Jhg\\ComposerCiTools\\PhpCs::report"
            ]
        }
    }

## Reference

- [Global configuration](docs/01-global-configuration.md)
- [Git Hooks Installer](docs/02-git-hooks-installer.md)
- [SecurityChecker](docs/03-security-checker.md)
- [Create custom script](docs/11-create-custom-script.md)

## Note for Windows users

Windows platform is not fully supported. This tool is not tested under Windows environments.

## License

This project is licensed under the terms of the MIT license. See the LICENSE file.
