<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 */
declare(strict_types=1);

namespace qFW\mvc\view\pages\admin;

use qFW\mvc\view\pages\elements\PageEnd;
use qFW\mvc\view\pages\HtmlDoc;
use qFW\mvc\view\pages\elements\PageStart;
use qFW\mvc\view\template\content\ITplLogin;

/**
 * Class LoginPage
 *
 * @package qFW\mvc\view\pages\admin
 */
final class LoginPage extends HtmlDoc
{
    /** @var string Page content */
    private $content = '';

    /**
     * LoginPage constructor.
     *
     * @param string $title
     * @param string $skinPath default ''
     */
    public function __construct(string $title, string $skinPath = '')
    {
        if ($skinPath != '') {
            $skin = "<link rel='stylesheet' type='text/css' href='$skinPath' />";
        } else {
            $skin = '';
        }

        parent::__construct(new PageStart($title, $skin), new PageEnd());

        $this->setStaticOrigin('/static');
    }

    /**
     * Make body
     *
     * @return string
     */
    protected function makeBody(): string
    {
        $html = "
        <!-- start: container -->
        <div id='containerLogin'>
            {$this->content}
        </div>
        <!-- stop: container -->
        ";
        return $html;
    }

    /**
     * Set content
     *
     * @param \qFW\mvc\view\template\content\ITplLogin $tmpl
     *
     * @return $this
     */
    public function setContent(ITplLogin $tmpl)
    {
        $this->content = $tmpl->getHtml();
        return $this;
    }

    /**
     * End Js
     *
     * @return string
     */
    protected function endJs(): string
    {

        // JQUERY & BOOTSTRAP
        //jQuery (mandatory for Bootstrap\'s JavaScript plugins)
        return "<script src=\"STATIC_ORIGIN/js/jquery.1.12.4.min.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/jquery-ui.min.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/bootstrap.3.3.7.min.js\"></script>"

            // Tokenfield
            . "<script src=\"STATIC_ORIGIN/js/bootstrap-tokenfield.min.js\"></script>"
            //."<script src=\"STATIC_ORIGIN/panel/js/typeahead.bundle.min.js\"></script> $t"
            . "<script src=\"STATIC_ORIGIN/js/scrollspy.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/typeahead.bundle.min.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/docs.min.js \"></script>";
    }
}
