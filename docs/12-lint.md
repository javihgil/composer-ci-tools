# Lint

This Script Handler integrates syntax validation of php, twig and yaml files.

**Usage**

    {
        "scripts": {
            "pre-commit-hook": [
                "Jhg\\ComposerCiTools\\Lint::phpLazy",
                "Jhg\\ComposerCiTools\\Lint::yamlLazy",
                "Jhg\\ComposerCiTools\\Lint::twigLazy"
            ],
            "test": [
                "Jhg\\ComposerCiTools\\Lint::php",
                "Jhg\\ComposerCiTools\\Lint::yaml",
                "Jhg\\ComposerCiTools\\Lint::twig"
            ]
        }
    }

## PHP linter

Validates PHP syntax of included .php files.

### Requirements

No special requirements for php syntax validation, just php.

### Lazy linter

Validates PHP syntax of changed .php files. Requires a GIT repository.

### Configuration

**lint-php-include**

Indicates witch paths needs to be linted.

Default value: \['.'\]

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "php-include": ["src"]
                }
            }
        }
    }

**lint-php-exclude**

Exclude paths from syntax validation.

Default value: \['./vendor/\*', './var/\*', './bin/\*'\]

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "php-exclude": ["./app/*", "./bin/*", "./vendor/*", "./var/*", "./web/*"]
                }
            }
        }
    }

**lint-php-log-prepend**

This option allows you to prepend some string to your php lint log messages.

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "php-log-prepend": " >>"
                }
            }
        }
    }

## TWIG linter

Validates TWIG syntax of included .twig files.

### Requirements

In Symfony projects you can use the console lint:twig command.

In other projects maybe you need to install a twig linter package:

    $ composer require asm89/twig-lint

### Lazy linter

Validates TWIG syntax of changed .twig files. Requires a GIT repository.

### Configuration

**lint-twig-bin**

The bin file.

Default value: "twig-lint" in your vendor-bin path.

     {
        "extra": {
            "ci-tools": {
                "lint": {
                    "twig-bin": "~/.composer/vendor/bin/twig-lint"
                }
            }
        }
    }

For Symfony projects, you should use console twig linter:

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "twig-bin": "bin/console lint:twig"
                }
            }
        }
    }

Also you can specify a command in your $PATH.

    {
        "extra": {
            "ci-tools": {
                "security-checker": {
                    "checker-bin": "security-checker"
                }
            }
        }
    }

**lint-twig-include**

Indicates witch paths needs to be linted.

Default value: \['.'\]

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "twig-include": ["src"]
                }
            }
        }
    }

**lint-twig-exclude**

Exclude paths from syntax validation.

Default value: \['./vendor/\*', './var/\*', './bin/\*'\]

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "twig-exclude": ["./app/*", "./bin/*", "./vendor/*", "./var/*", "./web/*"]
                }
            }
        }
    }

**lint-twig-log-prepend**

This option allows you to prepend some string to your twig lint log messages.

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "twig-log-prepend": " >>"
                }
            }
        }
    }

## YAML linter

Validates YAML syntax of included .yml files.

### Requirements

In Symfony projects you can use the console lint:yaml command.

In other projects maybe you need to install a yaml linter package:

    $ composer require hexmedia/yaml-linter

### Lazy linter

Validates YAML syntax of changed .yml files. Requires a GIT repository.

### Configuration

**lint-yaml-bin**

The bin file.

Default value: "yaml-lint" in your vendor-bin path.

     {
        "extra": {
            "ci-tools": {
                "lint": {
                    "yaml-bin": "~/.composer/vendor/bin/yaml-lint"
                }
            }
        }
    }

For Symfony projects, you should use console yaml linter:

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "yaml-bin": "bin/console lint:yaml"
                }
            }
        }
    }

Also you can specify a command in your $PATH.

    {
        "extra": {
            "ci-tools": {
                "security-checker": {
                    "checker-bin": "security-checker"
                }
            }
        }
    }

**lint-yaml-include**

Indicates witch paths needs to be linted.

Default value: \['.'\]

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "yaml-include": ["src"]
                }
            }
        }
    }

**lint-yaml-exclude**

Exclude paths from syntax validation.

Default value: \['./vendor/\*', './var/\*', './bin/\*'\]

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "yaml-exclude": ["./app/*", "./bin/*", "./vendor/*", "./var/*", "./web/*"]
                }
            }
        }
    }

**lint-yaml-log-prepend**

This option allows you to prepend some string to your yaml lint log messages.

    {
        "extra": {
            "ci-tools": {
                "lint": {
                    "yaml-log-prepend": " >>"
                }
            }
        }
    }