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
 * Interface ILogger
 *
 * Define uniform methods for class that want log in some specific way
 *
 * @package qFW\log
 */
interface ILogger
{
    /**
     * ILogger constructor.
     *
     * @param        $uid
     * @param string $lang
     */
    public function __construct($uid, string $lang);

    /**
     * Return the html code for see all logs
     *
     * @return string
     */
    public function getLogs(): string;

    /**
     * Get log quantity
     *
     * @return int
     */
    public function getLogsQty(): int;

    /**
     * Store a log message
     *
     * @param \qFW\log\ILogMessage $log
     *
     * @return mixed
     */
    public function log(ILogMessage $log);

    /**
     * Setup lang for create vocabulary
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     *
     * @return mixed
     */
    public function setLang(ILang $lang);
}
