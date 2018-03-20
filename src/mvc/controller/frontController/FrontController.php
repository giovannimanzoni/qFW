<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\controller\frontController;

use qFW\mvc\controller\errors\FWThrowable;
use qFW\mvc\controller\routing\Routing;

/**
 * Class FrontController
 *
 * @package qFW\mvc\controller\frontController
 */
class FrontController
{
    /**
     * FrontController constructor.
     *
     * @param bool $showError
     */
    public function __construct(bool $showError = true)
    {
        set_exception_handler(array(FWThrowable::class, 'handleException'));
        //set_error_handler(array(__CLASS__, '_errorHandler')); // serve ? o incluso in quella sopra in PHP7 ?


        if ($showError) {
            $opt = '1';
        } else {
            $opt = '0';
        }

        $this->initIni($opt);

        $this->setTimeZone();

        // @todo init database
    }

    /**
     * Run front controller
     */
    public function run()
    {
        new Routing();
    }

    /**
     * Set different timezone instead of default
     *
     * @param string $timezone
     */
    public function setTimeZone(string $timezone = 'Europe/Rome')
    {
        date_default_timezone_set($timezone);
    }

    /**
     * @param string $showError
     */
    private function initIni(string $showError)
    {
        ini_set('display_errors', $showError);
        ini_set('display_startup_errors', $showError);
    }
}
