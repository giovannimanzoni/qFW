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
use qFW\mvc\controller\lang\ILang;
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

    /** @var string Hold html code for content */
    private $html = '';

    /** @var string Html code for breacumb */
    private $breacumb = '';

    /** @var \qFW\mvc\controller\dataTypes\UtString  */
    private $utStr;

    /** @var \qFW\mvc\controller\url\Url  */
    private $url;

    /**
     * Content constructor.
     *
     * @param \qFW\mvc\view\template\content\ITplContent $templateContent
     * @param \qFW\mvc\controller\lang\ILang             $lang
     */
    public function __construct(ITplContent $templateContent, ILang $lang)
    {
        $this->html = $templateContent->getHtml();
        $this->utStr= new UtString($lang);
        $this->url = new Url();
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
        $html = "<div><ul class='breadcrumb'><li><i class='fas fa-home'></i>";

        // First element
        $html .= "Home</a>";
        if (!empty($breadcrumb)) {
            $html .= "<i class='fas fa-caret-right'></i>";
        }
        $html .= '</li>';

        // Other elements
        if (!empty($breadcrumb)) {
            $count = count($breadcrumb);
            foreach ($breadcrumb as $index => $elem) {
                // split data
                $arrExpl = explode('|', $elem, 2);
                $title = @$arrExpl[0];
                $link = @$arrExpl[1];

                $html .= '<li>';

                // If it is a link to a page then it inserts links
                if ($this->utStr->areEqual($link, '#')) { // It is only a label
                    $html .= $title;
                } else { // It is a link
                    $html .= '<a href="' . $this->url->makeUrl($link) . "\">$title</a>";
                    if ($index < $count - 1) {
                        $html .= "<i class='fas fa-caret-right'></i>";
                    }
                }

                $html .= '</li>';
            }
        }

        // Close
        $html .= '</ul></div>';
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
