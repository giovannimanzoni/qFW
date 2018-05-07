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
 * Class Navbar
 *
 * Manage navbar generation
 *
 * @package qFW\mvc\view\pages\elements
 */
final class Navbar implements INavbar
{
    /** @var string Custom logo path */
    private $logoPath = '';

    /** @var string Hold html */
    private $html = '';

    /**
     * Navbar constructor.
     *
     * @param \qFW\mvc\view\template\ITplNavbar $templateNavbar
     */
    public function __construct(ITplNavbar $templateNavbar)
    {
        $this->html = $templateNavbar->getHtml();
    }

    /**
     * Set custom logo @todo
     *
     * @param string $path
     *
     * @return $this
     */
    public function setLogo(string $path)
    {
        $this->logoPath = $path;
        return $this;
    }

    /**
     * Get html for navbar block
     *
     * @todo develop this function
     * @return string
     */
    public function getHtml(): string
    {

        return $this->html;
    }

    // @todo
    /*private function tryGetPrivateLogo(): string
    {
        $html='';


        return $html;
    }*/
}
