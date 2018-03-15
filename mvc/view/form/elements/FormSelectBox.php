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
 * Class FormSelectBox
 *
 * Allow use of html Select inside html form
 *
 * @package qFW\mvc\view\form\elements
 */
class FormSelectBox implements IFormElements
{
        // specific to select box
    /** @var array hold options for the select element*/
    private $options = array();

    /** @var bool Specifies that the drop-down list should automatically get focus when the page loads */
    private $autofocus = false;

    /** @var bool Specifies if a drop-down list should be disabled */
    private $disabled = false;

    /** @var bool Specifies that multiple options can be selected at once */
    private $multiple = false;

    /** @var int Defines the number of visible options in a drop-down list */
    private $size = 0;

    use TError;
    use TFormObj;
    use TBootstrap;
    use TGlobalAttributes;

    /**
     * FormSelectBox constructor.
     *
     * @param string $id        html id
     * @param bool   $required  Specifies that the user is required to select a value before submitting the form
     * @param array  $options   array of string. Each element is an selectable element of select
     */
    public function __construct(string $id, bool $required, array $options)
    {
        $this->id = $id;
        $this->required = $required;
        $this->options = $options;
    }


    /*********************************************************************************************************
     * metodi specifici di select-box
     ********************************************************************************************************/

    /**
     * Set autofocus
     *
     * @return $this
     */
    public function setAutofocus()
    {
        $this->autofocus = true;
        return $this;
    }

    /**
     * Get if select has got autofocus set
     *
     * @return bool
     */
    public function getAutofocus(): bool
    {
        return $this->autofocus;
    }

    /**
     * Set select disabled
     *
     * @return $this
     */
    public function setDisabled()
    {
        $this->disabled = true;
        return $this;
    }

    /**
     * Set Select as multiple selectable and set html size property equal to 4
     *
     * @return $this
     */
    public function setMultiple()
    {
        $this->multiple = true;
        $this->size = 4;
        return $this;
    }

    /**
     * Set the visible option for select
     *
     * @param int $size number of visible values
     *
     * @return $this
     */
    public function setSize(int $size)
    {
        $this->size = $size;
        return $this;
    }

    /*********************************************************************************************************
     * metodi dell'interfaccia IFormElements
     ********************************************************************************************************/

    /**
     * Get name for this
     *
     * @return string
     */
    public function getElementType(): string
    {
        return 'select';
    }

    /**
     * Check if select box options are valid
     *
     * @return bool
     */
    public function check(): bool
    {

        // check se valore di default esiste nell'elenco dei valori
        if (($this->defaultValue != 0) &&
            !array_key_exists($this->defaultValue, $this->options)
        ) {
            $this->addLog("id {$this->id}: Valore di default non trovato.");
        }


        return $this->getCheckEsito();
    }

    /**
     * make html code for Select box
     *
     * @return string
     */
    public function make(): string
    {
        $html = '';

        if (!$this->TErrorChecked) {
            $this->check();
        }

        if ($this->TErrorValid) {
            $html .= "<div class='input-group col-xs-{$this->col2}'><select size='{$this->size}' name='{$this->id}' ";
            if ($this->required) {
                $html .= 'required="required" ';
            }
            if ($this->autofocus) {
                $html .= 'autofocus="autofocus" ';
            }
            if ($this->disabled) {
                $html .= 'disabled="disabled" ';
            }
            if ($this->multiple) {
                $html .= 'multiple="multiple" ';
            }
            if ($this->size) {
                $html .= "size='{$this->size}' ";
            }

            // imposta classe di bootstrap + quella eventuale impostata dall'utente
            $this->setClass("form-control {$this->class} ");

            $html .= $this->getGlobalAttributes();

            // riga vuota, obbliga la selezione
            $html .= "><option value='0'>{$this->defaultText}</option>";


            foreach ($this->options as $key => $option) {
                if ($key == 0) {
                    $key = '';
                }
                $html .= "<option value='{$key}'";
                if ($key == $this->defaultValue) {
                    $html .= ' selected="selected" ';
                }
                $html .= ">$option</option>";
            }
            $html .= "</select></div>";
        }
        return $html;
    }
}
