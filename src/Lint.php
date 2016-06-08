<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Composer\Util\Platform;
use Jhg\ComposerCiTools\Exception\LintErrorException;
use Jhg\ComposerCiTools\Exception\RequiresDependencyException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class Lint.
 */
class Lint extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'lint';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $binDir = $event->getComposer()->getConfig()->get('bin-dir');

        $resolver->setDefaults(array(
            // Lint PHP configuration
            'lint-php-include' => array('.'),
            'lint-php-exclude' => array('./vendor/*', './var/*', './bin/*'),
            'lint-php-log-prepend' => '',

            // Lint Twig configuration
            'lint-twig-bin' => $binDir.'/twig-lint',
            'lint-twig-include' => array('.'),
            'lint-twig-exclude' => array('./vendor/*', './var/*', './bin/*'),
            'lint-twig-log-prepend' => '',

            // Lint Yaml configuration
            'lint-yaml-bin' => $binDir.'/yaml-lint',
            'lint-yaml-include' => array('.'),
            'lint-yaml-exclude' => array('./vendor/*', './var/*', './bin/*'),
            'lint-yaml-log-prepend' => '',
        ));
    }

    /**
     * @param Event $event
     * @param bool  $lazy
     *
     * @throws LintErrorException
     * @throws RequiresDependencyException
     */
    public static function php(Event $event, $lazy = false)
    {
        $includes = self::getOption('lint-php-include', $event);
        $excludes = self::getOption('lint-php-exclude', $event);
        $logPrepend = self::getOption('lint-php-log-prepend', $event);

        if (Platform::isWindows()) {
            self::lintFinder($event, $includes, $excludes, '*.php', 'php -l', $logPrepend, $lazy);
        } else {
            self::lintLinuxFind($event, $includes, $excludes, '*.php', 'php -l', $logPrepend, $lazy);
        }
    }

    /**
     * @param Event $event
     */
    public static function phpLazy(Event $event)
    {
        self::php($event, true);
    }

    /**
     * @param Event $event
     * @param bool  $lazy
     *
     * @throws LintErrorException
     * @throws RequiresDependencyException
     */
    public static function twig(Event $event, $lazy = false)
    {
        $bin = self::getOption('lint-twig-bin', $event);
        $includes = self::getOption('lint-twig-include', $event);
        $excludes = self::getOption('lint-twig-exclude', $event);
        $logPrepend = self::getOption('lint-twig-log-prepend', $event);

        if (Platform::isWindows()) {
            self::lintFinder($event, $includes, $excludes, '*.twig', $bin, $logPrepend, $lazy);
        } else {
            self::lintLinuxFind($event, $includes, $excludes, '*.twig', $bin, $logPrepend, $lazy);
        }
    }

    /**
     * @param Event $event
     */
    public static function twigLazy(Event $event)
    {
        self::twig($event, true);
    }

    /**
     * @param Event $event
     * @param bool  $lazy
     *
     * @throws LintErrorException
     * @throws RequiresDependencyException
     */
    public static function yaml(Event $event, $lazy = false)
    {
        $bin = self::getOption('lint-yaml-bin', $event);
        $includes = self::getOption('lint-yaml-include', $event);
        $excludes = self::getOption('lint-yaml-exclude', $event);
        $logPrepend = self::getOption('lint-yaml-log-prepend', $event);

        if (Platform::isWindows()) {
            self::lintFinder($event, $includes, $excludes, '*.yml', $bin, $logPrepend, $lazy);
        } else {
            self::lintLinuxFind($event, $includes, $excludes, '*.yml', $bin, $logPrepend, $lazy);
        }
    }

    /**
     * @param Event $event
     */
    public static function yamlLazy(Event $event)
    {
        self::yaml($event, true);
    }

    /**
     * @param Event  $event
     * @param array  $includes
     * @param array  $excludes
     * @param string $extension
     * @param string $command
     * @param string $bufferPrepend
     * @param bool   $lazy
     *
     * @throws LintErrorException
     * @throws RequiresDependencyException
     */
    protected static function lintFinder(Event $event, array $includes, array $excludes, $extension, $command, $bufferPrepend = '', $lazy = false)
    {
        if (!class_exists('Symfony\Component\Finder\Finder')) {
            throw new RequiresDependencyException('This job requires install of symfony/finder component. $ composer require-dev symfony/finder');
        }

        if ($lazy) {
            self::warning('Lazy lint is not supported for windows platforms', $event);
        }

        $finder = new Finder();
        $finder->files()->name($extension)->in($includes)->exclude($excludes);

        $status = false;

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $process = new Process($command.' '.$file->getRelativePathname());

            $status &= $process->run(
                function ($type, $buffer) use ($event, $bufferPrepend) {
                    self::writeProcessBuffer($type, $bufferPrepend.$buffer, $event);
                }
            );
        }

        if ($status) {
            throw new LintErrorException('Some files does not lint');
        }
    }

    /**
     * @param Event  $event
     * @param array  $includes
     * @param array  $excludes
     * @param string $extension
     * @param string $command
     * @param string $bufferPrepend
     * @param bool   $lazy
     */
    protected static function lintLinuxFind(Event $event, array $includes, array $excludes, $extension, $command, $bufferPrepend = '', $lazy = false)
    {
        $excludeParams = array_map(function ($exclude) {
            return '-not -path \''.$exclude.'\'';
        }, $excludes);

        foreach ($includes as $include) {
            if ($lazy && is_dir('.git')) {
                // last commit = git diff-tree --no-commit-id --name-only --diff-filter=ACMRTUXB -r HEAD
                $processCount = new Process('git diff --name-only HEAD | grep '.trim($extension, '*').' --color=never | wc -l');
                $process = new Process('git diff --name-only HEAD | grep '.trim($extension, '*').' --color=never | xargs --no-run-if-empty -n1 -P8 '.$command);
            } else {
                if ($lazy) {
                    self::warning('Lazy lints are only for git repositories', $event);
                }

                $processCount = new Process('find '.$include.' -name \''.$extension.'\' '.implode(' ', $excludeParams).' | wc -l');
                $process = new Process('find '.$include.' -name \''.$extension.'\' '.implode(' ', $excludeParams).' -print0 | xargs --no-run-if-empty -0 -n1 -P8 '.$command);
            }

            self::writeDebug('Running command: '.$process->getCommandLine(), $event);

            $processCount->run();
            $changedFiles = (int) $processCount->getOutput();

            if (!$changedFiles) {
                self::warning('No files to lint', $event);

                return;
            }

            $process->mustRun(function ($type, $buffer) use ($event, $bufferPrepend) {
                self::writeProcessBuffer($type, $bufferPrepend.$buffer, $event);
            });
        }
    }
}
