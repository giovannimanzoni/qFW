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

use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\vocabulary\Voc;
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
    // Specific to textarea
    /** @var bool Hold autofocus property */
    private $autofocus = false;

    /** @var bool  hold disabled property */
    private $disabled = false;

    /** @var int Hold max lenght property */
    private $maxLength = 0;

    /** @var bool Hold read only property */
    private $readonly = false;

    /** @var int Hold number of column property */
    private $rows = 4;

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    use TError;
    use TFormObj;
    use TBootstrap;
    use TGlobalAttributes;

    /**
     * FormTextarea constructor.
     *
     * @param string $id       User id
     * @param bool   $required True if required in form validation
     */
    public function __construct(string $id, bool $required)
    {
        $this->id = $id;
        $this->required = $required;
        $this->voc = new Voc();
    }

    /*********************************************************************************************************
     * Specific methods for select-box
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
     * @param int $val Maximum number of characters allowed
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
     * Methods of IFormElements interface
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
            $this->addLog("id {$this->id}: _VOC_", $this->voc->formTextarea0Rows());
        } else {
            /*Ok*/
        }

        if (!is_numeric($this->maxLength)) {
            $this->addLog("id {$this->id}: _VOC_", $this->voc->formPropertyMaxLenghtErr());
        } else {
            /*Ok*/
        }

        return $this->getCheckOutcome();
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
        } else {
            /*Ok*/
        }

        if ($this->TErrorValid) {
            $html .= "<textarea name='{$this->id}' rows='{$this->rows}' ";
            if ($this->required) {
                $html .= 'required="required" ';
            } else {
                /*Ok*/
            }
            if ($this->autofocus) {
                $html .= 'autofocus="autofocus" ';
            } else {
                /*Ok*/
            }
            if ($this->disabled) {
                $html .= 'disabled="disabled" ';
            } else {
                /*Ok*/
            }
            if ($this->maxLength) {
                $html .= "maxlength='{$this->maxLength}' ";
            } else {
                /*Ok*/
            }
            if ($this->readonly) {
                $html .= 'readonly="readonly" ';
            } else {
                /*Ok*/
            }

            // Set bootstrap class + any one set by the user
            $this->setClass("form-control col-xs-{$this->col2} {$this->class} ");

            $html .= "{$this->getGlobalAttributes()}>";
            if ($this->defaultText) {
                $html .= $this->defaultText;
            } else {
                /*Ok*/
            }
            $html .= '</textarea>';
        }
        return $html;
    }
}
