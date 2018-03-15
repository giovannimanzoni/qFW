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

use qFW\mvc\view\form\elements\input\IFormInput;
use qFW\mvc\view\form\TError;
use qFW\mvc\view\form\TGlobalAttributes;
use qFW\mvc\controller\dataTypes\UtString;

/**
 * Class FormInput
 *
 *
 *
 * @package qFW\mvc\view\form\elements
 */
class FormInput implements IFormElements
{
    // specific to input
    /** @var bool  hold autofocus property*/
    private $autofocus = false;

    /** @var bool  hold disabled property*/
    private $disabled = false;

    /** @var bool  hold multiple property*/
    private $multiple = false;

    /** @var int  hold size property*/
    private $size = 0;

    /** @var int  hold max lenght property*/
    private $maxLength = 0;

    //checkbox
    /** @var bool  hold checked property*/
    private $checkedProp = false;

    /** @var string  hold element type*/
    private $strategy;

    /** @var string|int  hold placeholder property*/
    private $placeholder = '';

    /** @var string|int  hold value property*/
    private $value = '';

    /** @var string  hold html to prepend to the code*/
    private $prepend = '';

    /** @var string  hold html to append to the code*/
    private $append = '';

    /** @var bool  hold read only property*/
    private $readonly = false;

    use TError;
    use TFormObj;
    use TBootstrap;
    use TGlobalAttributes;
    use TGlobalEventAttributes;

    /**
     * FormInput constructor.
     *
     * @param string                                                $id         html id
     * @param \qFW\mvc\view\form\elements\input\IFormInput $strategy   element type
     * @param bool                                                  $required   true if it is required
     */
    public function __construct(string $id, IFormInput $strategy, bool $required)
    {
        $this->id = $id;
        $this->name = $id;
        $this->strategy = $strategy->getType();
        $this->required = $required;
    }


    /*********************************************************************************************************
     * metodi specifici di input
     ********************************************************************************************************/
    /**
     * Set placeholder
     *
     * @param $text
     *
     * @return $this
     */
    public function setPlaceholder($text)
    {
        $this->placeholder = $text;
        return $this;
    }

    /**
     * Set value
     *
     * @param $text
     *
     * @return $this
     */
    public function setValue($text)
    {
        $this->value = $text;
        return $this;
    }

    /**
     * Set html code to prepend to this input element
     *
     * @param string $prepend
     *
     * @return $this
     */
    public function setPrepend(string $prepend)
    {
        $this->prepend = $prepend;
        return $this;
    }

    /**
     * Set html code to append to this input element
     *
     * @param string $append
     *
     * @return $this
     */
    public function setAppend(string $append)
    {
        $this->append = $append;
        return $this;
    }

    /**
     * Set this element read only
     *
     * @return $this
     */
    public function setReadonly()
    {
        $this->readonly = true;
        return $this;
    }

    /**
     * Set autofocus for this element
     *
     * @return $this
     */
    public function setAutofocus()
    {
        $this->autofocus = true;
        return $this;
    }

    /**
     * Get if this element has got autofocus
     *
     * @return bool
     */
    public function getAutofocus(): bool
    {
        return $this->autofocus;
    }

    /**
     * Set this elment disabled
     *
     * @return $this
     */
    public function setDisabled()
    {
        $this->disabled = true;
        return $this;
    }

    /**
     * Set size
     *
     * @param int $size size
     *
     * @return $this
     */
    public function setSize(int $size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Set max lenght
     *
     * @param int $val
     *
     * @return $this
     */
    public function setMaxLength(int $val)
    {
        $this->maxLength = $val;
        return $this;
    }

    /**
     * Set html name for this element
     *
     * @param string $name  name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set cheched for checkbox
     *
     * @param int $val
     *
     * @return $this
     */
    public function setCheckedVal(int $val)
    {
        if ($val == 0) {
            $this->checkedProp = false;
        } else {
            $this->checkedProp = true;
        }
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
        return $this->strategy;
    }

    /**
     * Check if this form element has got some errors
     *
     * @return bool
     */
    public function check(): bool
    {

        // @todo spostare in classe fatta diversamente
        if ($this->checkedProp ) {

            if (
                UtString::areEqual($this->strategy, 'checkbox') ||
                UtString::areEqual($this->strategy, 'radio')
            ) {
                /* ok */
            } else {
                $this->addLog("id {$this->id}: proprietà checked impostata su elemento che non lo ammette.");
            }
        }
        if (!is_numeric($this->maxLength)) {
            $this->addLog("id {$this->id}: proprietà maxLength impostata di tipo non numerico.");
        }

        return $this->getCheckEsito();
    }


    /**
     * Make html for this form element
     *
     * @todo migliorare, esegue controlli su opzioni che alcuni titpi di oggetti non hanno..
     *       per es. checkbox non ha maxlength ne multiple..
     *
     * @return string
     */
    public function make(): string
    {
        $html = '';

        if (!$this->checkedProp) {
            $this->check();
        }

        if ($this->TErrorValid) {
            $html .= "<div class='input-group col-xs-{$this->col2}' >";

            $html .= $this->checkAddPrependAppend($this->prepend);
            $html .= "<input type='{$this->strategy}' name='{$this->name}' ";

            $html .= $this->addInputHtml();

            $html .= '>';
            $html .= $this->checkAddPrependAppend($this->append);

            // se checkbox aggiunge testo
            // @todo: if (isCheckBox($this))
            if (UtString::areEqual($this->strategy, 'checkbox') ||
                UtString::areEqual($this->strategy, 'radio')
            ) {
                $html .= "<span class='vertCenterFormText'>{$this->defaultText}</span>";
            }

            $html .= '</div>';
        }
        return $html;
    }

    /**
     * Check if html code for prepend or append is needed
     *
     * @param string $txt
     *
     * @return string
     */
    private function checkAddPrependAppend(string $txt): string
    {
        $html = '';
        if ($txt != '') {
            $html .= "<span class='input-group-addon'>";
            if ($this->isFaIcon($this->prepend)) {
                $html .= "<i class='{$this->prepend} fa-lg'></i>";
            } else {
                if ($this->isGlyphiconIcon($this->prepend)) {
                    $html .= "<i class=glyphicon '{$this->prepend}' ></i>";
                } else {
                    $html .= $this->prepend;
                }
            }
            $html .= '</span>';
        }
        return $html;
    }

    /**
     * Check if given string is a fa icon
     *
     * @param string $txt   string to check
     *
     * @return bool
     */
    private function isFaIcon(string $txt): bool
    {
        return UtString::strSearch($txt, ' fa-');
    }

    /**
     * Check if given string is a Glyphicon Icon
     *
     * @param string $txt
     *
     * @return bool
     */
    private function isGlyphiconIcon(string $txt): bool
    {
        return UtString::strSearch($txt, 'glyphicon-');
    }

    /**
     * Add html code for this input html tag
     *
     * @return string
     */
    private function addInputHtml(): string
    {
        $html = '';
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
        if ($this->checkedProp) {
            $html .= 'checked="checked" ';
        }

        if ($this->placeholder != '') {
            $html .= 'placeholder="' . $this->placeholder . '" ';
        }
        $html .= "value='{$this->value}' ";
        if ($this->readonly) {
            $html .= 'readonly="readonly" ';
        }
        if ($this->size) {
            $html .= "size='{$this->size}' ";
        }
        if ($this->maxLength) {
            $html .= "maxlength='{$this->maxLength}' ";
        }

        $classes = "{$this->class} ";
        if (UtString::areEqual($this->strategy, 'checkbox') ||
            UtString::areEqual($this->strategy, 'radio')
        ) {
            /* ok nothing to do*/
        } else {
            $classes .= 'form-control ';
        }
        if ($classes != '') {
            $this->setClass($classes);
        }

        $html .= $this->getGlobalAttributes();
        return $html;
    }
}
