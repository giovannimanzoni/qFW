<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\form;

use qFW\log\ILogOutput;
use qFW\mvc\controller\lang\ILang;
use qFW\mvc\view\form\elements\IFormElements;

/**
 * Interface IFormPage
 *
 * Standardize methods for pages of the form
 *
 * @package qFW\mvc\view\form
 */
interface IFormPage
{
    /**
     * IFormPage constructor.
     *
     * @param \qFW\log\ILogOutput $logger
     * @param string              $tabName
     */
    public function __construct(ILogOutput $logger, string $tabName = ' ');

    /**
     * Add form element to the page
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $formElement
     *
     * @return mixed
     */
    public function addElement(IFormElements $formElement);

    /**
     * Get form element of this page
     *
     * @return array
     */
    public function getPageElements(): array;

    /**
     * Get how many form element are there on this page
     *
     * @return int
     */
    public function getPageElementsQty(): int;

    /**
     * Get page name
     *
     * @return string
     */
    public function getPageName(): string;
}
