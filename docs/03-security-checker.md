# SecurityChecker

This Script Handler allows to search for vulnerabilities in your installed composer dependencies.

Obtain more information about Security Checker in [https://security.sensiolabs.org/](https://security.sensiolabs.org/)

**Usage**

    {
        "scripts": {
            "test": [
                "Jhg\\ComposerCiTools\\SecurityChecker::check"
            ]
        }
    }

## Requirements

If you want to use a project installation (recommended option) you must require security-checker:

    $ composer require sensiolabs/security-checker

## Configuration

**checker-bin**

The bin file. Also you can specify a command in your $PATH.

Default value: "security-checker" in your vendor-bin path.

    {
        "extra": {
            "ci-tools": {
                "security-checker": {
                    "checker-bin": "~/.composer/vendor/bin/security-checker"
                }
            }
        }
    }

**checker-log-prepend**

This option allows you to prepend some string to your security-checker log messages.

    {
        "extra": {
            "ci-tools": {
                "security-checker": {
                    "checker-log-prepend": " >>"
                }
            }
        }
    }