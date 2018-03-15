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
 * Interface IEngineName
 *
 * Standardizes the method for get name of engine to call
 *
 * @package qFW\mvc\model\httpRequest\engine
 */
interface IEngineName
{
    /**
     * Get name of engine
     *
     * @return string
     */
    public function getName(): string;
}
