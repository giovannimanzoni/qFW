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
 * Trait TGlobalEventAttributes
 *
 * Manage Form Global Events
 *
 * @package qFW\mvc\view\form\elements
 */
trait TGlobalEventAttributes
{
    // Global Event Attributes

    // Window Event Attributes

    // Form Events
    private $FormEventOnBlur = '';
    private $FormEventOnChange = '';
    private $FormEventOnContextMenu = '';
    private $FormEventOnFocus = '';
    private $FormEventOnInput = '';
    private $FormEventOnInvalid = '';
    private $FormEventOnReset = '';
    private $FormEventOnSearch = '';
    private $FormEventOnSelect = '';
    private $FormEventOnSubmit = '';

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnSubmit(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnSubmit _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnSubmit = $script;
        }

        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnBlur(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnBlur _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnBlur = $script;
        }

        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnChange(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnChange _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnChange = $script;
        }

        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnContextMenu(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnContextMenu _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnContextMenu = $script;
        }
        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnFocus(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnFocus _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnFocus = $script;
        }
        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnInput(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnInput _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnInput = $script;
        }
        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnInvalid(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnInvalid _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnInvalid = $script;
        }
        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnReset(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnReset _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnReset = $script;
        }
        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnSearch(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnSearch _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnSearch = $script;
        }
        return $this;
    }

    /**
     * @param string $script
     *
     * @return $this
     */
    public function setOnSelect(string $script)
    {
        if ($script == '') {
            $this->addLog("setOnSelect _VOC_", $this->voc->formSetWithoutScript());
        } else {
            $this->FormEventOnSelect = $script;
        }
        return $this;
    }


    // Keyboard Events

    // Mouse Events

    // Drag Events

    // Clipboard Events

    // Media Events

    // Misc Events


    /****************************************
     *
     *      get html
     *
     ****************************************/

    /**
     * @return string
     */
    public function getGlobalEventAttributesHtml(): string
    {
        $html = '';

        if ($this->FormEventOnBlur != '') {
            $html .= "onblur='{$this->FormEventOnBlur}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnChange != '') {
            $html .= "onchenge='{$this->FormEventOnChange}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnContextMenu != '') {
            $html .= "oncontextmenu='{$this->FormEventOnContextMenu}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnFocus != '') {
            $html .= "onfocus='{$this->FormEventOnFocus}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnInput != '') {
            $html .= "oninput='{$this->FormEventOnInput}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnInvalid != '') {
            $html .= "oninvalid='{$this->FormEventOnInvalid}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnReset != '') {
            $html .= "onreset='{$this->FormEventOnReset}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnSearch != '') {
            $html .= "onsearch='{$this->FormEventOnSearch}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnSelect != '') {
            $html .= "onselect='{$this->FormEventOnSelect}' ";
        } else {
            /*Ok*/
        }
        if ($this->FormEventOnSubmit != '') {
            $html .= "onsubmit='{$this->FormEventOnSubmit}' ";
        } else {
            /*Ok*/
        }

        return $html;
    }
}
