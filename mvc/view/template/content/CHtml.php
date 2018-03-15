<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\view\template\content;

use qFW\mvc\view\pages\IHtml;

/**
 * Class CHtml
 *
 * @package qFW\mvc\view\template\content
 */
abstract class CHtml implements IHtml
{
    /** @var string  */
    protected $html='';

    /**
     * @return string html
     */
    public function getHtml(): string
    {
        return $this->html;
    }
}
