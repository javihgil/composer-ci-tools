<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Jhg\ComposerCiTools\Exception\GulpBadTaskConfigurationException;
use Jhg\ComposerCiTools\Exception\GulpUndefinedTaskException;
use Jhg\ComposerCiTools\Inflector\Inflector;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class Gulp.
 */
class Gulp extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'gulp';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $resolver->setDefaults(array(
            'gulp-bin' => './node_modules/.bin/gulp',
            'gulp-log-prepend' => '',
            'gulp-tasks' => [],
        ));

        $resolver->isRequired('gulp-bin');
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @throws GulpBadTaskConfigurationException
     * @throws GulpUndefinedTaskException
     */
    public static function __callStatic($name, $arguments)
    {
        $taskName = Inflector::camelCaseToDaseCase($name);

        /** @var Event $event */
        list($event) = $arguments;

        $gulpTasks = self::getOption('gulp-tasks', $event);

        if (!isset($gulpTasks[$taskName])) {
            throw new GulpUndefinedTaskException(sprintf('Gulp %s task is not defined.', $taskName));
        }

        // get gulp options
        $gulpBin = self::getOption('gulp-bin', $event);
        $logPrepend = self::getOption('gulp-log-prepend', $event);

        // get task options
        $taskOptions = $gulpTasks[$taskName];
        if (empty($taskOptions['command'])) {
            throw new GulpBadTaskConfigurationException(sprintf('Gulp %s task command option must be defined.', $taskName));
        }

        $command = [
            $gulpBin,
            $taskOptions['command'],
            isset($taskOptions['params']) ? $taskOptions['params'] : '',
        ];

        $failOnError = empty($taskOptions['fail-on-error']) ? false : $taskOptions['fail-on-error'];

        if (!$failOnError) {
            $command[] = '|| true';
        }

        // execute process
        $process = new Process(implode(' ', $command));

        $process->mustRun(function ($type, $buffer) use ($event, $logPrepend) {
            self::writeProcessBuffer($type, $buffer, $event, $logPrepend);
        });
    }
}
