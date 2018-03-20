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

use qFW\mvc\view\template\ITplSidebar;

/**
 * Interface ISidebar
 *
 * @package qFW\mvc\view\pages\elements
 */
interface ISidebar
{
    /**
     * ISidebar constructor.
     *
     * @param \qFW\mvc\view\template\ITplSidebar $sidebarTpl
     */
    public function __construct(ITplSidebar $sidebarTpl);

    /**
     * @return string
     */
    public function getHtml(): string;
}
