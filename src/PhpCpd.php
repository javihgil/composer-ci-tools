<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class PhpCpd.
 */
class PhpCpd extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'phpcpd';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $binDir = $event->getComposer()->getConfig()->get('bin-dir');

        $resolver->setDefaults(array(
            'phpcpd-bin' => $binDir.'/phpcpd',
            'phpcpd-include' => array('.'),
            'phpcpd-exclude' => array('vendor'),
            'phpcpd-xml-report-file' => 'php-cpd.xml',
            'phpcpd-min-lines' => 5,
            'phpcpd-min-tokens' => 70,
            'phpcpd-fail-on-error' => false,
            'phpcpd-log-prepend' => '',
        ));
    }

    /**
     * @param Event $event
     */
    public static function report(Event $event)
    {
        $bin = self::getOption('phpcpd-bin', $event);
        $include = self::getOption('phpcpd-include', $event);
        $exclude = self::getOption('phpcpd-exclude', $event);
        $xmlReportFile = self::getOption('phpcpd-xml-report-file', $event);
        $minLines = self::getOption('phpcpd-min-lines', $event);
        $minTokens = self::getOption('phpcpd-min-tokens', $event);
        $failOnError = self::getOption('phpcpd-fail-on-error', $event);
        $logPrepend = self::getOption('phpcpd-log-prepend', $event);

        $command = array(
            $bin,
            '--no-interaction',
            '--min-lines '.$minLines,
            '--min-tokens '.$minTokens,
        );

        if ($xmlReportFile) {
            $command[] = '--log-pmd '.self::getReportResultsPath($event).'/'.$xmlReportFile;
        }

        if ($exclude) {
            $command[] = '--exclude '.implode(' ', $exclude);
        }

        $command[] = implode(' ', $include);

        if (!$failOnError) {
            $command[] = '|| true';
        }

        $process = new Process(implode(' ', $command), null, null, null, null, []);

        $process->mustRun(function ($type, $buffer) use ($event, $logPrepend) {
            self::writeProcessBuffer($type, $buffer, $event, $logPrepend);
        });
    }
}
