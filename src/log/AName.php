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

use qFW\mvc\controller\lang\ILang;

/**
 * Class AName
 *
 * Common function for force specific code for handling logging
 *
 * @package qFW\log
 */
abstract class AName implements ILogOutput
{
    /** @var string|int User id who generate the log */
    private $uid;

    /** @var \qFW\mvc\controller\lang\ILang  */
    protected $lang;

    /**
     * AName constructor.
     *
     * @param                                $uid
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct($uid, ILang $lang)
    {
        $this->uid = $uid;
        $this->lang = $lang;
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
     * @param string $namespace Use __NAMESPACE__ for this parameter
     * @param        $class     Object use $this for this parameter
     *
     * @return string
     */
    protected function getClassName(string $namespace, $class): string
    {
        $path = str_replace($namespace . '\\', '', get_class($class));
        $name = str_replace('Name', '', $path);

        return "$namespace\\$name\\$name";
    }

    /**
     * Return lang
     *
     * @return \qFW\mvc\controller\lang\ILang
     */
    public function getLang(): ILang
    {
        return $this->lang;
    }
}
