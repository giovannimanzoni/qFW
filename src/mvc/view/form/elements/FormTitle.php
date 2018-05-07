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
 * Allow use of html Title tag inside html form
 *
 * @package qFW\mvc\view\form\elements
 */
class FormTitle implements IFormElements
{
    /** @var string Title value */
    private $title = '';

    /** @var int Dimension of title */
    private $dimension = 1;

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    use TError;
    use TGlobalAttributes;

    /**
     * FormTitle constructor.
     *
     * @param string $title
     * @param int    $dimension
     */
    public function __construct(string $title, int $dimension)
    {
        $this->title = $title;
        $this->dimension = $dimension;
        $this->voc = new Voc();
    }

    /*********************************************************************************************************
     *  Methods defined in the interface but not used @codingStandardsIgnoreStart
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
     * Optional methods
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

    /**
     * Check if this form element has got some errors
     *
     * @return bool : outcome
     */
    public function check(): bool
    {

        if (($this->dimension < 1) || ($this->dimension > 6)) {
            $this->addLog("'{$this->title}': _VOC_ : {$this->dimension}", $this->voc->formTitleOutOfRange());
        } else {
            /*Ok*/
        }
        if ($this->title == '') {
            $this->addLog('_VOC_', $this->voc->formTitleEmpty());
        } else {
            /*Ok*/
        }

        return $this->getCheckOutcome();
    }


    /**
     * Make html for this form element
     *
     * @return string   : html
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
            $html = "<h{$this->dimension} {$this->getGlobalAttributes()}>{$this->title}</h{$this->dimension}>";
        } else {
            /*Ok*/
        }
        return $html;
    }
}
