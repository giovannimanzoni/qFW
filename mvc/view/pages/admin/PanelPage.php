<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\view\pages\admin;

use qFW\mvc\view\pages\elements\IContent;
use qFW\mvc\view\pages\elements\INavbar;
use qFW\mvc\view\pages\elements\PageEnd;
use qFW\mvc\view\pages\elements\PageStart;
use qFW\mvc\view\pages\elements\Sidebar;
use qFW\mvc\view\template\ITplSidebar;

/**
 * Class StartHtmlDoc
 *
 *
 *
 * @package qFW\mvc\view\pages\admin
 */
final class PanelPage extends PanelHtmlDoc
{

    /**
     * PanelPage constructor.
     *
     * @param string $title
     * @param string $del
     * @param string $skinPath
     */
    public function __construct(string $title, string $del = "\n", string $skinPath)
    {
        $this->title=$title;
        $this->del=$del;

        $skin="<link rel='stylesheet' type='text/css' href='$skinPath' />";
        parent::__construct(new PageStart($title,$del, $skin),new PageEnd());

    }


    /**
     * Set navbar
     *
     * @param \qFW\mvc\view\pages\elements\INavbar $navbar
     *
     * @return $this|mixed
     */
    public function setNavbar(INavbar $navbar)
    {
        $this->navbar = $navbar->getHtml();
        return $this;
    }

    /**
     * @param \qFW\mvc\view\template\ITplSidebar $sidebarTpl
     *
     * @return $this|mixed
     */
    public function setSidebar(ITplSidebar $sidebarTpl)
    {
        $this->sidebar.=(new Sidebar($sidebarTpl))->getHtml();
        return $this;
    }

    /**
     * @param \qFW\mvc\view\pages\elements\IContent $content
     *
     * @return $this
     */
    public function setContent(IContent $content)
    {
        $this->content.=
            $content->getBreadCrumb();
        if($_SESSION['err']!=''){
            $this->content.="<div class='alert alert-danger'><strong>Attenzione! </strong> {$_SESSION['err']}.</div>";
        }
        if($_SESSION['mex']!=''){
            $this->content.="<div class='alert alert-success'><strong>Bene! </strong> {$_SESSION['mex']}.</div>";
        }
        $this->content.=
            "<h1>$this->title</h1>".
            $content->getHtml();
        return $this;
    }

    /**
     * @param string $html
     *
     * @return $this
     */
    public function addCustomJs(string $html)
    {
        $this->addEndJs($html);
        return $this;
    }
}
