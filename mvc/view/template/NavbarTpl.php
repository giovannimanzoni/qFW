<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\view\template;

/**
 * Class NavbarTpl
 *
 * @package qFW\mvc\view\template
 */
abstract class NavbarTpl
{
    /** @var string delimiter */
    protected $del='';

    /** @var string hold html */
    protected $html='';


    /**
     * NavbarTpl constructor.
     *
     * @param string $del
     */
    public function __construct(string $del="\n")
    {
        $html='
            <!-- top fixed navbar -->
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-content" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">';

        $html.= $this->setLogo();

        $html.='</a></div>';

        //if(!callFromqPanelAdmin()) {
        $html.="<div id='navbar' class='navbar-collapse collapse'>
                <ul class='nav navbar-nav navbar-right'>
<!--
                    <li><a href='#'>Dashboard</a></li>
                    <li><a href='#'>Settings</a></li>
                    <li><a href='#'>Profile</a></li>
                    <li><a href='#'>Help</a></li>
-->";
        //menuAction();
        //menuNotification();
        // menuMessage();
        //menuUser();
        $html.="
                </ul>
            </div>";
        //}
        $html.="</div></nav>";

        $this->html=$html;
    }

    /**
     * Get html for menu block
     *
     * @todo develop this function
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @return string
     */
    abstract protected function setLogo(): string;

}