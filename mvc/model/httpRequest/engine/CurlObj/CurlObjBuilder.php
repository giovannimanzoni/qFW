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

use qFW\mvc\model\httpRequest\engine\IEngineObjBuilder;
use qFW\mvc\model\httpRequest\verbs\Delete;
use qFW\mvc\model\httpRequest\verbs\Get;
use qFW\mvc\model\httpRequest\verbs\IVerbs;
use qFW\mvc\model\httpRequest\verbs\Post;
use qFW\mvc\model\httpRequest\verbs\Put;
use qFW\log\LogProxy;
use qFW\log\ILogOutput;
use qFW\log\LogMessage;
use qFW\mvc\controller\dataTypes\UtString;

/**
 * Class CurlObjBuilder
 *
 * Builder for Curl
 *
 * @package qFW\mvc\model\httpRequest\engine\CurlObj
 */
class CurlObjBuilder implements IEngineObjBuilder, \SplSubject
{
    /** hold default user agent */
    const DEFAULT_USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu 
                                Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36';

    /** hold default reffer */
    const DEFAULT_REFFER = 'https://www.google.it';

    /** @var  Cookie file */
    private $cookieFile;

    /** @var array hold curl option */
    private $arrOptions = array();

    /** @var \qFW\mvc\model\httpRequest\verbs\IVerbs Hold Verb to use*/
    private $verb;

    /** @var bool  hold verbose mode */
    private $verboseResourceHandle = false;

    /** @var array  curl field*/
    private $fieldArr = array();

    /** @var \qFW\log\LogProxy  hold Log engine*/
    private $loggerEngine;

    /** @var string  url to curl*/
    private $url = '';

    /** @var string  http content type*/
    private $httpContentType = '';

    /** @var string  http accept type*/
    private $httpAcceptType = '';

    /** @var array  additional http headers*/
    private $additionalsHttpHeaders = array();

    //Observe
    /** @var array hold object to observe*/
    private $observers = array();



    /***********************************
     * OBSERVE - to update Curl object using this class
     ***********************************/

    /**
     * For attach object to observe
     *
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer)
    {
        $observerHash = spl_object_hash($observer);
        $this->observers[$observerHash] = $observer;
    }

    /**
     * For detach object to observe
     *
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        $observerHash = spl_object_hash($observer);
        unset($this->observers[$observerHash]);
    }

    /**
     * call observed object function
     */
    public function notify()
    {
        foreach ($this->observers as $value) {
            $value->update($this); // metodo dell'oggetto che osserva
        }
    }

    /***********************************
     * BUILDER
     ***********************************/

    /**
     * CurlObjBuilder constructor.
     *
     * @param string                                     $url
     * @param \qFW\mvc\model\httpRequest\verbs\IVerbs $verb
     * @param \qFW\log\ILogOutput                     $outputLog
     * @param string                                     $contentType   Http Content Type
     * @param string                                     $acceptType    Http Accept Type
     */
    public function __construct(
        string $url,
        IVerbs $verb,
        ILogOutput $outputLog,
        string $contentType,
        string $acceptType
    ) {
        // init log engine
        $this->loggerEngine = new LogProxy($outputLog);

        $this->url = $url;
        $this->setVerb($verb);

        // COOKIE
        // http://blog.davenicholas.me.uk/2013/04/session-cookies-and-curl-in-php.html
        //$this->setCookieFile(tempnam('/tmp', 'COOKIE'));
        $this->setCookieFile('/tmp/cookie');

        $this->setUserAgent(self::DEFAULT_USER_AGENT);
        $this->setOption(CURLOPT_TIMEOUT, 60);
        $this->setOption(CURLOPT_CONNECTTIMEOUT, 60);
        $this->setOption(CURLOPT_RETURNTRANSFER, true); // evito che il contenuto remoto venga passato a print
        $this->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $this->setOption(CURLOPT_SSL_VERIFYHOST, false);
        $this->setOption(CURLOPT_FOLLOWLOCATION, true); // segue redirect HTTP 3xx.

        $this->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        $this->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 2 );

        $this->setOption(CURLOPT_HEADER, true); // do not print header in curl get
        $this->setOption(CURLINFO_HEADER_OUT, true); // outgoing headers are available in the array returned by curl_getinfo(), under request_header key


        $this->setHttpContentType($contentType);          // init, va impostato
        $this->setHttpAcceptType($acceptType);            // init, va impostato


    }

    /**
     * Set option for curl
     *
     * @param int $opt  Curl option
     * @param     $val  value for the option
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function setOption(int $opt, $val): CurlObjBuilder
    {
        $this->arrOptions[$opt] = $val;
        return $this;
    }

    /**
     * Set the parameters to send with curl request
     *
     * @param array $arr list of parameters, they will be encoded by selected encoding format
     *
     * @return $this
     */
    public function setFields(array $arr)
    {
        $this->fieldArr = $arr;
        return $this;
    }


    /**
     * Set the http content type. Content types are too many and can not be foreseen
     *  -> https://stackoverflow.com/questions/23714383/what-are-all-the-possible-values-for-http-content-type-header
     *
     * @param string $format
     *
     * @return $this
     */
    public function setHttpContentType(string $format): CurlObjBuilder
    {
        $this->httpContentType = $format;
        return $this;
    }

    /**
     * Set http accept header
     *
     * @param string $format
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function setHttpAcceptType(string $format): CurlObjBuilder
    {
        $this->httpAcceptType = $format;
        return $this;
    }

    /**
     * Set additional http headers
     *
     * @param array $headers
     *
     * @return $this
     */
    public function setAdditionalHttpHeaders(array $headers)
    {
        $this->additionalsHttpHeaders = $headers;
        return $this;
    }

    /**
     * Build (initialize) curl request
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function build(): CurlObjBuilder
    {

        if ($this->check()) {

            /*************************************
             * headers
             ************************************/

            $headers = array();

            foreach ($this->additionalsHttpHeaders as $addHeader) {
                $headers[] = $addHeader;
            }


            $hCT = $this->httpContentType;
            $hAT = $this->httpAcceptType;

            // todo migliorare con chiave
            $headersStandard = array(
                "Content-Type: {$hCT}",
                "Accept: {$hAT}",
                'Expect:' // https://stackoverflow
            //.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request/17971689
            );
            foreach ($headersStandard as $addHeader) {
                $headers[] = $addHeader;
            }

            $this->setOption(CURLOPT_HTTPHEADER, $headers);

            /*************************************
             * altre opzioni curl
             ************************************/
            // set content type -
            //  https://stackoverflow.com/questions/23714383/what-are-all-the-possible-values-for-http-content-type-header
            switch ($this->verb->getName()) {
                case (new Post())->getName():
                    if (UtString::strSearch($hCT, 'json')) {
                        $data = json_encode($this->fieldArr);
                        $this->setOption(CURLOPT_POSTFIELDS, $data);
                    } elseif (UtString::strSearch($hCT, 'urlencoded')) {
                        $data = http_build_query($this->fieldArr);
                        $this->setOption(CURLOPT_POSTFIELDS, $data);
                    } else {
                        $this->addLog('build(): questa modalità httpHeader non è implementata.');
                    }
                    $this->setOption(CURLOPT_URL, $this->url);
                    break;

                case (new Get())->getName():
                    $this->setOption(CURLOPT_URL, "{$this->url}?" .
                                                http_build_query($this->fieldArr));
                    break;

                case (new Delete())->getName():
                case (new Put())->getName():
                default:
                    //come si imposta ?
                    //$this->setOption(CURLOPT_URL, $this->url . '?'. http_build_query($this->fieldArr) );
                    $this->addLog('build(): verbo non implementato.');
                    break;
            }

            $this->notify();
        }
        return $this;
    }

    /**
     * Check this builder options
     *
     * @return bool
     */
    private function check(): bool
    {

        //if (! is_resource($this->cookieFile)) $this->addLog('Cookiefile non è una risorsa.');
        //  falso errore sui file?
        if (is_null($this->verb)) {
            $this->addLog('Verbo non impostato.');
        }

        if ($this->httpHeaderMode = '') {
            $this->addLog('httpheader mode non impostato.');
        }

        return $this->checkValid();
    }

    /**
     * If curl has got no errors and no waring return true
     *
     * @return bool
     */
    private function checkValid(): bool
    {
        $esito = true;
        if ($this->loggerEngine->getLogsQty()) {
            $esito = false;
        }

        return $esito;
    }

    /************************************************
     * OPZIONALI x la costruzione
     ***********************************************/

    /**
     * Set curl url
     *
     * @param string $url
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function setUrl(string $url): CurlObjBuilder
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set curl verb
     *
     * @param \qFW\mvc\model\httpRequest\verbs\IVerbs $verb
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function setVerb(IVerbs $verb): CurlObjBuilder
    {
        $this->verb = $verb;

        switch ($this->verb->getName()) {
            case Post::getName():
                $this->setOption(CURLOPT_CUSTOMREQUEST, null);
                $this->setOption(CURLOPT_POST, 1);

                break;

            case Get::getName():
                $this->setOption(CURLOPT_CUSTOMREQUEST, null);
                $this->setOption(CURLOPT_HTTPGET, true);

                // questi non dovrebbero servire, il comando sopra resetta curl a richieste di tipo Get
                //$this->setOption(CURLOPT_POST,0);
                // get default per curl, non serve impostare header specifici
                break;

            case Delete::getName():
            case Put::getName():
                $this->setOption(CURLOPT_POST, 0);
                $this->setOption(CURLOPT_CUSTOMREQUEST, $verb->getName());
                break;

            default:
                $this->addLog('setVerb(): Verbo non implementato.');
                break;
        }

        return $this;
    }

    /**
     * Set curl user agent
     *
     * @param string $userAgent
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function setUserAgent(string $userAgent): CurlObjBuilder
    {
        if ($userAgent == '') {
            $this->addLog('UserAgent impostato vuoto.');
        } else {
            $this->setOption(CURLOPT_USERAGENT, $userAgent);
        } // Imposto uno user-agent in modo arbitrario
        return $this;
    }

    /**
     * Set cookie file
     *
     * @param $cookieFile
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function setCookieFile($cookieFile): CurlObjBuilder
    {
        $this->cookieFile = $cookieFile;
        $this->arrOptions[CURLOPT_COOKIEFILE] = $this->cookieFile;
        $this->arrOptions[CURLOPT_COOKIEJAR] = $this->cookieFile;
        return $this;
    }

    /**
     * Set to ignore last session cookie
     *
     * @param bool $mode
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function ignoreLastSessionCookie(bool $mode): CurlObjBuilder
    {
        $this->arrOptions[CURLOPT_COOKIESESSION] = $mode;
        return $this;
    }

    /**
     * Set verbose option for curl
     *
     * @param bool $verbose
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function setVerbose(bool $verbose): CurlObjBuilder
    {
        if ($verbose) {
            $this->verboseResourceHandle = fopen('php://temp', 'w+');
            if ($this->verboseResourceHandle == false) {
                $this->addLog('Impossibile impostare modalità verbose.');
            }

            $this->arrOptions[CURLOPT_VERBOSE] = true;
            $this->arrOptions[CURLOPT_STDERR] = $this->verboseResourceHandle;
        } else {
            $this->arrOptions[CURLOPT_VERBOSE] = false;
            if (is_resource($this->verboseResourceHandle)) {
                fclose($this->verboseResourceHandle);
            }
        }
        return $this;
    }

    /**
     * Set debug option for curl
     *
     * @param bool $debug
     *
     * @return \qFW\mvc\model\httpRequest\engine\CurlObj\CurlObjBuilder
     */
    public function setDebug(bool $debug): CurlObjBuilder
    {
        if ($debug) {
            $this->arrOptions[CURLOPT_VERBOSE] = -1;
        } else {
            $this->arrOptions[CURLOPT_VERBOSE] = 0;
        }

        return $this;
    }

    /************************************************
     * x l'uso dell'oggetto
     ************************************************/

    /**
     * Get curl options
     *
     * @return array
     */
    public function getOptions(): array
    {
        $options = $this->arrOptions;

        // una volta ritornate, non serve ritornarle ancora al prossimo curl, ormai l'oggetto curl è stato impostato
        $this->arrOptions = array();

        return $options;
    }

    /************************************************
     * Log
     ************************************************/

    /**
     * Get logs
     *
     * @return string
     */
    public function getLogs(): string
    {
        return $this->loggerEngine->getLogs();
    }

    /**
     * Get logs qty
     *
     * @return int
     */
    public function getLogsQty(): int
    {
        return $this->loggerEngine->getLogsQty();
    }

    /**
     * Add log to the logger
     *
     * @param string $err
     */
    private function addLog(string $err)
    {
        $this->loggerEngine->log(new LogMessage('', $err));
    }
}
