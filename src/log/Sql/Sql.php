<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\log\Sql;

use qFW\log\ALogger;
use qFW\log\ILogMessage;
use qFW\log\ILogger;

/**
 * Class Sql
 *
 * Manage logs for store on sql database
 *
 * @package qFW\log\Sql
 */
class Sql extends ALogger implements ILogger
{
    /** @var string table to log on */
    private $table = '';

    /** @var int user id */
    private $userId = 0;

    /** @var string url that generate the log*/
    private $url = '';

    /** @var string client IP that generate the log*/
    private $clientIp='';

    /**
     * Sql constructor.
     *
     * Override method in abstract class ALogger
     *
     * @param $uid  : user id who make logs
     */
    public function __construct($uid)
    {
        parent::__construct($uid);

        $this->userId = $uid;                     // user loggato al sito che fa generare l'errore
        $this->clientIp = $_SERVER['REMOTE_ADDR'];  // ip dell'utente che ha generato l'errore
    }

    /**
     * Setup parameters for sql connection
     *
     * @param string $table
     * @param string $path
     */
    public function init(string $table, string $path)
    {
        $this->table = $table;                    // table su cui loggare
        $this->url = $path;                      // url che genera l'errore

        // @todo init SQL ?
    }

    /**
     * Get logs in html list
     *
     * @return string
     */
    public function getLogs(): string
    {
        $html = '<ul>';

        foreach ($this->logArray as $line) {
            $html .= "<li>{$line->date} : {$line->type} | {$line->text}</li>";
        }

        $html .= '</ul>';
        return $html;
    }

    /**
     * Log message in SQL table.
     *
     * Override method in abstract class ALogger
     *
     * @see ALogger::log()
     * @param \qFW\log\ILogMessage $log
     *
     * @return mixed|void
     */
    public function log(ILogMessage $log)
    {
        parent::log($log);

        // @todo connect to SQL

        // save in sql
        // @todo:  add all constants and variables, stack call
        // @fixme: not work

        // $_GET, $_POST, $_COOKIE, $_REQUEST, $_ENV, $_SERVER, $_SESSION
        sqlAdd(
            $this->table,
            $this->logArray['uid'],
            $log->getType(),
            $log->getText(),
            $this->url,
            $this->userId,
            $log->getDate()
        );
    }
}
