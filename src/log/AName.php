<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\log;

/**
 * Class AName
 *
 * Common function for force specific code for handling logging
 *
 * @package qFW\log
 */
abstract class AName implements ILogOutput
{
    /** @var string|int user id who generate the log */
    private $uid;

    /**
     * AName constructor.
     *
     * @param $uid string|int user id
     */
    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Get user id
     *
     * @return int|string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Get class name to pass to __call
     *
     * @param string $namespace use __NAMESPACE__ for this parameter
     * @param        $class     object use $this for this parameter
     *
     * @return string
     */
    protected function getClassName(string $namespace, $class): string
    {
        $path = str_replace($namespace . '\\', '', get_class($class));
        $name = str_replace('Name', '', $path);

        return "$namespace\\$name\\$name";
    }
}
