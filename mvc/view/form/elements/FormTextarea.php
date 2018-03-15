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
 * Allow use of html Textarea tag inside html form
 *
 * @package qFW\mvc\view\form\elements
 */
class FormTextarea implements IFormElements
{
    // specific to textarea
    /** @var bool  hold autofocus property*/
    private $autofocus = false;

    /** @var bool  hold disabled property */
    private $disabled = false;

    /** @var int  hold max lenght property*/
    private $maxLength = 0;

    /** @var bool  hold read only property*/
    private $readonly = false;

    /** @var int  hold number of column property*/
    private $rows = 4;

    use TError;
    use TFormObj;
    use TBootstrap;
    use TGlobalAttributes;

    /**
     * FormTextarea constructor.
     *
     * @param string $id        user id
     * @param bool   $required  true if required in form validation
     */
    public function __construct(string $id, bool $required)
    {
        $this->id = $id;
        $this->required = $required;
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
     * Get if this html element has got autofocus
     *
     * @return bool
     */
    public function getAutofocus(): bool
    {
        return $this->autofocus;
    }

    /**
     * Set diables
     *
     * @return $this
     */
    public function setDisabled()
    {
        $this->disabled = true;
        return $this;
    }

    /**
     * Specifies the maximum number of characters allowed in the text area
     *
     * @param int $val  maximum number of characters allowed
     *
     * @return $this
     */
    public function setMaxLength(int $val)
    {
        $this->maxLength = $val;
        return $this;
    }

    /**
     * Specifies that a text area should be read-only
     *
     * @return $this
     */
    public function setReadonly()
    {
        $this->readonly = true;
        return $this;
    }


    /*********************************************************************************************************
     * metodi dell'interfaccia IFormElements
     ********************************************************************************************************/

    /**
     * Get element type it is a costant used to build the form
     *
     * @return string
     */
    public function getElementType(): string
    {
        return 'textarea';
    }

    /**
     * Check if this form element has got some errors
     *
     * @return bool
     */
    public function check(): bool
    {
        if ($this->rows == 0) {
            $this->addLog("id {$this->id}: Textarea ha impostato 0 righe visibili.");
        }

        if (!is_numeric($this->maxLength)) {
            $this->addLog("id {$this->id}: proprietà maxLength impostata di tipo non numerico.");
        }

        return $this->getCheckEsito();
    }

    /**
     * Make html for this form element
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
            $html .= "<textarea name='{$this->id}' rows='{$this->rows}' ";
            if ($this->required) {
                $html .= 'required="required" ';
            }
            if ($this->autofocus) {
                $html .= 'autofocus="autofocus" ';
            }
            if ($this->disabled) {
                $html .= 'disabled="disabled" ';
            }
            if ($this->maxLength) {
                $html .= "maxlength='{$this->maxLength}' ";
            }
            if ($this->readonly) {
                $html .= 'readonly="readonly" ';
            }

            // imposta classe di bootstrap + quella eventuale impostata dall'utente
            $this->setClass("form-control col-xs-{$this->col2} {$this->class} ");

            $html .= "{$this->getGlobalAttributes()}>";
            if ($this->defaultText) {
                $html .= $this->defaultText;
            }
            $html .= '</textarea>';
        }
        return $html;
    }
}
