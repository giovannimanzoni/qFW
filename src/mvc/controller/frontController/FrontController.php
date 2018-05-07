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
use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\routing\Routing;

/**
 * Class FrontController
 *
 * @package qFW\mvc\controller\frontController
 */
class FrontController
{
    /** @var  ILang language*/
    private $lang;

    /** @var string  */
    private $folder='';

    /** @var bool  */
    private $isAdmin=true;

    /** @var string  */
    protected $scriptName='';

    /**
     * FrontController constructor.
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     * @param bool                           $showError
     */
    public function __construct(ILang $lang, bool $showError = false)
    {
        $this->lang = $lang;
        $this->isAdmin = true;

        set_exception_handler(array(FWThrowable::class, 'handleException'));
        //Set_error_handler(array(__CLASS__, '_errorHandler')); // do we need? or included in the one above in PHP7?

        $this->setInit($showError);

        $this->setTimeZone();

        // @todo init database
    }

    /**
     * Use this function if website is not in document root
     *
     * @param string $folder
     *
     * @return $this
     */
    public function setDomainFolder(string $folder)
    {
        $this->folder = $folder;
        return $this;
    }

    /**
     * @param bool $isAdmin
     *
     * @return $this
     */
    public function isAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * Run front controller
     */
    public function run()
    {
        $routing=new Routing($this->isAdmin, $this->folder);
        $this->scriptName=$routing->getScriptName();

        return $this;
    }

    /**
     * Set different timezone instead of default
     *
     * @param string $timezone
     *
     * @return $this
     */
    public function setTimeZone(string $timezone = 'Europe/Rome')
    {
        date_default_timezone_set($timezone);
        return $this;
    }

    /**
     * Return Language
     *
     * @return \qFW\mvc\controller\lang\ILang
     */
    public function getLang(): ILang
    {
        return $this->lang;
    }


    /**
     * @param bool $showError
     */
    private function setInit(bool $showError)
    {
        if ($showError) {
            $opt = '1';
        } else {
            $opt = '0';
        }
        $this->initIni($opt);
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
