<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class AbstractScriptHandler.
 */
abstract class AbstractScriptHandler
{
    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureGlobalOptions(OptionsResolver $resolver, Event $event)
    {
        $resolver->setDefaults(array(
            'error-format' => '    <fg=red>%s</>',
            'warning-format' => '    <fg=yellow>%s</>',
            'log-format' => '    %s',
            'report-results-path' => 'reports',
            'test-results-path' => 'reports',
        ));
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
    }

    /**
     * @return string
     */
    protected static function getName()
    {
        return '';
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    protected static function getOptions(Event $event)
    {
        $resolver = new OptionsResolver();

        static::configureGlobalOptions($resolver, $event);
        static::configureOptions($resolver, $event);

        $extra = $event->getComposer()->getPackage()->getExtra();

        $processedOptions = isset($extra['ci-tools']['global']) && is_array($extra['ci-tools']['global']) ? $extra['ci-tools']['global'] : [];

        if (isset($extra['ci-tools'][static::getName()]) && is_array($extra['ci-tools'][static::getName()])) {
            foreach ($extra['ci-tools'][static::getName()] as $key => $value) {
                $processedOptions[static::getName().'-'.$key] = $value;
            }
        }

        return $resolver->resolve($processedOptions);
    }

    /**
     * @param string $name
     * @param Event  $event
     *
     * @return mixed
     */
    protected static function getOption($name, Event $event)
    {
        $options = self::getOptions($event);

        return $options[$name];
    }

    /**
     * @param string $type
     * @param string $buffer
     * @param Event  $event
     * @param string $bufferPrepend
     */
    protected static function writeProcessBuffer($type, $buffer, Event $event, $bufferPrepend = '')
    {
        $bufferLines = explode("\n", trim($buffer));

        foreach ($bufferLines as $buffer) {
            if (Process::ERR === $type) {
                $event->getIO()->writeError(
                    sprintf(self::getOption('error-format', $event), $bufferPrepend.$buffer)
                );
            } else {
                $event->getIO()->write(sprintf(self::getOption('log-format', $event), $bufferPrepend.$buffer));
            }
        }
    }

    /**
     * @param string $message
     * @param Event  $event
     */
    protected static function warning($message, Event $event)
    {
        $event->getIO()->write(sprintf(self::getOption('warning-format', $event), $message));
    }

    /**
     * @param string $message
     * @param Event  $event
     * @param string $formatOption
     */
    protected static function log($message, Event $event, $formatOption = 'log-format')
    {
        $event->getIO()->write(sprintf(self::getOption($formatOption, $event), $message));
    }

    /**
     * @param Event $event
     *
     * @return string
     */
    protected static function getReportResultsPath(Event $event)
    {
        $reportsPath = self::getOption('report-results-path', $event);

        if (!is_dir($reportsPath)) {
            $fs = new Filesystem();
            $fs->mkdir($reportsPath, 0755);
        }

        return rtrim($reportsPath);
    }

    /**
     * @param Event $event
     *
     * @return string
     */
    protected static function getTestResultsPath(Event $event)
    {
        $reportsPath = self::getOption('test-results-path', $event);

        if (!is_dir($reportsPath)) {
            $fs = new Filesystem();
            $fs->mkdir($reportsPath, 0755);
        }

        return rtrim($reportsPath);
    }
}
