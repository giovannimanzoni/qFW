<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\log\Html;

use qFW\log\ALogger;
use qFW\log\ILogMessage;
use qFW\log\ILogger;

/**
 * Class Html
 *
 * Manage logs for output them in web page
 *
 * @package qFW\log\Html
 */
class Html extends ALogger implements ILogger
{
    /**
     * Return the html code for see all logs in the page
     *
     * @return string
     */
    public function getLogs(): string
    {
        $html = '';

        if (array_key_exists('logs', $this->logArray)) {
            $html .= '<ul>';

            foreach ($this->logArray['logs'] as $line) {
                $html .= $this->formatLogRow($line);
            }

            $html .= '</ul>';
        } else {
            $html = 'No errors to show.';
        }

        return $html;
    }

    /**
     * Format each row in log report.
     *
     * Html code in a list of elements
     *
     * @param \qFW\log\ILogMessage $log
     *
     * @return string
     */
    private function formatLogRow(ILogMessage $log)
    {
        return "<li>{$log->getDate()} : {$log->getType()} | {$log->getText()}</li>";
    }
}
