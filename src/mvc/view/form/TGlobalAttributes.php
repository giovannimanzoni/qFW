<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\form;

use qFW\mvc\controller\lang\ILang;

/**
 * Trait TGlobalAttributes
 *
 * Manage Global Attributes for html elements
 *
 * @package qFW\mvc\view\form
 */
trait TGlobalAttributes
{

    // HTML GLOBAL ATTRIBUTES
    private $id = '';
    private $name = '';
    private $accesskey = '';
    private $class = '';
    private $contenteditable = false;
    private $contextmenuId = '';
    private $contextmenuHtml = '';
    private $dataGAName = '';     // Data global attribute name
    private $dataGAValue = '';    // Data global attribute value
    // private dir='';          // Ltr, non abbiamo necessità di impostare la pagina ltr e il campo rtl...
    private $draggable = false;
    //private $dropzone='';    // Still not supported
    private $hidden = false;
    private $langGA = '';
    private $spellcheck = false;
    private $style = '';
    private $tabindex = 0;
    private $title = '';
    // private $translate='';   // No browser supporteds

    /***********************************
     * Settter
     **********************************/

    /**
     * @param string $accKey
     *
     * @return $this
     */
    public function setAccessKey(string $accKey)
    {
        $this->accesskey = $accKey;
        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass(string $class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $style
     *
     * @return $this
     */
    public function setStyle(string $style)
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @return $this
     */
    public function setContentEditable()
    {
        $this->contenteditable = true;
        return $this;
    }

    /**
     * @param string $id
     * @param string $html
     *
     * @return $this
     */
    public function setContextMenu(string $id, string $html)
    {
        $this->contextmenuId = $id;
        $this->contextmenuHtml = $html;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function setDataGA(string $name, string $value)
    {
        $this->dataGAName = $name;
        $this->dataGAValue = $value;
        return $this;
    }

    /**
     * @return $this
     */
    public function setDraggable()
    {
        $this->draggable = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function setHidden()
    {
        $this->hidden = true;
        return $this;
    }

    /**
     * @param \qFW\mvc\controller\lang\ILang $langGA
     *
     * @return $this
     */
    public function setLangGA(ILang $langGA)
    {
        $this->langGA = $langGA;
        return $this;
    }

    /**
     * @return $this
     */
    public function setSpellCheck()
    {
        $this->spellcheck = true;
        return $this;
    }

    /**
     * @param int $index
     *
     * @return $this
     */
    public function setTabIndex(int $index)
    {
        // Need to check this error here because by default the value is 0
        if ($index == 0) {
            $this->addLog("id='{$this->id}': _VOC_", $this->voc->formTabindexNot0());
        } else {
            $this->tabindex = $index;
        }
        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title)
    {
        // Need to check this error here because by default the value is 0
        if ($title == '') {
            $this->addLog("id='{$this->id}': _VOC_", $this->voc->formTabindexNoEmpty());
        } else {
            $this->title = $title;
        }
        return $this;
    }

    /***********************************
     * Getter
     **********************************/

    /**
     * @return string
     */
    public function getAccessKey(): string
    {
        return $this->accesskey;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContextMenuId(): string
    {
        return $this->contextmenuId;
    }

    /**
     * @return string
     */
    public function getContextMenuHtml(): string
    {
        return $this->contextmenuHtml;
    }

    /**
     * @return string
     */
    public function getDataGAName(): string
    {
        return $this->dataGAName;
    }

    /**
     * @return string
     */
    public function getDataGAValue(): string
    {
        return $this->dataGAValue;
    }

    /**
     * @return string
     */
    public function getLangGA(): string
    {
        return $this->langGA;
    }

    /**
     * @return bool
     */
    public function getSpellCheck(): bool
    {
        return $this->spellcheck;
    }

    /**
     * @return bool
     */
    public function getContentEditable(): bool
    {
        return $this->contenteditable;
    }

    /**
     * @return int
     */
    public function getTabIndex(): int
    {
        return $this->tabindex;
    }

    /**
     * @return bool
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @return string
     */
    public function getGlobalAttributes()
    {
        $html = '';

        if ($this->id != '') {
            $html .= "id='{$this->id}' ";
        }
        if ($this->class != '') {
            $html .= "class='{$this->class}' ";
        }
        if ($this->style != '') {
            $html .= "style='{$this->style}' ";
        }
        if ($this->accesskey != '') {
            $html .= "accesskey='{$this->accesskey}' ";
        }
        if ($this->contenteditable) {
            $html .= 'contenteditable="true" ';
        }
        if ($this->contextmenuId != '') {
            $html .= "contextmenu='{$this->contextmenuId}' ";
        }
        if (($this->dataGAName != '') && ($this->dataGAValue != '')) {
            $html .= "data-{$this->dataGAName}='{$this->dataGAValue}'";
        }
        if ($this->draggable) {
            $html .= 'draggable="true" ';
        }
        if ($this->hidden) {
            $html .= 'hidden="hidden" ';
        }
        if ($this->spellcheck) {
            $html .= 'spellcheck="true" ';
        }
        if ($this->tabindex != 0) {
            $html .= "tabindex='{$this->tabindex}' ";
        }
        if ($this->title != '') {
            $html .= "title='{$this->title}' ";
        }

        return $html;
    }
}
