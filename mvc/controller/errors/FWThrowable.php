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
 * Class Errors
 * -> http://php.net/manual/en/function.set-exception-handler.php#82512
 *
 *
 * @package qFW\mvc\controller\errors
 */
final class FWThrowable extends \Exception implements IFWThrowable
{

    private static $logSql=true;

    /**
     * @param \Throwable $e
     *
     * @return mixed|void
     */
    public static function handleException(\Throwable $e)
    {
        self::printException($e);

        if(self::$logSql) {
            // @todo completare
            //Sql::log();
        }
    }


    /**
     * Print elegant message in bootstrap alert box
     *
     * @param \Throwable $exception
     */
    private static function printException(\Throwable $exception)
    {
        echo '<div class="alert alert-danger">';
        echo '<b>Fatal error</b>:  Uncaught exception \'' . get_class($exception) . "<br>";
        echo 'with message: '.htmlentities($exception->getMessage()) . ', code: ' . $exception->getCode().'<br>';
        echo 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>';
        echo 'thrown in <b>' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b><br>';
        echo '</div>';

    }


}
