<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\log;

/**
 * Class LogProxy
 *
 * Proxy log engine
 *
 * @package qFW\log
 */
class LogProxy
{
    /** @var  object of logger engine */
    protected $instance;

    /**
     * LogProxy constructor.
     *
     * @param \qFW\log\ILogOutput $output
     */
    public function __construct(ILogOutput $output)
    {
        $class =  $output->getName();

        $this->instance = new $class($output->getUid());
    }

    /**
     * Magic method for call function on proxyed logger class
     *
     * @param string $name      function name
     * @param array  $arguments arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->instance, $name], $arguments);
    }
}
