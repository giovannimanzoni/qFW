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
    /** @var string Hold title */
    private $title = '';

    private $additionalCss = '';

    public function __construct(string $title, string $additionalCss = '')
    {
        $this->title = $title;
        $this->additionalCss = $additionalCss;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        // @codingStandardsIgnoreStart
        return "<!DOCTYPE html><html lang='it' itemtype='http://schema.org/WebPage' itemscope=''>
                <head>{$this->getMetaPanel()}{$this->getCssPanel()}{$this->getCrwIcons()}
                <title>{$this->title}</title></head><body>";
        // @codingStandardsIgnoreEnd
    }

    /**
     * Return html for css block
     *
     * @return string
     */
    private function getCssPanel(): string
    {

        // BOOTSTRAP
        // @codingStandardsIgnoreStart
        $html = "<link rel='stylesheet' type='text/css' href='STATIC_ORIGIN/css/bootstrap.3.3.7.min.css' />"
            . ' <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
        <!--[if lt IE 9]>' ;
        $html .= "<script src=\"STATIC_ORIGIN/js/html5shiv.3.7.3.min.js\"></script>"
            . "<script src=\"STATIC_ORIGIN/js/respond.1.4.2.min.js\"></script>"
            . '<![endif]-->' ;

        // Data tables
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/autofill/2.2.0/css/autoFill.dataTables.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.bootstrap.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.bootstrap.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/keytable/2.2.1/css/keyTable.bootstrap.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/rowgroup/1.0.0/css/rowGroup.bootstrap.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.bootstrap.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/scroller/1.4.2/css/scroller.bootstrap.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/select/1.2.2/css/select.bootstrap.min.css\" /> ";

        // Tokenfield
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/tokenfield-typeahead.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/bootstrap-tokenfield.min.css\" /> ";

        // Theme -  it matters a lot of css
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/style.css\" id=\"base-style\" />";

        // jQuery ui theme
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/jquery-ui.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/jquery-ui.theme.min.css\" /> "
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/jquery-ui.structure.min.css\" /> ";

        // MOD
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/last.css\" /> ";

        // Font Awesome
        $html .='<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" 
                    integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" 
                    crossorigin="anonymous">';

        // custom css
        $html .= "{$this->additionalCss} ";

        // Printer
        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"STATIC_ORIGIN/css/print.css\" media=\"print\" /> ";
        // @codingStandardsIgnoreEnd
        return $html;
    }

    /**
     * Return html for icons block for crawler
     *
     * @return string
     */
    private function getCrwIcons(): string
    {
        // http://www.favicon-generator.org/
        // @codingStandardsIgnoreStart
        $html = "<link rel='shortcut icon' href='/favicon.ico' type='image/x-icon'>"
            . "<link rel='icon' href='/favicon.ico' type='image/x-icon'>"
            . "<link rel='apple-touch-icon' sizes='57x57' href='STATIC_ORIGIN/images/icons/apple-icon-57x57.png'>"
            . "<link rel='apple-touch-icon' sizes='60x60' href='STATIC_ORIGIN/images/icons/apple-icon-60x60.png'>"
            . "<link rel='apple-touch-icon' sizes='72x72' href='STATIC_ORIGIN/images/icons/apple-icon-72x72.png'>"
            . "<link rel='apple-touch-icon' sizes='76x76' href='STATIC_ORIGIN/images/icons/apple-icon-76x76.png'>"
            . "<link rel='apple-touch-icon' sizes='114x114' href='STATIC_ORIGIN/images/icons/apple-icon-114x114.png'>"
            . "<link rel='apple-touch-icon' sizes='120x120' href='STATIC_ORIGIN/images/icons/apple-icon-120x120.png'>"
            . "<link rel='apple-touch-icon' sizes='144x144' href='STATIC_ORIGIN/images/icons/apple-icon-144x144.png'>"
            . "<link rel='apple-touch-icon' sizes='152x152' href='STATIC_ORIGIN/images/icons/apple-icon-152x152.png'>"
            . "<link rel='apple-touch-icon' sizes='180x180' href='STATIC_ORIGIN/images/icons/apple-icon-180x180.png'>"
            . "<link rel='icon' type='image/png' sizes='192x192' href='STATIC_ORIGIN/images/icons/android-icon-192x192.png'>"
            . "<link rel='icon' type='image/png' sizes='32x32' href='STATIC_ORIGIN/images/icons/favicon-32x32.png'>"
            . "<link rel='icon' type='image/png' sizes='96x96' href='STATIC_ORIGIN/images/icons/favicon-96x96.png'>"
            . "<link rel='icon' type='image/png' sizes='16x16' href='STATIC_ORIGIN/images/icons/favicon-16x16.png'>";
        // @codingStandardsIgnoreEnd
        // manifest
        // https://github.com/boyofgreen/manUp.js/
        // https://thishereweb.com/understanding-the-manifest-for-web-app-3f6cd2b853d6#.66fpiucl2
        // https://thishereweb.com/manifoldjs-version-0-6-has-arrived-8f92e7a9878b#.lnilplaf5
        //https://medium.com/@elisechant/web-app-manifest-quick-start-802963195cea#.8iak3hbu5
        //$html.="<link rel=\"manifest\" href=\"STATIC_ORIGIN/icons/manifest.json\">";

        $html .= "<meta name='msapplication-TileColor' content='#ffffff'>"
            . "<meta name='msapplication-TileImage' content='STATIC_ORIGIN/images/icons/ms-icon-144x144.png'>"
            . "<meta name='theme-color' content='#ffffff'>";

        return $html;
    }

    /**
     * Return html for meta tag block
     *
     * @return string
     */
    private function getMetaPanel(): string
    {
        return "<meta charset=\"utf-8\">"
            . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"> "
            . "<meta name=\"Cache-control\" content=\"no-cache\"> "
            . "<meta name=\"copyright\" content=\"ImagingAgency.com\">"
            . "<meta name=\"generator\" content=\"qFW\">";
    }
}
