<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\httpRequest\engine;

use qFW\mvc\controller\lang\ILang;
use qFW\mvc\model\httpRequest\verbs\IVerbs;
use qFW\log\ILogOutput;

/**
 * Interface IEngineObjBuilder
 *
 * Standardizes the methods for object builder engine
 *
 * @package qFW\mvc\model\httpRequest\engine
 */
interface IEngineObjBuilder
{
    /**
     * IEngineObjBuilder constructor.
     *
     * @param string                                  $url
     * @param \qFW\mvc\model\httpRequest\verbs\IVerbs $verb
     * @param \qFW\log\ILogOutput                     $outputLog
     * @param string                                  $contentType
     * @param string                                  $acceptType
     * @param \qFW\mvc\controller\lang\ILang          $lang
     */
    public function __construct(
        string $url,
        IVerbs $verb,
        ILogOutput $outputLog,
        string $contentType,
        string $acceptType,
        ILang $lang
    );

    /**
     * Build (initialize) request
     *
     * @return mixed
     */
    public function build();

    /**
     * Set url
     *
     * @param string $url
     *
     * @return mixed
     */
    public function setUrl(string $url);

    /**
     * Set option
     *
     * @param int $opt
     * @param     $val
     *
     * @return mixed
     */
    public function setOption(int $opt, $val);

    /**
     * Set verb
     *
     * @param \qFW\mvc\model\httpRequest\verbs\IVerbs $verb
     *
     * @return mixed
     */
    public function setVerb(IVerbs $verb);

    /**
     * Set the http content type. Content types are too many and can not be foreseen
     *
     * @param string $format
     *
     * @return mixed
     */
    public function setHttpContentType(string $format);

    /**
     * Set http accept header
     *
     * @param string $format
     *
     * @return mixed
     */
    public function setHttpAcceptType(string $format);

    /**
     * Set additional http headers
     *
     * @param array $headers
     *
     * @return mixed
     */
    public function setAdditionalHttpHeaders(array $headers);

    /**
     * Set user agent
     *
     * @param string $userAgent
     *
     * @return mixed
     */
    public function setUserAgent(string $userAgent);

    /**
     * Set cookie file
     *
     * @param $cookieFile
     *
     * @return mixed
     */
    public function setCookieFile($cookieFile);

    /**
     * Set to ignore last session cookie
     *
     * @param bool $mode
     *
     * @return mixed
     */
    public function ignoreLastSessionCookie(bool $mode);

    /**
     * Set verbose option for curl
     *
     * @param bool $verbose
     *
     * @return mixed
     */
    public function setVerbose(bool $verbose);

    /**
     * Set debug option for curl
     *
     * @param bool $debug
     *
     * @return mixed
     */
    public function setDebug(bool $debug);

    /**
     * Get curl options
     *
     * @return array
     */
    public function getOptions(): array;

    /**
     * Get logs
     *
     * @return string
     */
    public function getLogs(): string;

    /**
     * Get logs qty
     *
     * @return int
     */
    public function getLogsQty(): int;
}
