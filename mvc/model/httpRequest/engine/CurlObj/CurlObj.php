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

    /** @var \qFW\mvc\model\httpRequest\engine\IEngineObjBuilder  object builder*/
    private $objBuilder;

    /** @var bool hold if check is performed */
    private $checked = false;

    /** @var bool  hold if curl check is valid*/
    private $valid = false;

    // per addError(), cosi si può tenere traccia, se si esegue piu volte check() quale volta è stato generato l'errore.
    /** @var int  index for addError*/
    private $checkTime = 1;

    /** @var array hold list for all logs*/
    private $logHistory = array();

    /** @var string return html */
    private $html='';

    /** @var array  */
    private $cookies=array();

    /** @var string  */
    private $headersSent='';


    /** @var string  */
    private $headersGet=array();

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
        $this->headersGet=array();

        // init curl obj
        // fixme -> da chiamare solo la prima volta nella pagina principale che usa curl ?
        //session_write_close(); // https://stackoverflow.com/questions/2424714/how-to-maintain-session-in-curl-in-php
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
        // può servire chiamare mostrare piu volte la pagina. Se le opzioni di curl non sono cambiate,
        //  non ha senso ripetere ogni volta il check
        if (!$this->checked) {
            $this->check();
        }

        if ($this->valid) {
            $this->curl($this->objBuilder);
            $html=$this->html;

        } else {
            $html = 'Errori nel builder.';
        }

        return $html;
    }

    /**
     *  var dump of curl_getinfo()
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

        // se ci sono errori
        if ($this->objBuilder->getLogsQty()) {
            $this->logHistory[$this->checkTime][] = $this->objBuilder->getLogs();
        } else {
            $this->valid = true;
        }

        $this->checkTime++; //raggruppa gli eventuali messaggi di errore che potrebbero essere generati
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

        //@todo farla parametrica dal builder ?

        // https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request/17971689
        $ch=$this->curl;
        curl_setopt($ch, CURLOPT_HEADERFUNCTION,
            function($ch, $header) //use (&$headers)
            {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $name = strtolower(trim($header[0]));
                if (!array_key_exists($name, $this->headersGet))
                    $this->headersGet[$name] = [trim($header[1])];
                else
                    $this->headersGet[$name][] = trim($header[1]);

                return $len;
            }
        );


        $htmlCurl = curl_exec($this->curl); // eseguo la chiamata
        $this->extractHeadersSent();

        if (curl_error($this->curl)) {
            $this->html = '<p>Errori della chiamata curl: <br>' . curl_error($this->curl) . '</p>';

        } elseif (is_string($htmlCurl)) {

            $this->html = trim($htmlCurl);
        } else {

            $this->html = '';
        }

        $this->closeCurl();
    }


    /**
     *
     * must be public
     *
     * @param $ch
     * @param $headerLine
     *
     * @return int
     */
    public function curlResponseHeaderCallback($ch, $headerLine) {

        if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1) {
            $this->cookies[] = $cookie;
        }

        return strlen($headerLine); // Needed by curl
    }


    /**
     * NON USATA, FA DANNO ?
     *
     * @param string $html
     */
    /*public function extractCookies(string $html)
    {
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $html, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        $this->cookies=$cookies;
    }*/

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
        $cont=fread($cookieFile, 100000000);
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
        $this->headersSent= curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
    }



    /**
     *
     */
    public function closeCurl()
    {

        curl_close($this->curl); // chiudo curl, cosi viene scritto il cookie -> https://stackoverflow
        //.com/questions/11390613/curl-post-method-not-creating-cookie - // Notice: Undefined property ??
        unset($this->curl);
        //usleep(100000); // 100000 = 100ms - per evitare DoS attack
        usleep(1000000); // 1 sec

        // curl salva la sessione con  session_write_close(); .
        //      la riapro per far ripristinarla
        if (ob_get_level() == 0) {
            ob_start();
        }
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }



}
