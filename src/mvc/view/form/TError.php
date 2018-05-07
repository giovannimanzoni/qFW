<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\form;

use qFW\log\ILogOutput;
use qFW\log\LogMessage;
use qFW\log\LogProxy;
use qFW\mvc\controller\lang\ILang;

/**
 * Trait TError
 *
 * Manage errors for html elements of this form
 *
 * @package qFW\mvc\view\form
 */
trait TError
{
    private $loggerEngine;
    private $TErrorChecked = false;
    private $TErrorValid = false;

    /*********************************************
     * Error management
     ********************************************/
    /************************************************
     * Log
     ************************************************/

    /**
     * Creat logger for every form field
     *
     * @todo $uid is unused
     *
     * @param \qFW\log\ILogOutput $logger
     */
    public function createLogger(ILogOutput $logger)
    {
        $this->loggerEngine = new LogProxy($logger);
    }

    /**
     * @return string
     */
    public function getLogs(): string
    {
        return $this->loggerEngine->getLogs();
    }

    /**
     * @return int
     */
    public function getLogsQty(): int
    {
        return $this->loggerEngine->getLogsQty();
    }


    /**
     * @param string $err
     * @param string $vocFun Name of function that must exist on Vocabulary
     */
    private function addLog(string $err, string $vocFun = '')
    {
        $this->loggerEngine->log(new LogMessage('', $err, $vocFun));
    }

    /**
     * @return bool
     */
    private function getCheckOutcome(): bool
    {
        $outcome = true;

        // If there are errors, then it returns false
        if ($this->getLogsQty() != 0) {
            $outcome = false;
        }

        // Error control management
        $this->TErrorChecked = true;
        $this->TErrorValid = $outcome;

        return $outcome;
    }
}
