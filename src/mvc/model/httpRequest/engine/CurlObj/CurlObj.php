<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\httpRequest\engine\CurlObj;

use qFW\mvc\model\httpRequest\engine\IEngineObj;
use qFW\mvc\model\httpRequest\engine\IEngineObjBuilder;

/**
 * Class CurlObj
 *
 *
 * @package qFW\mvc\model\httpRequest\engine\CurlObj
 */
class CurlObj implements IEngineObj, \SplObserver
{
    /** @var resource curl resource */
    private $curl;

    /** @var \qFW\mvc\model\httpRequest\engine\IEngineObjBuilder  object builder */
    private $objBuilder;

    /** @var bool hold if check is performed */
    private $checked = false;

    /** @var bool  hold if curl check is valid */
    private $valid = false;

    // For addError(), so you can keep track, if you run check () several times when the error was generated.
    /** @var int  index for addError */
    private $checkTime = 1;

    /** @var array hold list for all logs */
    private $logHistory = array();

    /** @var string return html */
    private $html = '';

    /** @var array */
    private $cookies = array();

    /** @var string */
    private $headersSent = '';


    /** @var string */
    private $headersGet = array();

    /***********************************
     * OBSERVE
     ***********************************/

    /**
     * Update curl with new options
     *
     * @param \SplSubject $subject
     */
    public function update(\SplSubject $subject)
    {
        $this->objBuilder = $subject;
        $this->initVars();
    }

    /***********************************
     * BUILDER
     ***********************************/

    /**
     * CurlObj constructor.
     *
     * @param \qFW\mvc\model\httpRequest\engine\IEngineObjBuilder $objBuilder
     */
    public function __construct(IEngineObjBuilder $objBuilder)
    {
        $this->headersGet = array();

        //session_write_close(); ? // https://stackoverflow.com/questions/2424714/how-to-maintain-session-in-curl-in-php

        $this->curl = curl_init();

        $this->objBuilder = $objBuilder;
        $this->initVars();
        $this->logHistory[$this->checkTime] = array();
    }

    /**
     * Get curl result or error response
     *
     * @return string
     */
    public function getResult(): string
    {
        // It can serve to show the page several times. If the curl options have not changed, it makes no sense to
        //      repeat the check every time
        if (!$this->checked) {
            $this->check();
        } else {
            /*Ok*/
        }

        if ($this->valid) {
            $this->curl($this->objBuilder);
            $html = $this->html;
        } else {
            $html = 'curl builder errors.';
        }

        return $html;
    }

    /**
     *  Var dump of curl_getinfo()
     */
    public function getRequestInfo()
    {
        return curl_getinfo($this->curl);
    }

    /**
     * Get logs
     *
     * @return string
     */
    public function getLogs(): string
    {
        return $this->objBuilder->getLogs();
    }

    /**
     * Check if curl setup is wrong or not
     */
    protected function check()
    {
        $this->valid = false;

        // If there are errors
        if ($this->objBuilder->getLogsQty()) {
            $this->logHistory[$this->checkTime][] = $this->objBuilder->getLogs();
        } else {
            $this->valid = true;
        }

        $this->checkTime++; // Groups any error messages that could be generated
        $this->checked = true;
    }

    /**
     * Exec curl request
     *
     * @param \qFW\mvc\model\httpRequest\engine\IEngineObjBuilder $objBuilder
     *
     */
    public function curl(IEngineObjBuilder $objBuilder)
    {
        foreach ($objBuilder->getOptions() as $option => $value) {
            curl_setopt($this->curl, $option, $value);
        }

        //@todo make it parametric from the builder ?

        // https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request/17971689
        $ch = $this->curl;
        curl_setopt(
            $ch,
            CURLOPT_HEADERFUNCTION,
            //use (&$headers)
            function ($ch, $header) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) {
                    // ignore invalid headers
                    return $len;
                } else {
                    /*Ok*/
                }

                $name = strtolower(trim($header[0]));
                if (!array_key_exists($name, $this->headersGet)) {
                    $this->headersGet[$name] = [trim($header[1])];
                } else {
                    $this->headersGet[$name][] = trim($header[1]);
                }

                return $len;
            }
        );


        $htmlCurl = curl_exec($this->curl);
        $this->extractHeadersSent();

        if (curl_error($this->curl)) {
            $this->html = '<p>Errors from curl call: <br>' . curl_error($this->curl) . '</p>';
        } elseif (is_string($htmlCurl)) {
            $this->html = trim($htmlCurl);
        } else {
            $this->html = '';
        }

        $this->closeCurl();
    }

    /**
     *
     * Must be public
     *
     * @param $ch
     * @param $headerLine
     *
     * @return int
     */
    public function curlResponseHeaderCallback($ch, $headerLine)
    {
        if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1) {
            $this->cookies[] = $cookie;
        } else {
            /*Ok*/
        }

        return strlen($headerLine); // Needed by curl
    }

    /**
     *
     */
    public function dumpHeadersGet()
    {
        return $this->headersGet;
    }

    public function dumpHeadersSent()
    {
        return print_r($this->headersSent, true);
    }

    /**
     * @return array
     */
    public function dumpCookie(): array
    {
        return $this->cookies;
    }

    /**
     * @return bool|string
     */
    public function dumpCookieFile()
    {
        $cookieFile = fopen("/tmp/cookie", 'r');
        $cont = fread($cookieFile, 100000000);
        fclose($cookieFile);

        return $cont;
    }

    /**
     * init class vars
     */
    private function initVars()
    {
        $this->checked = false;
    }

    /**
     *
     */
    private function extractHeadersSent()
    {
        $this->headersSent = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
    }

    /**
     *
     */
    public function closeCurl()
    {

        curl_close($this->curl); // I close curl, so the cookie is written -> https://stackoverflow
        //.com/questions/11390613/curl-post-method-not-creating-cookie - // Notice: Undefined property ??
        unset($this->curl);
        //usleep(100000); // 100000 = 100ms - to avoid DoS attack
        usleep(1000000); // 1 sec

        // Curl save the session with session_write_close(); .
        //      I reopen it to restore it
        if (ob_get_level() == 0) {
            ob_start();
        } else {
            /*Ok*/
        }
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        } else {
            /*Ok*/
        }
    }
}
