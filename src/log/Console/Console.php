<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\log\Console;

use qFW\log\ALogger;
use qFW\log\ILogger;
use qFW\log\ILogMessage;

/**
 * Class Console
 *
 * Manage logs for output them in web console
 *
 * @package qFW\log\Console
 */
class Console extends ALogger implements ILogger
{
    /**
     * Return the html code for see all logs in web console
     *
     * @return string           html code
     */
    public function getLogs(): string
    {
        $html = '';

        if (array_key_exists('logs', $_SESSION['ALOGGER'])) {
            $html .= '<script type="text/javascript">';

            foreach ($_SESSION['ALOGGER']['logs'] as $line) {
                $html .= $this->formatLogRow($line);
            }

            $html .= '</script>';
            unset($_SESSION['ALOGGER']);
        } else {
            $html = '<script type="text/javascript">console.log("No errors to show.");</script>';
        }

        return $html;
    }

    /**
     * Format each row in log report
     *
     * @param \qFW\log\ILogMessage $log
     *
     * @return string
     */
    private function formatLogRow(ILogMessage $log)
    {
        return "console.log('{$log->getDate()} : {$log->getType()} | {$log->getText()}');";
    }
}
