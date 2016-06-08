<?php

namespace Jhg\ComposerCiTools\Inflector;

/**
 * Class Inflector.
 */
class Inflector
{
    /**
     * @param string $camelCase
     *
     * @return string
     */
    public static function camelCaseToDaseCase($camelCase)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $camelCase));
    }
}
