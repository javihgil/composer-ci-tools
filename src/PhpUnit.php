<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Jhg\ComposerCiTools\Inflector\Inflector;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class PhpUnit.
 */
class PhpUnit extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'phpunit';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $binDir = $event->getComposer()->getConfig()->get('bin-dir');

        $resolver->setDefaults(array(
            'phpunit-bin' => $binDir.'/phpunit',
            'phpunit-log-prepend' => '',
            'phpunit-tasks' => [],
        ));
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $taskName = Inflector::camelCaseToDaseCase($name);

        /** @var Event $event */
        list($event) = $arguments;

        $bin = self::getOption('phpunit-bin', $event);
        $configs = self::getOption('phpunit-tasks', $event);
        $config = isset($configs[$taskName]) ? $configs[$taskName] : [];
        $logPrepend = self::getOption('phpunit-log-prepend', $event);

        $command = array(
            $bin,
        );

        if (!empty($config['config'])) {
            $command[] = '-c '.$config['config'];
        }

        $process = new Process(implode(' ', $command));

        $process->mustRun(function ($type, $buffer) use ($event, $logPrepend) {
            self::writeProcessBuffer($type, $buffer, $event, $logPrepend);
        });
    }
}
