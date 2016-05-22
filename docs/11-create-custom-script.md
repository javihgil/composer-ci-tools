# Create custom scripts

## Create script class

Create your custom class extending Jhg\ComposerCiTools\AbstractScriptHandler:

It's recommended use another namespace out of your application namespace to separate your logic from CI logic.

    <?php
    namespace Acme\ComposerCiTools;

    use Composer\Script\Event;
    use Jhg\ComposerCiTools\AbstractScriptHandler;

    /**
     * Class MyCustomScript.
     */
    class MyCustomScript extends AbstractScriptHandler
    {

    }

Create action:

    /**
     * @param Event $event
     */
    public static function someAction(Event $event)
    {
        // do something
    }

Define your configuration:

    use Symfony\Component\OptionsResolver\OptionsResolver;

    /**
     * @return string
     */
    protected static function getName()
    {
        return 'my-script';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $resolver->setDefaults(array(
            'config1' => 'default-value',
        ));
    }

Configure your script in the composer.json:

    {
        "extra": {
            "ci-tools": {
                "my-script": {
                    "config1": "custom-value"
                }
            }
        }
    }

Use your configuration:

    $config1 = self::getOption('my-script-config1', $event);

Use global configuration:

    self::getOption('report-results-path', $event);
    self::getTestResultsPath($event);

## Configure your script

Before use your script you must configure autoload-dev:

    {
        "autoload-dev": {
            "psr-4": {
                "Acme\\ComposerCiTools\\": "<your-ci-tools-path>"
            }
        }
    }

Now you can use it in scripts:

    {
        "scripts": {
            "post-install-cmd": [
                "Acme\\ComposerCiTools\\MyCustomScript::someAction"
            ]
        }
    }

## Define your own CI Tools repository

If you want to share your own ci-tools you can create your own package.
