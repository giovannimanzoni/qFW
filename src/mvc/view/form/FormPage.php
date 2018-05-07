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
 * Class FormPage
 *
 * Create page of a form
 *
 * @package qFW\mvc\view\form
 */
class FormPage implements IFormPage
{
    /** @var array Elements of the page */
    private $formElements = array();

    /** @var string Name of the page */
    private $pageName;

    /** @var \qFW\log\ILogOutput Output log */
    private $logger;

    /**
     * FormPage constructor.
     *
     * @param \qFW\log\ILogOutput            $logger
     * @param string                         $pageName
     */
    public function __construct(ILogOutput $logger, string $pageName = ' ')
    {
        $this->pageName = $pageName;
        $this->logger = $logger;
    }

    /**
     * Add form element to the page
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $formElement
     *
     * @return $this
     */
    public function addElement(IFormElements $formElement)
    {
        $formElement->createLogger($this->logger);
        $this->formElements[] = $formElement;
        return $this;
    }

    /**
     * Get page elements
     *
     * @return array
     */
    public function getPageElements(): array
    {
        return $this->formElements;
    }

    /**
     * Get how many form element are there on this page
     *
     * @return int
     */
    public function getPageElementsQty(): int
    {
        return count($this->formElements);
    }

    /**
     * Get page name
     *
     * @return string
     */
    public function getPageName(): string
    {
        return $this->pageName;
    }
}
