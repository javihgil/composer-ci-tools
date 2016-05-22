<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Jhg\ComposerCiTools\Exception\GitMissingCommitMsgFileException;
use Jhg\ComposerCiTools\Exception\GitMissingCommitMsgRegexException;
use Jhg\ComposerCiTools\Exception\GitNotMatchingCommitMsgException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

/**
 * Class Git.
 */
class Git extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'git';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $resolver->setDefaults(array(
            'git-log-prepend' => '',
            'git-commit-msg-regex' => null,
            'git-commit-msg-error' => null,
        ));
    }

    /**
     * @param Event $event
     *
     * @throws GitMissingCommitMsgFileException
     * @throws GitMissingCommitMsgRegexException
     * @throws GitNotMatchingCommitMsgException
     */
    public static function commitMsgRegex(Event $event)
    {
        $regex = self::getOption('git-commit-msg-regex', $event);
        $logPrepend = self::getOption('git-log-prepend', $event);

        if (!$regex) {
            throw new GitMissingCommitMsgRegexException();
        }

        $finder = new Finder();
        $finder->in('.git')->name('COMMIT_EDITMSG');
        $iterator = $finder->files()->getIterator();

        if (!iterator_count($iterator)) {
            throw new GitMissingCommitMsgFileException();
        }

        /** @var SplFileInfo $file */
        $file = current(iterator_to_array($iterator));

        if (empty($file)) {
            throw new GitMissingCommitMsgFileException();
        }

        // validate commit message
        if (!preg_match($regex, $file->getContents())) {
            $error = self::getOption('git-commit-msg-error', $event);

            if (!$error) {
                // default error
                $error = sprintf('Commit message does not match against "%s"', $regex);
            }

            throw new GitNotMatchingCommitMsgException($error);
        }

        self::log($logPrepend.'Commit message is valid', $event);
    }

    /**
     * @param Event $event
     */
    public static function addAll(Event $event)
    {
        $logPrepend = self::getOption('git-log-prepend', $event);

        // execute process
        $process = new Process('git add . -A');

        $process->mustRun(function ($type, $buffer) use ($event, $logPrepend) {
            self::writeProcessBuffer($type, $buffer, $event, $logPrepend);
        });
    }
}
