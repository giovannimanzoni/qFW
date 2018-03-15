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
 * Class CurlObjName
 *
 * For make http request with curl
 *
 * @package qFW\mvc\model\httpRequest\engine
 */
class CurlObjName implements IEngineName
{
    /**
     * CurlObjName constructor.
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
        $namespace=__NAMESPACE__;
        $path = str_replace("$namespace\\", '', get_class($this));
        $name = str_replace('Name', '', $path);

        return "$namespace\\$name\\$name";
    }
}
