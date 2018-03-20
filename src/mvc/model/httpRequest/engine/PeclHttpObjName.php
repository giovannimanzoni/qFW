<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\httpRequest\engine;

/**
 * Class PeclHttpObjName
 *
 * For make http request with pecl-http
 *
 * @package qFW\mvc\model\httpRequest\engine
 */
class PeclHttpObjName implements IEngineName
{
    /**
     * PeclHttpObjName constructor.
     */
    public function __construct()
    {
    }

    /**
     * Return path of class to call
     *
     * @return string
     */
    public function getName(): string
    {
        $path = str_replace(__NAMESPACE__ . '\\', '', get_class($this));
        $name = str_replace('Name', '', $path);

        return __NAMESPACE__ . "\\$name\\$name";
    }
}
