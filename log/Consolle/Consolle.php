<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\log\Consolle;

use qFW\log\ALogger;
use qFW\log\ILogger;
use qFW\log\ILogMessage;

/**
 * Class Consolle
 *
 * Manage logs for output them in web consolle
 *
 * @package qFW\log\Consolle
 */
class Consolle extends ALogger implements ILogger
{
    /**
     * Return the html code for see all logs in web consolle
     *
     * @return string           html code
     */
    public function getLogs(): string
    {
        $html = '';

        if (array_key_exists('logs', $this->logArray)) {
            $html .= '<script type="text/javascript">';

            foreach ($this->logArray['logs'] as $line) {
                $html .= $this->formatLogRow($line);
            }

            $html .= '</script>';
        } else {
            $html = 'console.log(No errors to show.);';
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
        return "console.log({$log->getDate()} : {$log->getType()} | {$log->getText()});";
    }
}
