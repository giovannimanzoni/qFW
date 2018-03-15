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
use qFW\mvc\view\template\ITplSidebar;

/**
 * Class PanelHtmlDoc
 *
 * @package qFW\mvc\view\pages\admin
 */
abstract class PanelHtmlDoc extends HtmlDoc
{
    /** @var string  hold page content*/
    protected $content='';

    /** @var string  hold sidebar content*/
    protected $sidebar='';

    /** @var string  hold navbar content*/
    protected $navbar='';

    /** @var string title of the page */
    protected $title='';

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
    protected abstract function setNavbar(INavbar $navBAR);

    /**
     * Setup Sidebar
     *
     * @param \qFW\mvc\view\template\ITplSidebar $sidebarTpl
     *
     * @return mixed
     */
    protected abstract function setSidebar(ITplSidebar $sidebarTpl);

    /**
     * Setup Content
     *
     * @param \qFW\mvc\view\pages\elements\IContent $content
     *
     * @return mixed
     */
    protected abstract function setContent(IContent $content);


    /**
     * Page template
     *
     * @todo: nasconderlo con wrapper alla classe che lo eredita
     *
     * @return string
     */
    protected function makeBody(): string
    {

        $html="
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
                        <p>You need to have <a href='http://en.wikipedia.org/wiki/JavaScript' target='_blank'>JavaScript</a> enabled to use this site.</p>
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
        
        <footer>
            <p>
                &copy; 2017-2018 <a href='https://imagingagency.com' alt='Imaging Agency'>Imaging Agency</a>
            </p>
        </footer>
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
        $del = $this->del;

        // JQUERY & BOOTSTRAP
        //jQuery (necessary for Bootstrap\'s JavaScript plugins)
        return "<script src=\"STATIC_ORIGIN/js/jquery.1.12.4.min.js\"></script> $del"
            . "<script src=\"STATIC_ORIGIN/js/jquery-ui.min.js\"></script> $del"
            . "<script src=\"STATIC_ORIGIN/js/bootstrap.3.3.7.min.js\"></script> $del"

            //tokenfield
            . "<script src=\"STATIC_ORIGIN/js/bootstrap-tokenfield.min.js\"></script> $del"
            //."<script src=\"STATIC_ORIGIN/js/typeahead.bundle.min.js\"></script> $t"
            . "<script src=\"STATIC_ORIGIN/js/scrollspy.js\"></script> $del"
            . "<script src=\"STATIC_ORIGIN/js/typeahead.bundle.min.js\"></script> $del"
            . "<script src=\"STATIC_ORIGIN/js/docs.min.js \"></script> $del"
            
            // JQUERY ADD-ON
            //data tables
            . "<script src=\"https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/autofill/2.2.0/js/dataTables.autoFill.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/autofill/2.2.0/js/autoFill.bootstrap.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/keytable/2.2.1/js/dataTables.keyTable.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/rowgroup/1.0.0/js/dataTables.rowGroup.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/rowreorder/1.2.0/js/dataTables.rowReorder.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/scroller/1.4.2/js/dataTables.scroller.min.js\"></script> $del"
            . "<script src=\"https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js\"></script> $del"

            . "<script src=\"https://cdn.datatables.net/autofill/2.2.0/js/dataTables.autoFill.min.js\"></script> $del";


            //."<script src=\"https://code.jquery.com/ui/1.12.1/jquery-ui.js\"></script>$t";
            // in comune x attivi e non attivi - https://jqueryui.com/checkboxradio/#default
        
            // COMPATIBILITÀ CSS TRA BROWSER
            //		."<script src=\"js/modernizr.js\"></script>$t";
    }
}