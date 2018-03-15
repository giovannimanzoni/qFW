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
    private $dataGAName = '';     // data global attribute name
    private $dataGAValue = '';    // data global attribute value
    // private dir='';          // ltr, non abbiamo necessità di impostare la pagina ltr e il campo rtl...
    private $draggable = false;
    //private $dropzone='';    // ancora non supportato
    private $hidden = false;
    private $lang = '';
    private $spellcheck = false;
    private $style = '';
    private $tabindex = 0;
    private $title = '';
    // private $translate='';   // supportato da nessun browser

    /***********************************
     * set
     **********************************/

    public function setAccessKey(string $accKey)
    {
        $this->accesskey = $accKey;
        return $this;
    }

    public function setClass(string $class)
    {
        $this->class = $class;
        return $this;
    }

    public function setStyle(string $style)
    {
        $this->style = $style;
        return $this;
    }

    public function setContentEditable()
    {
        $this->contenteditable = true;
        return $this;
    }

    public function setContextMenu(string $id, string $html)
    {
        $this->contextmenuId = $id;
        $this->contextmenuHtml = $html;
        return $this;
    }

    public function setDataGA(string $name, string $value)
    {
        $this->dataGAName = $name;
        $this->dataGAValue = $value;
        return $this;
    }

    public function setDraggable()
    {
        $this->draggable = true;
        return $this;
    }

    public function setHidden()
    {
        $this->hidden = true;
        return $this;
    }

    public function setLang(string $lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function setSpellCheck()
    {
        $this->spellcheck = true;
        return $this;
    }

    public function setTabIndex(int $index)
    {
        // necessario controllare qui questo errore perchè di default il valore è 0
        if ($index == 0) {
            $this->addLog("id='{$this->id}': Valore di tabindex non può essere 0");
        } else {
            $this->tabindex = $index;
        }
        return $this;
    }

    public function setTitle(string $title)
    {
        // necessario controllare qui questo errore perchè di default il valore è 0
        if ($title == '') {
            $this->addLog("id='{$this->id}': Valore di title non può essere vuoto");
        } else {
            $this->title = $title;
        }
        return $this;
    }

    /***********************************
     * get
     **********************************/

    public function getAccessKey(): string
    {
        return $this->accesskey;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContextMenuId(): string
    {
        return $this->contextmenuId;
    }

    public function getContextMenuHtml(): string
    {
        return $this->contextmenuHtml;
    }

    public function getDataGAName(): string
    {
        return $this->dataGAName;
    }

    public function getDataGAValue(): string
    {
        return $this->dataGAValue;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getSpellCheck(): bool
    {
        return $this->spellcheck;
    }

    public function getContentEditable(): bool
    {
        return $this->contenteditable;
    }

    public function getTabIndex(): int
    {
        return $this->tabindex;
    }

    public function getHidden(): bool
    {
        return $this->hidden;
    }

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
