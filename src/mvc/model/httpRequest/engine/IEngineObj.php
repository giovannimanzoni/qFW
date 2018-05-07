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

/**
 * Interface IEngineObj
 *
 * Standardizes the method for get http request engine
 *
 * @package qFW\mvc\model\httpRequest\engine
 */
interface IEngineObj
{
    /**
     * IEngineObj constructor.
     *
     * @param \qFW\mvc\model\httpRequest\engine\IEngineObjBuilder $objBuilder
     */
    public function __construct(IEngineObjBuilder $objBuilder);

    /**
     * Get results
     *
     * @return string
     */
    public function getResult(): string;

    /**
     * Get logs
     *
     * @return string
     */
    public function getLogs(): string;
}
