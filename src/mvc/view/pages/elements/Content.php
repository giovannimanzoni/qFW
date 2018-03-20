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

use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\view\template\content\ITplContent;
use qFW\mvc\controller\url\Url;

/**
 * Class Content
 *
 * Manage contents of the page.
 * This class will be call from PageContent class and Page class
 *
 * @package qFW\mvc\view\pages\elements
 */
final class Content implements IContent
{
    /** @var string html code delimiter */
    private $del = '';

    /** @var string hold html code for content */
    private $html = '';

    /** @var string html code for breacumb */
    private $breacumb = '';

    /**
     * Content constructor.
     *
     * @param \qFW\mvc\view\template\content\ITplContent $templateContent
     * @param string                                     $del
     */
    public function __construct(ITplContent $templateContent, string $del = "\n")
    {
        $this->html = $templateContent->getHtml();
        $this->del = $del;
    }

    /**
     * Add breacumb to the page
     *
     * @param array $breadcrumb
     *
     * @return $this|mixed
     */
    public function addBreadcrumb(array $breadcrumb)
    {
        $del = $this->del;

        $html = "<div><ul class='breadcrumb'>$del<li><i class='fas fa-home'></i>$del";

        // first
        $html .= "Home</a>$del";
        if (!empty($breadcrumb)) {
            $html .= "<i class='fas fa-caret-right'></i>$del";
        }
        $html .= "	</li> $del";

        // seguenti
        if (!empty($breadcrumb)) {
            $count = count($breadcrumb);
            foreach ($breadcrumb as $index => $elem) {
                // split data
                $arrExpl = explode('|', $elem, 2);
                $title = @$arrExpl[0];
                $link = @$arrExpl[1];

                $html .= "<li> $del";

                //se è un link ad una pagina allora inserisce link
                if (UtString::areEqual($link, '#')) { // è solo una label
                    $html .= $title;
                } else { // è un link
                    $html .= '<a href="' . Url::makeUrl($link) . "\">$title</a>  $del";
                    if ($index < $count - 1) {
                        $html .= "<i class='fas fa-caret-right'></i>$del";
                    }
                }

                $html .= "</li> $del";
            }
        }

        //close
        $html .= "</ul></div> $del";
        $this->breacumb = $html;
        return $this;
    }

    /**
     * Return html for content block
     *
     * @todo develop this function
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * Return breadcrumb
     *
     * @return string
     */
    public function getBreadCrumb(): string
    {
        return $this->breacumb;
    }
}
