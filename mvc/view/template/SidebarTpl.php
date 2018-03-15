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

use qFW\mvc\controller\url\Url;

/**
 * Class SidebarTpl
 *
 * @package qFW\mvc\view\template
 */
abstract class SidebarTpl
{
    /** @var string delimiter */
    protected $del='';

    /** @var string hold html */
    protected $html='';

    /** @var int  */
    protected $maxLev1=0;

    /** @var int level of menu of sidebar */
    protected $lev1=0;

    /** @var int level of sub menu of sidebar */
    protected $lev2=0;


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
     * Start Navbar
     *
     * @param int    $lev1
     * @param int    $lev2
     * @param string $del
     */
    protected function startSidebar(int $lev1, int $lev2, string $del="\n")
    {
        $this->del=$del;
        $this->lev1=$lev1;
        $this->lev2=$lev2;
        $this->maxLev1=0;

        $this->html='
        <div class="nav-side-menu">
            <div class="menu-list">
                <ul id="menu-content" class="menu-content collapse out">
        
        ';
    }

    /**
     * Stop navbar
     */
    protected function stopSidebar()
    {
        $this->html .= '
                </ul>
            </div>
        </div>';
    }


    /**
     * Start level1 of the menu
     *
     * @param string $target
     * @param string $fa
     * @param int    $lev1
     * @param string $txt
     *
     * @return string
     */
    protected function startLev1(string $target, string $fa, int $lev1, string $txt, string $url=''): string
    {
        $this->maxLev1++;

        $html= '<li ';
        if($url=='') $html.="data-toggle='collapse' data-target='#$target' class='collapsed ";
        else $html.="class='";

        if($this->lev1==$lev1) $html.='active';
        else {/* inactive*/}

        $html.="'><a ";
        if($url!='') {
            $html.="href='$url'";
        }
        $html.="><span class='menu-icon fas $fa fa-lg'></span></i> $txt";
        if($url=='') $html.='<span class="arrowdownfa"><i class="fas fa-angle-down"></i></span>';
        $html.="</a></li>";

        // start submenu
        if($url=='') {
            $html.= $this->startSubmenu($lev1,$target);
        } else {/* no submenu*/}

        return $html;
    }

    /**
     * @param $lev1
     * @param $target
     *
     * @return string
     */
    private function startSubmenu($lev1,$target): string
    {
        $html = "<ul class=\"sub-menu ";
        if ($this->lev1 != $lev1) {
            $html .= 'collapse';
        }
        $html .= "\" id='$target'>";
        return $html;
    }

    /**
     * Close
     *
     * @return string
     */
    protected function closeLev1(): string
    {
        return '</ul>';
    }

    /**
     * @param int    $lev2
     * @param string $url
     * @param string $txt
     *
     * @return string
     */
    protected function makeLev2(int $lev2, string $url, string $txt): string
    {
        $urlFull=Url::makeUrl($url);
        $html='<li class="';
        if (($this->lev1 == $this->maxLev1) && ($this->lev2 == $lev2)) {
            $html.='active';
        }
        $html.="\"><a href='$urlFull'><span class='arrowfaright'><i class='fas fa-angle-right'></i></span> $txt</a></li>";

        return $html;
    }

}