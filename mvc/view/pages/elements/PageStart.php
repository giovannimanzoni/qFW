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
 * Class PageStart
 *
 * @package qFW\mvc\view\pages\elements
 */
class PageStart implements IHtml
{
    /** @var string hold delimiter */
    private $del='';

    /** @var string  hold title*/
    private $title='';

    private $additionalCss='';

    public function __construct(string $title, string $del="\n",string $additionalCss='')
    {
        $this->title=$title;
        $this->del=$del;
        $this->additionalCss=$additionalCss;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return "<!DOCTYPE html>$this->del<html lang='it' itemtype='http://schema.org/WebPage' itemscope=''>$this->del<head>$this->del{$this->getMetaPanel()}$this->del{$this->getCssPanel()}$this->del{$this->getCrwIcons()}<title>{$this->title}</title>$this->del</head>$this->del<body>";
    }

    /**
     * Return html for css block
     *
     * @return string
     */
    private function getCssPanel(): string
    {
        $del = $this->del;

        // BOOTSTRAP
        $html = "<link rel='stylesheet' type='text/css' href='STATIC_ORIGIN/css/bootstrap.3.3.7.min.css' /> $del"
            . ' <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
        <!--[if lt IE 9]>' . $del;
        $html .= "<script src=\"STATIC_ORIGIN/js/html5shiv.3.7.3.min.js\"></script> $del"
            . "<script src=\"STATIC_ORIGIN/js/respond.1.4.2.min.js\"></script> $del"
            . '<![endif]-->' . $del;

        // data tables
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/autofill/2.2.0/css/autoFill.dataTables.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.bootstrap.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.bootstrap.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/keytable/2.2.1/css/keyTable.bootstrap.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/rowgroup/1.0.0/css/rowGroup.bootstrap.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.bootstrap.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/scroller/1.4.2/css/scroller.bootstrap.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/select/1.2.2/css/select.bootstrap.min.css\" /> $del";

        // tokenfield
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/tokenfield-typeahead.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/bootstrap-tokenfield.min.css\" /> $del";

        //theme
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/style.css\" id=\"base-style\" />$del";  // <---  importa moltissimi css
        //jquery ui theme
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/jquery-ui.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/jquery-ui.theme.min.css\" /> $del"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/jquery-ui.structure.min.css\" /> $del";

        // MOD
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/last.css\" /> $del";

        // custom css
        $html .= "{$this->additionalCss} $del";

        //PRINTER
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/print.css\" media=\"print\" /> $del";

        return $html;
    }

    /**
     * Return html for icons block for crawler
     *
     * @return string
     */
    private function getCrwIcons(): string
    {
        $del = $this->del;

        // http://www.favicon-generator.org/
        $html = "<link rel='shortcut icon' href='/favicon.ico' type='image/x-icon'>$del"
            . "<link rel='icon' href='/favicon.ico' type='image/x-icon'>$del"
            . "<link rel='apple-touch-icon' sizes='57x57' href='STATIC_ORIGIN/images/icons/apple-icon-57x57.png'>$del"
            . "<link rel='apple-touch-icon' sizes='60x60' href='STATIC_ORIGIN/images/icons/apple-icon-60x60.png'>$del"
            . "<link rel='apple-touch-icon' sizes='72x72' href='STATIC_ORIGIN/images/icons/apple-icon-72x72.png'>$del"
            . "<link rel='apple-touch-icon' sizes='76x76' href='STATIC_ORIGIN/images/icons/apple-icon-76x76.png'>$del"
            . "<link rel='apple-touch-icon' sizes='114x114' href='STATIC_ORIGIN/images/icons/apple-icon-114x114.png'>$del"
            . "<link rel='apple-touch-icon' sizes='120x120' href='STATIC_ORIGIN/images/icons/apple-icon-120x120.png'>$del"
            . "<link rel='apple-touch-icon' sizes='144x144' href='STATIC_ORIGIN/images/icons/apple-icon-144x144.png'>$del"
            . "<link rel='apple-touch-icon' sizes='152x152' href='STATIC_ORIGIN/images/icons/apple-icon-152x152.png'>$del"
            . "<link rel='apple-touch-icon' sizes='180x180' href='STATIC_ORIGIN/images/icons/apple-icon-180x180.png'>$del"
            . "<link rel='icon' type='image/png' sizes='192x192' href='STATIC_ORIGIN/images/icons/android-icon-192x192.png'>$del"
            . "<link rel='icon' type='image/png' sizes='32x32' href='STATIC_ORIGIN/images/icons/favicon-32x32.png'>$del"
            . "<link rel='icon' type='image/png' sizes='96x96' href='STATIC_ORIGIN/images/icons/favicon-96x96.png'>$del"
            . "<link rel='icon' type='image/png' sizes='16x16' href='STATIC_ORIGIN/images/icons/favicon-16x16.png'>$del";

        // manifest
        // https://github.com/boyofgreen/manUp.js/
        // https://thishereweb.com/understanding-the-manifest-for-web-app-3f6cd2b853d6#.66fpiucl2
        // https://thishereweb.com/manifoldjs-version-0-6-has-arrived-8f92e7a9878b#.lnilplaf5
        //https://medium.com/@elisechant/web-app-manifest-quick-start-802963195cea#.8iak3hbu5
        //$html.="<link rel=\"manifest\" href=\"STATIC_ORIGIN/icons/manifest.json\">$del";

        $html .= "<meta name='msapplication-TileColor' content='#ffffff'>$del"
            . "<meta name='msapplication-TileImage' content='STATIC_ORIGIN/images/icons/ms-icon-144x144.png'>$del"
            . "<meta name='theme-color' content='#ffffff'>$del";

        return $html;
    }

    /**
     * Return html for meta tag block
     *
     * @return string
     */
    private function getMetaPanel(): string
    {
        $del = $this->del;

        $html = "<meta charset=\"utf-8\">$del"
            . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"> $del"
            . "<meta name=\"Cache-control\" content=\"no-cache\"> $del"
            . "<meta name=\"copyright\" content=\"ImagingAgency.com\">$del"
            . "<meta name=\"generator\" content=\"qFW\">$del";

        return $html;
    }
}