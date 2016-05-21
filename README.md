# Composer CI Tools

This library provides a easy way to integrate some common developing and testing tasks in the middle of composer workflow.

The advantage of include your CI scripts into composer configuration is to use a unique tool (that is also
 installed) that integrates the development and testing logic.

## Configure

    $ composer require javihgil/composer-ci-tools:dev-master --dev
    

## Usage

This is an example composer.json:

    {
        "scripts": {
            "post-install-cmd": [
                "Jhg\\ComposerCiTools\\GitHooksInstaller::preCommit"
            ],
            "pre-commit-hook": [
                "@composer validate",
                "Jhg\\ComposerCiTools\\Lint::phpLazy",
                "Jhg\\ComposerCiTools\\SecurityChecker::check"
            ]
        }
    }

## Reference

- [Global configuration](docs/01-global-configuration.md)
- [Git Hooks Installer](docs/02-git-hooks-installer.md)
- [SecurityChecker](docs/03-security-checker.md)

## Note for Windows users

Windows platform is not fully supported. This tool is not tested under Windows environments.

## License

This project is licensed under the terms of the MIT license. See the LICENSE file.
