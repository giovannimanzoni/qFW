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

use qFW\mvc\controller\lang\ILang;
use qFW\mvc\view\template\content\ITplContent;

/**
 * Interface IContents
 *
 * @package qFW\mvc\view\pages\elements
 */
interface IContent
{
    /**
     * IContent constructor.
     *
     * @param \qFW\mvc\view\template\content\ITplContent $templateContent
     * @param \qFW\mvc\controller\lang\ILang             $lang
     */
    public function __construct(ITplContent $templateContent, ILang $lang);

    /**
     * Add breacumb
     *
     * @param array $breadcrumb
     *
     * @return mixed
     */
    public function addBreadcrumb(array $breadcrumb);

    /**
     * Return breadcrumb
     *
     * @return string
     */
    public function getBreadCrumb(): string;

    /**
     * Get HTML
     *
     * @return string
     */
    public function getHtml(): string;
}
