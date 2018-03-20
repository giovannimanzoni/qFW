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
 * Class FormText
 *
 * Allow use of html text between <span></span> tags inside html form
 *
 * @package qFW\mvc\view\form\elements
 */
class FormText implements IFormElements
{
    /** @var string  text to show*/
    private $value='';

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
    }

    /*********************************************************************************************************
     * metodi definiti nell'interfaccia ma non usati @codingStandardsIgnoreStart
     ********************************************************************************************************/

    public function setDefaultValue(int $defaultValue)
    {
        $this->addLog('setDefaultValue non ha senso per l\'oggetto FormText.');
    }

    public function setLabel(string $label)
    {
        $this->addLog('setDefaultValue non ha senso per l\'oggetto FormText.');
    }

    public function setLabelOnTop()
    {
        $this->addLog('labelOnTop non ha senso per l\'oggetto FormText.');
    }

    public function setDefaultText(string $text)
    {
        $this->addLog('setDefaultText non ha senso per l\'oggetto FormText.');
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
     * metodi opzionali
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
            $this->addLog('Testo vuoto');
        }

        return $this->getCheckEsito();
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
        }

        if ($this->TErrorValid) {
            $html = "<span {$this->getGlobalAttributes()}>{$this->value}</span>";
        }
        return $html;
    }
}
