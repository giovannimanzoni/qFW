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

use qFW\mvc\controller\lang\LangEn;
use qFW\mvc\controller\vocabulary\Voc;
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
    // Specific to input
    /** @var bool Hold autofocus property */
    private $autofocus = false;

    /** @var bool Hold disabled property */
    private $disabled = false;

    /** @var bool Hold multiple property */
    private $multiple = false;

    /** @var int Hold size property */
    private $size = 0;

    /** @var int Hold max lenght property */
    private $maxLength = 0;

    // Checkbox
    /** @var bool Hold checked property */
    private $checkedProp = false;

    /** @var string Hold element type */
    private $strategy;

    /** @var string|int Hold placeholder property */
    private $placeholder = '';

    /** @var string|int Hold value property */
    private $value = '';

    /** @var string Hold html to prepend to the code */
    private $prepend = '';

    /** @var string Hold html to append to the code */
    private $append = '';

    /** @var bool Hold read only property */
    private $readonly = false;


    /** @var \qFW\mvc\controller\dataTypes\UtString */
    private $utStr;

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    use TError;
    use TFormObj;
    use TBootstrap;
    use TGlobalAttributes;
    use TGlobalEventAttributes;

    /**
     * FormInput constructor.
     *
     * @param string                                       $id
     * @param \qFW\mvc\view\form\elements\input\IFormInput $strategy
     * @param bool                                         $required
     */
    public function __construct(string $id, IFormInput $strategy, bool $required)
    {
        $this->id = $id;
        $this->name = $id;
        $this->strategy = $strategy->getType();
        $this->required = $required;
        $this->voc = new Voc();
        $this->utStr = new UtString(new LangEn());
    }


    /*********************************************************************************************************
     * Specific Methods for input element
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
     * @param string $name Name
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
     * Methods for IFormElements interfaces
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
        // @todo move to class made differently
        if ($this->checkedProp) {
            if ($this->utStr->areEqual($this->strategy, 'checkbox') ||
                $this->utStr->areEqual($this->strategy, 'radio')
            ) {
                /* ok */
            } else {
                $this->addLog("id {$this->id}: _VOC_", $this->voc->formPropertyCheckedErr());
            }
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
     * @todo Improve, perform checks on options that some objects do not have..
     *       for ex. checkbox has no maxlength nor multiple..
     *
     * @return string
     */
    public function make(): string
    {
        $html = '';

        if (!$this->checkedProp) {
            $this->check();
        } else {
            /*Ok*/
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
            if ($this->utStr->areEqual($this->strategy, 'checkbox') ||
                $this->utStr->areEqual($this->strategy, 'radio')
            ) {
                $html .= "<span class='vertCenterFormText'>{$this->defaultText}</span>";
            } else {
                /*Ok*/
            }

            $html .= '</div>';
        } else {
            /*Ok*/
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
            if ($this->isFaIcon($txt)) {
                $html .= "<i class='$txt fa-lg'></i>";
            } elseif ($this->isGlyphiconIcon($txt)) {
                $html .= "<i class='glyphicon $txt'></i>";
            } else {
                $html .= $txt;
            }
            $html .= '</span>';
        }
        return $html;
    }

    /**
     * Check if given string is a fa icon
     *
     * @param string $txt String to check
     *
     * @return bool
     */
    private function isFaIcon(string $txt): bool
    {
        return $this->utStr->strSearch($txt, ' fa-'); // fas far fab fal...
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
        return $this->utStr->strSearch($txt, 'glyphicon glyphicon-');
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
        if ($this->multiple) {
            $html .= 'multiple="multiple" ';
        } else {
            /*Ok*/
        }
        if ($this->checkedProp) {
            $html .= 'checked="checked" ';
        } else {
            /*Ok*/
        }

        if ($this->placeholder != '') {
            $html .= 'placeholder="' . $this->placeholder . '" ';
        } else {
            /*Ok*/
        }
        $html .= "value='{$this->value}' ";
        if ($this->readonly) {
            $html .= 'readonly="readonly" ';
        } else {
            /*Ok*/
        }
        if ($this->size) {
            $html .= "size='{$this->size}' ";
        } else {
            /*Ok*/
        }
        if ($this->maxLength) {
            $html .= "maxlength='{$this->maxLength}' ";
        } else {
            /*Ok*/
        }

        $classes = "{$this->class} ";
        if ($this->utStr->areEqual($this->strategy, 'checkbox') ||
            $this->utStr->areEqual($this->strategy, 'radio')
        ) {
            /* ok nothing to do*/
        } else {
            $classes .= 'form-control ';
        }
        if ($classes != '') {
            $this->setClass($classes);
        } else {
            /*Ok*/
        }

        $html .= $this->getGlobalAttributes();
        return $html;
    }
}
