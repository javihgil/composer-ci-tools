<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class SecurityChecker.
 */
class SecurityChecker extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'security-checker';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $binDir = $event->getComposer()->getConfig()->get('bin-dir');

        $resolver->setDefaults(array(
            'security-checker-bin' => $binDir.'/security-checker',
            'security-checker-log-prepend' => '',
        ));
    }

    /**
     * @param Event $event
     */
    public static function check(Event $event)
    {
        $logPrepend = self::getOption('security-checker-log-prepend', $event);

        $process = new Process(self::getOption('security-checker-bin', $event).' security:check composer.lock --ansi');

        $process->mustRun(
            function ($type, $buffer) use ($event, $logPrepend) {
                self::writeProcessBuffer($type, $buffer, $event, $logPrepend);
            }
        );
    }
}
