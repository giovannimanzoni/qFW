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
 * Interface ILogMessage
 *
 * For use logger classes as container and array with specific indexes values
 *
 * @package qFW\log
 */
interface ILogMessage
{
    /**
     * ILogMessage constructor.
     *
     * @param string $type type of log, user defined
     * @param string $text text to log
     */
    public function __construct(string $type, string $text);

    /**
     * return log type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * return log text
     *
     * @return string
     */
    public function getText(): string;

    /**
     * return storing date and time
     *
     * @return string
     */
    public function getDate(): string;
}
