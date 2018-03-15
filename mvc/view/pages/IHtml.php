<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\view\pages;

/**
 * Generic template that can be use in all website section, private admin area and public user area
 *
 * Interface IHtml
 */
interface IHtml
{

    /**
     * @return string get html
     */
    public function getHtml(): string;
}
