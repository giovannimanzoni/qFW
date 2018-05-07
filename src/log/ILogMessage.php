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
     * @param string $type
     * @param string $text
     * @param string $vocFun
     */
    public function __construct(string $type, string $text, string $vocFun);

    /**
     * Return log type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Return log text
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Return storing date and time
     *
     * @return string
     */
    public function getDate(): string;

    /**
     * Return vocabulary function name.
     *
     * @return string
     */
    public function getVocFun(): string;
}
