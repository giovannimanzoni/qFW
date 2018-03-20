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
     * Gestione errori
     ********************************************/
    /************************************************
     * Log
     ************************************************/


    public function createLogger(ILogOutput $outputLog)
    {
        $this->loggerEngine = new LogProxy($outputLog);
    }

    public function getLogs(): string
    {
        return $this->loggerEngine->getLogs();
    }

    public function getLogsQty(): int
    {
        return $this->loggerEngine->getLogsQty();
    }

    private function addLog(string $err)
    {
        $this->loggerEngine->log(new LogMessage('', $err));
    }

    private function getCheckEsito(): bool
    {
        $esito = true;

        // se ha errori allora riporta false
        if ($this->getLogsQty() != 0) {
            $esito = false;
        }

        // gestione controllo errori
        $this->TErrorChecked = true;
        $this->TErrorValid = $esito;

        return $esito;
    }
}
