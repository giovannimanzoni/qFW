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
 * Class ALogger
 *
 * Common function for force specific code for handling variables in logging classes
 *
 * @package qFW\log
 */
abstract class ALogger implements ILogger
{

    /** @var  vocabulary */
    protected $voc;

    /** @var string */
    protected $lang = '';

    /**
     * ALogger constructor.
     *
     * @param        $uid
     * @param string $lang
     */
    public function __construct($uid, string $lang)
    {
        $_SESSION['ALOGGER']['uid'] = $uid;
        $this->lang = $lang;
    }

    /**
     * Store new log in memory
     *
     * @param \qFW\log\ILogMessage $log Log message
     */
    public function log(ILogMessage $log)
    {
        $_SESSION['ALOGGER']['logs'][] = $log;
    }

    /**
     * Get how may logs are stored
     *
     * @return int Number of stored logs
     */
    public function getLogsQty(): int
    {
        $qty = 0;
        if ($_SESSION['ALOGGER']) {
            if (array_key_exists('logs', $_SESSION['ALOGGER'])) {
                $qty = count($_SESSION['ALOGGER']['logs']);
            } else {
                /*on non esiste chiave*/
            }
        } else {
            /*ok non ci sono messaggi di log*/
        }
        return $qty;
    }

    /**
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function setLang(ILang $lang)
    {
        $this->lang = $lang;
    }


    /**
     * Set lang in a separate method for not affect too many others method and avoid write in every form element the
     *      lang of log messages
     *
     * Return stored logs. This method must be implemented
     *
     *
     * @return string
     */
    abstract public function getLogs(): string;
}
