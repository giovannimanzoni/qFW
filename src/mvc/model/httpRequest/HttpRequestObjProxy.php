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
use qFW\mvc\model\httpRequest\engine\IEngineObjBuilder;
use SplSubject;

/**
 * Class HttpRequestObjProxy
 *
 * Proxy http request
 *
 * @package qFW\mvc\model\httpRequest
 */
class HttpRequestObjProxy implements \SplObserver
{
    /** @var  instance of proxied engine*/
    protected $instance;

    /**
     * HttpRequestObjProxy constructor.
     *
     * @param \qFW\mvc\model\httpRequest\engine\IEngineName       $engine
     * @param \qFW\mvc\model\httpRequest\engine\IEngineObjBuilder $objBuilder
     */
    public function __construct(IEngineName $engine, IEngineObjBuilder $objBuilder)
    {
        $class = $engine->getName();
        $this->instance = new $class($objBuilder);
    }

    /**
     * Call object methods
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->instance, $name], $arguments);
    }

    /**
     * Call update function on proxied http request engine
     *
     * @param \SplSubject $subject
     *
     * @return mixed|void
     */
    public function update(SplSubject $subject)
    {
        $arguments[] = $subject;
        return $this->__call('update', $arguments);
    }
}
