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

/**
 * Class SimpleTpl
 *
 * @package qFW\mvc\view\template\content
 */
class SimpleTpl extends CHtml implements ITplContent
{
    /**
     * SimpleTpl constructor.
     *
     * @param string $html
     */
    public function __construct(string $html)
    {
        $this->html=$html;
    }
}
