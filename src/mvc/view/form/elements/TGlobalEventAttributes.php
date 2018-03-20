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

    public function setOnSubmit(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnSubmit senza script');
        }
        $this->FormEventOnSubmit = $script;
        return $this;
    }

    public function setOnBlur(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnBlur senza script');
        }
        $this->FormEventOnBlur = $script;
        return $this;
    }

    public function setOnChange(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnChange senza script');
        }
        $this->FormEventOnChange = $script;
        return $this;
    }

    public function setOnContextMenu(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnContextMenu senza script');
        }
        $this->FormEventOnContextMenu = $script;
        return $this;
    }

    public function setOnFocus(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnFocus senza script');
        }
        $this->FormEventOnFocus = $script;
        return $this;
    }

    public function setOnInput(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnInput senza script');
        }
        $this->FormEventOnInput = $script;
        return $this;
    }

    public function setOnInvalid(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnInvalid senza script');
        }
        $this->FormEventOnInvalid = $script;
        return $this;
    }

    public function setOnReset(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnReset senza script');
        }
        $this->FormEventOnReset = $script;
        return $this;
    }

    public function setOnSearch(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnSearch senza script');
        }
        $this->FormEventOnSearch = $script;
        return $this;
    }

    public function setOnSelect(string $script)
    {
        if ($script == '') {
            $this->addLog('Impostato setOnSelect senza script');
        }
        $this->FormEventOnSelect = $script;
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
    public function getGlobalEventAttributesHtml(): string
    {
        $html = '';

        if ($this->FormEventOnBlur != '') {
            $html .= "onblur='{$this->FormEventOnBlur}' ";
        }
        if ($this->FormEventOnChange != '') {
            $html .= "onchenge='{$this->FormEventOnChange}' ";
        }
        if ($this->FormEventOnContextMenu != '') {
            $html .= "oncontextmenu='{$this->FormEventOnContextMenu}' ";
        }
        if ($this->FormEventOnFocus != '') {
            $html .= "onfocus='{$this->FormEventOnFocus}' ";
        }
        if ($this->FormEventOnInput != '') {
            $html .= "oninput='{$this->FormEventOnInput}' ";
        }
        if ($this->FormEventOnInvalid != '') {
            $html .= "oninvalid='{$this->FormEventOnInvalid}' ";
        }
        if ($this->FormEventOnReset != '') {
            $html .= "onreset='{$this->FormEventOnReset}' ";
        }
        if ($this->FormEventOnSearch != '') {
            $html .= "onsearch='{$this->FormEventOnSearch}' ";
        }
        if ($this->FormEventOnSelect != '') {
            $html .= "onselect='{$this->FormEventOnSelect}' ";
        }
        if ($this->FormEventOnSubmit != '') {
            $html .= "onsubmit='{$this->FormEventOnSubmit}' ";
        }

        return $html;
    }
}
