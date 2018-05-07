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

use qFW\mvc\view\pages\HtmlDoc;
use qFW\mvc\view\pages\elements\IContent;
use qFW\mvc\view\pages\elements\INavbar;
use qFW\mvc\view\template\IFooter;
use qFW\mvc\view\template\ITplSidebar;

/**
 * Class PanelHtmlDoc
 *
 * @package qFW\mvc\view\pages\admin
 */
abstract class PanelHtmlDoc extends HtmlDoc
{
    /** @var string Hold page content */
    protected $content = '';

    /** @var string Hold sidebar content */
    protected $sidebar = '';

    /** @var string Hold navbar content */
    protected $navbar = '';

    /** @var string Title of the page */
    protected $title = '';

    /** @var string Html for footer*/
    protected $footer='';

    /**
     * Clean session messages
     */
    public function __destruct()
    {
        $_SESSION['err'] = '';
        $_SESSION['mex'] = '';
    }

    /**
     * SetNavbar
     *
     * @param \qFW\mvc\view\pages\elements\INavbar $navBAR
     *
     * @return mixed
     */
    abstract protected function setNavbar(INavbar $navBAR);

    /**
     * Setup Sidebar
     *
     * @param \qFW\mvc\view\template\ITplSidebar $sidebarTpl
     *
     * @return mixed
     */
    abstract protected function setSidebar(ITplSidebar $sidebarTpl);

    /**
     * Setup Content
     *
     * @param \qFW\mvc\view\pages\elements\IContent $content
     *
     * @return mixed
     */
    abstract protected function setContent(IContent $content);

    /**
     * @param \qFW\mvc\view\template\IFooter $footer
     *
     * @return mixed
     */
    abstract protected function setFooter(IFooter $footer);

    /**
     * @return string
     */
    protected function makeBody(): string
    {

        $html = "
        <!-- start: top fixed navbar -->
        {$this->navbar}
        <!-- stop: top fixed navbar -->
        
        <!-- start: container -->
        <div id='containerSxDx'>            
            <div class='row'>
                <!-- start: QSidebar -->
                <div class='col-sm-3 col-md-3 col-lg-2 sidebar'>
                    {$this->sidebar}
                </div>
                <!-- stop: QSidebar -->
                            
                <!-- start: Check JS -->
                <noscript>
                    <div class='alert alert-block col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main'>
                        <h4 class='alert-heading'>Warning!</h4>
                        <p>You need to have <a href='http://en.wikipedia.org/wiki/JavaScript' target='_blank'>
                        JavaScript</a> enabled to use this site.</p>
                    </div>
                </noscript> 
                <!-- stop: Check JS -->
                  
                <!-- start: Pagina dx -->
			    <div class='col-sm-offset-3 col-md-9 col-md-offset-3 col-lg-10 col-lg-offset-2 ' id='pagCont'>
			        {$this->content}
    			</div>
                <!-- stop: Pagina dx -->
			
            </div><!-- row -->
        </div>
        <!-- stop: container -->
        
        <footer>{$this->footer}</footer>
        <div id='footerDx'></div>";

        return $html;
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
        // @codingStandardsIgnoreStart
        return "<script src=\"STATIC_ORIGIN/js/jquery.1.12.4.min.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/jquery-ui.min.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/bootstrap.3.3.7.min.js\"></script>"

            //tokenfield
            . "<script src=\"STATIC_ORIGIN/js/bootstrap-tokenfield.min.js\"></script>"
            //."<script src=\"STATIC_ORIGIN/js/typeahead.bundle.min.js\"></script> $t"
            . "<script src=\"STATIC_ORIGIN/js/scrollspy.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/typeahead.bundle.min.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/docs.min.js \"></script>"

            // JQUERY ADD-ON
            //data tables
            . "<script src=\"https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/autofill/2.2.0/js/dataTables.autoFill.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/autofill/2.2.0/js/autoFill.bootstrap.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/keytable/2.2.1/js/dataTables.keyTable.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/rowgroup/1.0.0/js/dataTables.rowGroup.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/rowreorder/1.2.0/js/dataTables.rowReorder.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/scroller/1.4.2/js/dataTables.scroller.min.js\"></script>"
            . "<script src=\"https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js\"></script>"
        ;
            // @codingStandardsIgnoreEnd
    }
}
