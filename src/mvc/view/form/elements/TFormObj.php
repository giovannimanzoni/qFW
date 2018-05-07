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

/**
 * Trait TFormObj
 *
 * Manage Form elements
 *
 * @package qFW\mvc\view\form\elements
 */
trait TFormObj
{
    private $required = false;
    private $defaultValue = 0;
    private $defaultText = '';
    private $label = '';
    private $labelDisabled = false;

    /*************************************************
     * Used for creating the object
     ************************************************/

    /**
     * Set the default value
     *
     * @param int $defaultValue
     *
     * @return $this
     */
    public function setDefaultValue(int $defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * Set label. default empty
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setDefaultText(string $text)
    {
        $this->defaultText = $text;
        return $this;
    }

    /**
     * @return $this
     */
    public function setLabelDisabled()
    {
        $this->labelDisabled = true;
        return $this;
    }

    /*************************************************
     * Used for the construction of the form
     ************************************************/

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isLabelDisabled(): bool
    {
        return $this->labelDisabled;
    }
}
