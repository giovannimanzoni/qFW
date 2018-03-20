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
     * usati per la creazione dell'oggetto
     ************************************************/

    // Imposta il valore di default
    public function setDefaultValue(int $defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    // Imposta lable. default vuota
    public function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
    }

    public function setDefaultText(string $text)
    {
        $this->defaultText = $text;
        return $this;
    }

    public function setLabelDisabled()
    {
        $this->labelDisabled = true;
        return $this;
    }

    /*************************************************
     * usati per la costruzione del form
     ************************************************/
    public function getLabel(): string
    {
        return $this->label;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isLabelDisabled(): bool
    {
        return $this->labelDisabled;
    }
}
