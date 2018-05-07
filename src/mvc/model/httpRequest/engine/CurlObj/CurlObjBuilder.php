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

use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\vocabulary\Voc;
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
    /** @var string  Hold default user agent */
    const DEFAULT_USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu 
                                Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36';

    /** @var string  Hold default reffer */
    const DEFAULT_REFFER = 'https://www.google.it';

    /** @var  Cookie file */
    private $cookieFile;

    /** @var array Hold curl option */
    private $arrOptions = array();

    /** @var \qFW\mvc\model\httpRequest\verbs\IVerbs Hold Verb to use */
    private $verb;

    /** @var bool  Hold verbose mode */
    private $verboseResourceHandle = false;

    /** @var array  Curl field */
    private $fieldArr = array();

    /** @var \qFW\log\LogProxy  Hold Log engine */
    private $loggerEngine;

    /** @var string  Url to curl */
    private $url = '';

    /** @var string  Http content type */
    private $httpContentType = '';

    /** @var string  Http accept type */
    private $httpAcceptType = '';

    /** @var array  Additional http headers */
    private $additionalsHttpHeaders = array();

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    /** @var \qFW\mvc\controller\dataTypes\UtString */
    private $utStr;

    //Observe
    /** @var array hold object to observe */
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
     * Call observed object function
     */
    public function notify()
    {
        foreach ($this->observers as $value) {
            $value->update($this); // Method of the object that observes
        }
    }

    /***********************************
     * BUILDER
     ***********************************/

    /**
     * CurlObjBuilder constructor.
     *
     * @param string                                  $url
     * @param \qFW\mvc\model\httpRequest\verbs\IVerbs $verb
     * @param \qFW\log\ILogOutput                     $outputLog
     * @param string                                  $contentType Http Content Type
     * @param string                                  $acceptType  Http Accept Type
     * @param \qFW\mvc\controller\lang\ILang          $lang
     */
    public function __construct(
        string $url,
        IVerbs $verb,
        ILogOutput $outputLog,
        string $contentType,
        string $acceptType,
        ILang $lang
    ) {

        $this->utStr = new UtString($lang);
        $this->voc = new Voc();

        // Init log engine
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
        $this->setOption(CURLOPT_RETURNTRANSFER, true); // Avoid the remote content being passed to print
        $this->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $this->setOption(CURLOPT_SSL_VERIFYHOST, false);
        $this->setOption(CURLOPT_FOLLOWLOCATION, true); // Follow redirect HTTP 3xx.

        $this->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        $this->setOption(CURLOPT_DNS_CACHE_TIMEOUT, 2);

        $this->setOption(CURLOPT_HEADER, true); // Do not print header in curl get

        // Outgoing headers are available in the array returned by curl_getinfo(), under request_header key
        $this->setOption(CURLINFO_HEADER_OUT, true);

        $this->setHttpContentType($contentType);          // Init, must be set
        $this->setHttpAcceptType($acceptType);            // Init, must be set
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
     * @param array $arr List of parameters, they will be encoded by selected encoding format
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
             * Headers
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
             * Other curl options
             ************************************/
            // Set content type -
            //  https://stackoverflow.com/questions/23714383/what-are-all-the-possible-values-for-http-content-type-header
            switch ($this->verb->getName()) {
                case (new Post())->getName():
                    if ($this->utStr->strSearch($hCT, 'json')) {
                        $data = json_encode($this->fieldArr);
                        $this->setOption(CURLOPT_POSTFIELDS, $data);
                    } elseif ($this->utStr->strSearch($hCT, 'urlencoded')) {
                        $data = http_build_query($this->fieldArr);
                        $this->setOption(CURLOPT_POSTFIELDS, $data);
                    } else {
                        $this->addLog('_VOC_', $this->voc->curlHttpHeaderNotIMplemented());
                    }
                    $this->setOption(CURLOPT_URL, $this->url);
                    break;

                case (new Get())->getName():
                    $this->setOption(CURLOPT_URL, "{$this->url}?" .
                                                http_build_query($this->fieldArr));
                    break;

                case (new Delete())->getName(): // @todo develop
                case (new Put())->getName():    // @todo develop
                default:
                    //$this->setOption(CURLOPT_URL, $this->url . '?'. http_build_query($this->fieldArr) );
                    $this->addLog('build(): Not implemented.');
                    break;
            }

            $this->notify();
        } else {
            /*Ok*/
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
        //if (! is_rsource($this->cookieFile)) $this->addLog('Cookiefile is not a resource.'); // False error on file ?

        if (is_null($this->verb)) {
            $this->addLog('_VOC_', $this->voc->curlVerbNotSet());
        } else {
            /*Ok*/
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
        $res = true;
        if ($this->loggerEngine->getLogsQty()) {
            $res = false;
        } else {
            /*Ok*/
        }

        return $res;
    }

    /************************************************
     * Optionals for setup
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

                // Follow commands should not serve, the above command resets curl to Get requests
                //    $this->setOption(CURLOPT_POST,0);
                // GET mode is default for curl, you do not need to set specific headers
                break;

            case Delete::getName():
            case Put::getName():
                $this->setOption(CURLOPT_POST, 0);
                $this->setOption(CURLOPT_CUSTOMREQUEST, $verb->getName());
                break;

            default:
                $this->addLog('setVerb(): Verb not implemented.');
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
            $this->addLog('_VOC_', $this->voc->curlUserAgentEmpty());
        } else {
            $this->setOption(CURLOPT_USERAGENT, $userAgent);
        }
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
                $this->addLog('_VOC_', $this->voc->curlVerboseErr());
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
     * For the use of the object
     ************************************************/

    /**
     * Get curl options
     *
     * @return array
     */
    public function getOptions(): array
    {
        $options = $this->arrOptions;

        // Once you return, you do not need to go back to the next curl, the curl object has now been set
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
     * @param string $vocFun
     */
    private function addLog(string $err, string $vocFun = '')
    {
        $this->loggerEngine->log(new LogMessage('', $err, $vocFun));
    }
}
