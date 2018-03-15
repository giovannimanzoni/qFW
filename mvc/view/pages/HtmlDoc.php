<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\view\pages;

/**
 * Class HtmlDoc
 *
 * Template Method design pattern
 *
 * @package qFW\mvc\view\pages
 */
abstract class HtmlDoc
{

    /** @var string html code delimiter */
    protected $del = '';

    /** @var string hold first block of html code for this page */
    protected $startHtmlDoc;

    /** @var string hold first block of html code for this page */
    protected $endHtmlDoc;

    /** @var string hold javascript code */
    private $endJs = '';

    /** @var string static origin for SEO for static contents. default same server */
    private $staticOrigin = '';


    /**
     * HtmlDoc constructor.
     *
     * @param \qFW\mvc\view\pages\IHtml $startHtmlDoc
     * @param \qFW\mvc\view\pages\IHtml $endHtmlDoc
     * @param string                    $del
     */
    public function __construct(
        IHtml $startHtmlDoc,
        IHtml $endHtmlDoc,
        string $del = "\n"
    ) {

        $this->del = $del;
        $this->startHtmlDoc = $startHtmlDoc->getHtml();
        $this->endHtmlDoc = $endHtmlDoc->getHtml();
    }

    /**
     * Clean message session already shown
     */
    public function __destruct()
    {
        $_SESSION['err']='';
        $_SESSION['err']='';
    }

    /**
     * Make and output html page
     *
     */
    public function render()
    {

        $start=str_replace('STATIC_ORIGIN', $this->staticOrigin, $this->startHtmlDoc);
        $body=str_replace('STATIC_ORIGIN', $this->staticOrigin, $this->makeBody());
        $end=str_replace('STATIC_ORIGIN', $this->staticOrigin, $this->endJs());


        $html = $start
            . $body
            . $end
            . $this->endHtmlDoc
            . $this->closeHtmlDoc();

        echo $html;
    }

    /**
     * Custom base url for static origin ( for SEO)
     *
     * @param string $staticOrigin
     *
     * @return $this
     */
    public function setStaticOrigin(string $staticOrigin): HtmlDoc
    {
        $this->staticOrigin = $staticOrigin;
        return $this;
    }

    /**
     * Add javascript code into the end of the page, custom js different in every page
     *
     * @param string $html
     *
     * @return mixed
     */
    protected function addEndJs(string $html)
    {
        $this->endJs .= $html;
        return $this;
    }

    /**
     * Implement html page template
     *
     * @return string
     */
    protected abstract function makeBody(): string;

    /**
     * Insert common js at the end of the html doc, for example bootstrap.hs etc
     * @return string
     */
    protected abstract function endJs(): string;

    /**
     * Return html closing tag block
     *
     * @return string
     */
    private function closeHtmlDoc(): string
    {
        return str_replace('STATIC_ORIGIN', $this->staticOrigin, $this->endJs) .
            '</body></html>';
    }
}
