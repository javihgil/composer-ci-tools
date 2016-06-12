

For old symfony directory structure

    "extra": {
        "ci-tools": {
            "lint": {
                "yaml-bin": "app/console lint:yaml",
                "twig-bin": "app/console lint:twig"
            },
            "phpunit": {
                "config": "app/phpunit.xml.dist"
            }
        }
    },


For new symfony directory structure

    "extra": {
        "ci-tools": {
            "lint": {
                "yaml-bin": "bin/console lint:yaml",
                "twig-bin": "bin/console lint:twig"
            },
            "phpunit": {
                "config": "phpunit.xml.dist"
            }
        }
    },