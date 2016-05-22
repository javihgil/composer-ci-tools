<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class PhpCs.
 */
class PhpCs extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'phpcs';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $binDir = $event->getComposer()->getConfig()->get('bin-dir');

        $resolver->setDefaults(array(
            'phpcs-bin' => $binDir.'/phpcs',
            'phpcs-include' => array('.'),
            'phpcs-exclude' => array('vendor'),
            'phpcs-report' => 'checkstyle',
            'phpcs-xml-report-file' => 'php-checkstyle.xml',
            'phpcs-standard' => 'psr2',
            'phpcs-fail-on-error' => false,
            'phpcs-log-prepend' => '',
        ));

        $resolver->isRequired('phpcs-bin');
    }

    /**
     * @param Event $event
     */
    public static function report(Event $event)
    {
        $bin = self::getOption('phpcs-bin', $event);
        $include = self::getOption('phpcs-include', $event);
        $exclude = self::getOption('phpcs-exclude', $event);
        $report = self::getOption('phpcs-report', $event);
        $xmlReportFile = self::getOption('phpcs-xml-report-file', $event);
        $standard = self::getOption('phpcs-standard', $event);
        $failOnError = self::getOption('phpcs-fail-on-error', $event);
        $logPrepend = self::getOption('phpcs-log-prepend', $event);

        $command = array(
            $bin,
            '--report='.$report,
            '--standard='.$standard,
        );

        if ($xmlReportFile) {
            $command[] = '--report-file='.self::getReportResultsPath($event).'/'.$xmlReportFile;
        }

        if ($exclude) {
            $command[] = implode(' ', array_map(function ($element) {
                return '--ignore='.$element;
            }, $exclude));
        }

        $command[] = implode(' ', $include);

        if (!$failOnError) {
            $command[] = '|| true';
        }

        $process = new Process(implode(' ', $command));

        $process->mustRun(function ($type, $buffer) use ($event, $logPrepend) {
            self::writeProcessBuffer($type, $buffer, $event, $logPrepend);
        });
    }
}
