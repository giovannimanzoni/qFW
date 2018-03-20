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

/**
 * Interface IFormObjBuilder
 *
 * Standardize methods for build a form
 *
 * @package qFW\mvc\view\form
 */
interface IFormObjBuilder
{
    /**
     * IFormObjBuilder constructor.
     *
     * @param \qFW\log\ILogOutput $outputLog
     */
    public function __construct(ILogOutput $outputLog);

    /**
     * Get elements of the form
     *
     * @param string $pageName
     *
     * @return array
     */
    public function getElements(string $pageName): array;

    /**
     * get name of all pages
     *
     * @return array
     */
    public function getPagesName(): array;

    /**
     * builder
     *
     * @return mixed
     */
    public function build();

    /**
     * Add page to the form
     *
     * @param \qFW\mvc\view\form\IFormPage $page
     *
     * @return \qFW\mvc\view\form\FormObjBuilder
     */
    public function addPage(IFormPage $page): FormObjBuilder;

    /***********************************
     * proprietà del form
     **********************************/
    /**
     * Set required symbol for the label
     *
     * @param string $symbol
     *
     * @return \qFW\mvc\view\form\FormObjBuilder
     */
    public function setRequiredSymbol(string $symbol): FormObjBuilder;

    /**
     * Set string for required form element
     *
     * @param string $text
     *
     * @return \qFW\mvc\view\form\FormObjBuilder
     */
    public function setRequiredString(string $text): FormObjBuilder;

    /**
     * Get the symbol used for mark required form elements
     *
     * @return string
     */
    public function getRequiredSymbol(): string;

    /**
     * Get string for required form element
     *
     * @return string
     */
    public function getRequiredString(): string;

    /**
     * Check if builder options are valid
     *
     * @return bool
     */
    public function check(): bool;

    /**
     * Get logs
     *
     * @return string
     */
    public function getLogs(): string;
}
