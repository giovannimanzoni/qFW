<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\template;

/**
 * Class IFooter
 *
 * @package qFW\mvc\view\template
 */
interface IFooter
{
    /**
     * @return string
     */
    public function getHtml(): string;
}
