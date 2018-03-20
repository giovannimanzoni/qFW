<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\pages\elements;

use qFW\mvc\view\pages\IHtml;

/**
 * Class PageEnd
 *
 * @package qFW\mvc\view\pages\elements
 */
class PageEnd implements IHtml
{
    /** @var string hold delimiter */
    private $del = '';

    /**
     * PageEnd constructor.
     *
     * @param string $del
     */
    public function __construct(string $del = "\n")
    {
        $this->del = $del;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->endPanelJs();
    }

    /**
     * @return string
     */
    private function endPanelJs(): string
    {
        $html = "<script defer src='https://use.fontawesome.com/releases/v5.0.6/js/all.js'></script> {$this->del}";

        return $html;
    }
}
