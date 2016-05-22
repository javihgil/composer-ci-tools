<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class PhpCsFixer.
 */
class PhpCsFixer extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'phpcsfixer';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $binDir = $event->getComposer()->getConfig()->get('bin-dir');

        $resolver->setDefaults(array(
            'phpcsfixer-bin' => $binDir.'/php-cs-fixer',
            'phpcsfixer-path' => 'src',
            'phpcsfixer-fail-on-error' => false,
            'phpcsfixer-log-prepend' => '',
            'phpcsfixer-level' => null,
            'phpcsfixer-fixers' => null,
            'phpcsfixer-config' => null,
            'phpcsfixer-dry-run' => false,
        ));
    }

    /**
     * @param Event $event
     */
    public static function fix(Event $event)
    {
        $path = self::getOption('phpcsfixer-path', $event);

        if (!is_array($path)) {
            $path = array($path);
        }

        foreach ($path as $p) {
            self::runFix($p, $event);
        }
    }

    /**
     * @param string $path
     * @param Event  $event
     */
    protected static function runFix($path, Event $event)
    {
        $bin = self::getOption('phpcsfixer-bin', $event);
        $failOnError = self::getOption('phpcsfixer-fail-on-error', $event);
        $logPrepend = self::getOption('phpcsfixer-log-prepend', $event);
        $level = self::getOption('phpcsfixer-level', $event);
        $fixers = self::getOption('phpcsfixer-fixers', $event);
        $config = self::getOption('phpcsfixer-config', $event);
        $dryRun = self::getOption('phpcsfixer-dry-run', $event);

        $command = array(
            $bin,
            'fix',
            $path,
        );

        if ($level) {
            $command[] = '--level='.$level;
        }

        if (!empty($fixers) && is_array($fixers)) {
            $command[] = '--fixers='.implode(',', $fixers);
        }

        if ($config) {
            $command[] = '--config='.$config;
        }

        if ($dryRun === true) {
            $command[] = '--dry-run';
        }

        if (!$failOnError) {
            $command[] = '|| true';
        }

        $process = new Process(implode(' ', $command));

        $process->mustRun(function ($type, $buffer) use ($event, $logPrepend) {
            self::writeProcessBuffer($type, $buffer, $event, $logPrepend);
        });
    }
}
