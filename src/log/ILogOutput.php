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
 * Interface ILogOutput
 *
 * Methods for logging engine
 *
 * @package qFW\log
 */
interface ILogOutput
{
    /**
     * ILogOutput constructor.
     *
     * @param                                $uid
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct($uid, ILang $lang);

    /**
     * Get path for call right function by class name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get user id
     *
     * @return string|int
     */
    public function getUid();

    /**
     * Get Lang
     *
     * @return mixed
     */
    public function getLang(): ILang;
}
