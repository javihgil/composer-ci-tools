<?php

namespace Jhg\ComposerCiTools;

use Composer\Script\Event;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Write.
 */
class Write extends AbstractScriptHandler
{
    /**
     * @return string
     */
    protected static function getName()
    {
        return 'write';
    }

    /**
     * @param OptionsResolver $resolver
     * @param Event           $event
     */
    protected static function configureOptions(OptionsResolver $resolver, Event $event)
    {
        $resolver->setDefaults(array(
            'write-block-start-format' => '<fg=magenta>Executing %s...</>',
            'write-block-end-format' => '<fg=magenta>%s Done!</>',
        ));
    }

    /**
     * @param Event $event
     */
    public static function blockStart(Event $event)
    {
        $event->getIO()->write(sprintf(self::getOption('write-block-start-format', $event), $event->getName()));
    }

    /**
     * @param Event $event
     */
    public static function blockEnd(Event $event)
    {
        $event->getIO()->write(sprintf(self::getOption('write-block-end-format', $event), $event->getName()));
    }
}
