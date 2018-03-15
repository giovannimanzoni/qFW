<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\view\widget\buttons;

use qFW\mvc\controller\url\Url;
use qFW\mvc\view\pages\IHtml;
use qFW\mvc\view\template\content\CHtml;

/**
 * Class Button
 *
 * Make page buttons
 *
 * @package qFW\mvc\view\widget\buttons
 */
class Buttons1 extends CHtml implements IHtml
{
    /** @var array hold buttons */
    private $buttons=array();

    /**
     * Button constructor.
     */
    public function __construct()
    {
    }

    /**
     * Add button
     *
     * @param string $link
     * @param string $fa
     * @param string $text
     *
     * @return $this
     */
    public function addButton(string $link, string $fa, string $text)
    {
        $this->buttons[]=array('link' => $link, 'fas' =>$fa, 'text' => $text);
        return $this;
    }

    /**
     * Get html
     *
     * @return string
     */
    public function getHtml(): string
    {
        $html='';
        foreach($this->buttons as $btn){
            $link=Url::makeUrl($btn['link']);

            $html.=
                 "
                 <div class='col-xs-6 col-sm-4 col-md-3 col-lg-2'>
                 <a href='$link' class='quick-button '>
                    <i class='buttons-icon fas {$btn['fas']} fa-3x'></i>
                    <p>{$btn['text']}</p>
                 </a>
                 </div>";
        }

        return $html;
    }
}