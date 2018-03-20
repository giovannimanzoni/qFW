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
 * Class LogMessage
 *
 * @package qFW\log
 */
class LogMessage implements ILogMessage
{
    /** @var string type of log, user defined */
    private $type = '';

    /** @var string text to log */
    private $text = '';

    /** @var string hold date and time for the log*/
    private $date = '';

    /**
     * LogMessage constructor.
     *
     * @param string $type type of log, user defined
     * @param string $text text to log
     */
    public function __construct(string $type, string $text)
    {
        $this->type = $type;
        $this->text = $text;
        $this->date = gmdate('Y-m-d H:i:s');
    }

    /**
     * Return type of log
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Return text of the log
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Return date and time of the log
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }
}
