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
    /** @var array  elements of the page*/
    private $formElements = array();

    /** @var string  name of the page*/
    private $pageName = '';

    /** @var \qFW\log\ILogOutput  output log*/
    private $outputLog;

    /**
     * FormPage constructor.
     *
     * @param string                 $pageName      name of the page
     * @param \qFW\log\ILogOutput $outputLog
     */
    public function __construct(string $pageName, ILogOutput $outputLog)
    {
        $this->pageName = $pageName;
        $this->outputLog = $outputLog;
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
        $this->formElements[] = $formElement;
        $formElement->createLogger($this->outputLog, $this->outputLog->getUid());
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
