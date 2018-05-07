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
 * Class FormText
 *
 * Allow use of html text between <span></span> tags inside html form
 *
 * @package qFW\mvc\view\form\elements
 */
class FormText implements IFormElements
{
    /** @var string  text to show */
    private $value = '';

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    use TError;
    use TGlobalAttributes;
    use TBootstrap;

    /**
     * FormText constructor.
     *
     * @param string $value text to show
     */
    public function __construct(string $value)
    {
        $this->value = $value;
        $this->voc = new Voc();
    }

    /*********************************************************************************************************
     * Methods defined in the interface but not used @codingStandardsIgnoreStart
     ********************************************************************************************************/

    public function setDefaultValue(int $defaultValue)
    {
        $this->addLog('setDefaultValue not used for this form element.');
    }

    public function setLabel(string $label)
    {
        $this->addLog('setDefaultValue not used for this form element.');
    }

    public function setLabelOnTop()
    {
        $this->addLog('labelOnTop not used for this form element.');
    }

    public function setDefaultText(string $text)
    {
        $this->addLog('setDefaultText not used for this form element.');
    }

    public function getLabel(): string
    {
        return '';
    }

    public function isHorizontal(): bool
    {
        return true;
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function isRatioSetted(): bool
    {
        return false;
    }

    public function isLabelDisabled(): bool
    {
        return false;
    }
    // @codingStandardsIgnoreEnd

    /*********************************************************************************************************
     * Opzional methods
     ********************************************************************************************************/

    /**
     * Get element type it is a costant used to build the form
     *
     * @return string
     */
    public function getElementType(): string
    {
        return 'text';
    }

    /**
     * Set id for this html element
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
     * Check if this form element has got some errors
     *
     * @return bool
     */
    public function check(): bool
    {
        if ($this->value == '') {
            $this->addLog("id {$this->id}: _VOC_", $this->voc->formEmptyText());
        }

        return $this->getCheckOutcome();
    }

    /**
     * Make html for this form element
     *
     * @return string   html
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
            $html = "<span {$this->getGlobalAttributes()}>{$this->value}</span>";
        } else {
            /*Ok*/
        }
        return $html;
    }
}
