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
 * Class ALogger
 *
 * Common function for force specific code for handling variables in logging classes
 *
 * @package qFW\log
 */
abstract class ALogger implements ILogger
{
    /** @var array hold all logs */
    protected $logArray;

    /**
     * ALogger constructor.
     *
     * @param string|int $uid  user id who generate logs events
     */
    public function __construct($uid)
    {
        $this->logArray['uid'] = $uid;
    }

    /**
     * Store new log in memory
     *
     * @param \qFW\log\ILogMessage $log log message
     */
    public function log(ILogMessage $log)
    {
        $this->logArray['logs'][] = $log;
    }

    /**
     * Get how may logs are stored
     *
     * @return int number of stored logs
     */
    public function getLogsQty(): int
    {
        if (array_key_exists('logs', $this->logArray)) {
            $qty = count($this->logArray['logs']);
        } else {
            $qty = 0;
        }
        return $qty;
    }

    /**
     * Return stored logs. This method must be implemented
     *
     * @return string
     */
    abstract public function getLogs(): string;
}
