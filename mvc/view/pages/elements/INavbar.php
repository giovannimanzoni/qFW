<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\pages\elements;

use qFW\mvc\view\template\ITplNavbar;

/**
 * Interface INavbar
 *
 * @package qFW\mvc\view\pages\elements
 */
interface INavbar
{
    /**
     * INavbar constructor.
     *
     * @param \qFW\mvc\view\template\ITplNavbar $navbar
     */
    public function __construct(ITplNavbar $navbar);

        /**
     * Set custom logo
     *
     * @param string $path
     *
     * @return mixed
     */
    public function setLogo(string $path);

    /**
     * Get html
     *
     * @return string
     */
    public function getHtml(): string;
}