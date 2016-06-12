# Composer configurations

## Reference scripts

https://getcomposer.org/doc/articles/scripts.md#referencing-scripts

## Calling composer comands

https://getcomposer.org/doc/articles/scripts.md#calling-composer-commands

Latest versions of composer add a new feature

    "@composer validate"

In previous versions you can use the console command:

    "composer validate"

## Script namespaces

Is posible to use namespace command syntax in scripts.

Examples:

- assets:compile
- assets:clear
- test:php

## Configure bin dir

    {
        "config": {
            "bin-dir": "vendor/bin"
        }
    }

## Configure process timeout

    {
        "config": {
            "process-timeout": 0
        }
    }

## Show comments and blank lines in composer logs

    {
        "scripts": {
            "test": [
                "",
                "# This is a sad comment",
                "# <fg=yellow>This is a happy yellow comment</>",
                "",
            ]
        }
    }