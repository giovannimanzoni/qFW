<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\view\form\elements;

use qFW\mvc\view\form\IFormElementsFinalize;

/**
 * Interface IFormElements
 *
 * Standardize methods for all form html elements
 *
 * @package qFW\mvc\view\form\elements
 */
interface IFormElements extends IFormElementsFinalize
{

    // public attribute on const start from 7.1.0
    /** number of bootstrap column */
    const BOOTSTRAP_COLUMNS = 12;

    // usate per definire l'oggetto del form

    /**
     * Set default value
     *
     * @param int $defaultValue
     *
     * @return mixed
     */
    public function setDefaultValue(int $defaultValue);

    /**
     * Set default text
     *
     * @param string $text
     *
     * @return mixed
     */
    public function setDefaultText(string $text);

    /**
     * Set label
     *
     * @param string $label
     *
     * @return mixed
     */
    public function setLabel(string $label);

    /**
     * Set label on top of this form element
     *
     * @return mixed
     */
    public function setLabelOnTop();

    /**
     * Set element ratio between label and this element
     *
     * @param int $col2
     *
     * @return mixed
     */
    public function setElementRatio(int $col2);

    /**
     * Set css class applyed to this element row
     *
     * @param string $class
     *
     * @return mixed
     */
    public function setElementRowClass(string $class);


    //usate per creare il form

    /**
     * Get html id
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Get text of the label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get bootstrap col dim/width
     *
     * @return int
     */
    public function getElementDim(): int;

    /**
     * Return if this element has got label on left of on top
     *
     * @return bool
     */
    public function isHorizontal(): bool;

    /**
     * Get css class applyed to this element row
     *
     * @return string
     */
    public function getElementRowClass(): string;

    /**
     * Get if this form element is required for the form
     *
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * Get element type it is a costant used to build the form
     *
     * @return string
     */
    public function getElementType(): string;
}
