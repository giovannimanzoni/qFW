<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\httpRequest;

use qFW\mvc\model\httpRequest\engine\IEngineName;
use qFW\mvc\model\httpRequest\verbs\IVerbs;
use qFW\log\ILogOutput;

/**
 * Class HttpRequestBuilderProxy
 *
 * Proxy for use different http request engine
 *
 * @package qFW\mvc\model\httpRequest
 */
class HttpRequestBuilderProxy
{
    /** @var  */
    protected $instance;

    /**
     * HttpRequestBuilderProxy constructor.
     *
     * @param \qFW\mvc\model\httpRequest\engine\IEngineName $engine
     * @param string                                        $url
     * @param \qFW\mvc\model\httpRequest\verbs\IVerbs       $verb
     * @param \qFW\log\ILogOutput                           $outputLog
     * @param string                                        $contentType
     * @param string                                        $acceptType
     */
    public function __construct(
        IEngineName $engine,
        string $url,
        IVerbs $verb,
        ILogOutput $outputLog,
        string $contentType,
        string $acceptType
    ) {
        $class = $engine->getName() . 'Builder';
        $this->instance = new $class($url, $verb, $outputLog, $contentType, $acceptType);
    }

    /**
     * Call proxied functions
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->instance, $name], $arguments);
    }
}
