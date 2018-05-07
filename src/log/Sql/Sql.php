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
use qFW\mvc\controller\lang\ILang;

/**
 * Class Sql
 *
 * Manage logs for store on sql database
 *
 * @package qFW\log\Sql
 */
class Sql extends ALogger implements ILogger
{
    /** @var string Table to log on */
    private $table = '';

    /** @var int User id */
    private $userId = 0;

    /** @var string Url that generate the log*/
    private $url = '';

    /** @var string Client IP that generate the log*/
    private $clientIp='';

    /**
     * Sql constructor.
     *
     * @param        $uid
     * @param string $lang
     */
    public function __construct($uid, string $lang)
    {
        parent::__construct($uid, $lang);

        $this->userId = $uid;                       // Save logged user that could generate the error
        $this->clientIp = $_SERVER['REMOTE_ADDR'];  // Save users's IP
    }

    /**
     * Setup parameters for sql connection
     *
     * @param string $table
     * @param string $path
     */
    public function init(string $table, string $path)
    {
        $this->table = $table;                    // Table in witch the logg will be saved
        $this->url = $path;                       // Url that generates the error

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

        foreach ($_SESSION['ALOGGER'] as $line) {
            $html .= "<li>{$line->date} : {$line->type} | {$line->text}</li>";
        }

        $html .= '</ul>';
        unset($_SESSION['ALOGGER']);
        return $html;
    }

    /**
     * Log message in SQL table.
     *
     * Override method in abstract class ALogger
     *
     * @fixme: not work
     *
     * @see ALogger::log()
     * @param \qFW\log\ILogMessage $log
     *
     * @return mixed|void
     */
    public function log(ILogMessage $log)
    {
        parent::log($log);

        // @todo Connect to SQL

        // save in sql
        // @todo:  Add all constants and variables, stack call
        // @fixme: Not work

        // $_GET, $_POST, $_COOKIE, $_REQUEST, $_ENV, $_SERVER, $_SESSION
        sqlAdd(
            $this->table,
            $_SESSION['ALOGGER']['uid'],
            $log->getType(),
            $log->getText(),
            $this->url,
            $this->userId,
            $log->getDate()
        );
    }
}
