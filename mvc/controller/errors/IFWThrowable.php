<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\controller\errors;

/**
 * Interface IThrowable
 *
 * @package qFW\mvc\controller\errors
 */
interface IFWThrowable extends \Throwable
{
    /**
     * @param \Throwable $exception
     *
     * @return mixed
     */
    public static function handleException(\Throwable $exception);
}