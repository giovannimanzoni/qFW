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

use qFW\mvc\view\form\TError;
use qFW\mvc\view\form\TGlobalAttributes;

/**
 * Class FormTitle
 *
 * Allow use of html Title tag inside html form
 *
 * @package qFW\mvc\view\form\elements
 */
class FormTitle implements IFormElements
{
    /** @var string title value */
    private $value='';

    /** @var int dimension of title */
    private $dimension=1;

    use TError;
    use TGlobalAttributes;

    /**
     * FormTitle constructor.
     *
     * @param string $value     title string
     * @param int    $dimension title dimension from 1 to 6
     */
    public function __construct(string $value, int $dimension)
    {
        $this->value = $value;
        $this->dimension = $dimension;
    }

    /*********************************************************************************************************
     * metodi definiti nell'interfaccia ma non usati @codingStandardsIgnoreStart
     ********************************************************************************************************/

    /**
     * @param int $defaultValue
     *
     * @return mixed|void
     */
    public function setDefaultValue(int $defaultValue)
    {
    }

    /**
     * @param string $label
     *
     * @return mixed|void
     */
    public function setLabel(string $label)
    {
    }

    /**
     * @return mixed|void
     */
    public function setLabelOnTop()
    {
    }

    /**
     * @param int $ratio
     *
     * @return mixed|void
     */
    public function setElementRatio(int $ratio)
    {
    }

    /**
     * @param string $text
     *
     * @return mixed|void
     */
    public function setDefaultText(string $text)
    {
    }

    /**
     * @return int
     */
    public function getElementDim(): int
    {
        return self::BOOTSTRAP_COLUMNS;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    public function isHorizontal(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getElementRowClass(): string
    {
        return 'col-xs-12';
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isRatioSetted(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isLabelDisabled(): bool
    {
        return false;
    }
    // @codingStandardsIgnoreEnd

    /*********************************************************************************************************
     * metodi opzionali
     ********************************************************************************************************/

    /**
     *  Return code for form engine
     *
     * @return string
     */
    public function getElementType(): string
    {
        return 'h';
    }

    /**
     * Set id of this html element
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *  Set css class applyed to this element row
     *
     * @param string $class
     *
     * @return $this
     */
    public function setElementRowClass(string $class)
    {
        $this->class = $class;
        return $this;
    }

    /**********************************************************************************
     * Check if this form element has got some errors
     *
     * @return bool : esito
     */
    public function check(): bool
    {

        if (($this->dimension < 1) || ($this->dimension > 6)) {
            $this->addLog("Titolo '{$this->value}': Dimensione del testo fuori range (1..6) : {$this->dimension}");
        }
        if ($this->value == '') {
            $this->addLog('Testo del titolo vuoto');
        }

        return $this->getCheckEsito();
    }


    /**********************************************************************************
     * Make html for this form element
     *
     * @return string   : html
     */
    public function make(): string
    {
        $html = '';

        if (!$this->TErrorChecked) {
            $this->check();
        }
        if ($this->TErrorValid) {
            $html = "<h{$this->dimension} {$this->getGlobalAttributes()}>{$this->value}</h{$this->dimension}>";
        }
        return $html;
    }
}
