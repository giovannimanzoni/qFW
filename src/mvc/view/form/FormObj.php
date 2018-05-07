<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\form;

use qFW\constants\encType\IEncType;
use qFW\constants\encType\Multipart;
use qFW\constants\encType\TextPlain;
use qFW\constants\encType\UrlEncoded;
use qFW\log\ILogOutput;
use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\url\Url;
use qFW\mvc\controller\vocabulary\Voc;
use qFW\mvc\view\form\elements\IFormElements;
use qFW\mvc\view\form\elements\TGlobalEventAttributes;
use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\controller\dataTypes\UtArray;

/**
 * Class FormObj
 *
 * Construct and show form
 *
 * @package qFW\mvc\view\form
 */
class FormObj
{
    /** @var \qFW\mvc\view\form\IFormObjBuilder Hold constructor */
    private $objBuilder;

    /** @var string action page */
    private $actionPage = '';

    /** @var bool Hold if form is multi page or single page. true = multi page */
    private $isMultiPage;

    // Optionals

    /** @var string Hold form method */
    private $method = 'POST';

    /** @var bool Hold if form will open a new page when submitted */
    private $openInNewPage = false;

    // Error handling

    /** @var bool Hold if form options/paramethers are checked */
    private $checked = false;

    /** @var bool Hold if form options values are valid */
    private $valid = false;

    /** @var bool Hold if form has got auto complete features */
    private $autocomplete = false;

    /** @var string Hold encoding type of the form */
    private $enctype = '';

    /** @var array Hold pages names */
    private $pagesName = array();

    /** @var int Hold number of pages */
    private $numPagine = 0;

    /** @var array Each element hold element of every form pages */
    private $frmElementsByPage = array();

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    /** @var \qFW\mvc\controller\dataTypes\UtArray */
    private $utArr;

    /** @var \qFW\mvc\controller\dataTypes\UtString */
    private $utStr;

    /** @var \qFW\mvc\controller\url\Url */
    private $url;

    use TError;
    use TGlobalAttributes;
    use TGlobalEventAttributes;

    /**
     * FormObj constructor.
     *
     * @param \qFW\mvc\view\form\IFormObjBuilder $objBuilder
     * @param string                             $actionPage
     * @param \qFW\log\ILogOutput                $outputLog
     * @param bool                               $isMultiPage
     */
    public function __construct(
        IFormObjBuilder $objBuilder,
        string $actionPage,
        ILogOutput $outputLog,
        bool $isMultiPage = false
    ) {
        $lang = $outputLog->getLang();

        $this->voc = new Voc();
        $this->utArr = new UtArray();

        $this->createLogger($outputLog);
        $this->loggerEngine->setLang($lang);

        $this->utStr = new UtString($lang);
        $this->url = new Url();


        $this->id = $outputLog->getUid();
        $this->objBuilder = $objBuilder;
        $this->actionPage = $actionPage;
        $this->setEncType(new UrlEncoded()); // Default
        $this->isMultiPage = $isMultiPage;
    }

    /**
     * Set method as Get
     *
     * @return $this
     */
    public function setMethodAsGet()
    {
        $this->method = 'GET';
        return $this;
    }

    /**
     * Enable auto complete
     *
     * @return $this
     */
    public function setAutocompleteOn()
    {
        $this->autocomplete = true;
        return $this;
    }

    /**
     * Set form encoding type to Multipart
     *
     * @return $this
     */
    public function setEnctypeMultipartFormData()
    {
        $this->setEncType(new Multipart());
        return $this;
    }

    /**
     * Set form encoding type to text/plain
     *
     * @return $this
     */
    public function setEnctypeTextPlain()
    {
        $this->setEncType(new TextPlain());
        return $this;
    }

    /**
     * Set encoding type
     *
     * @param \qFW\constants\encType\IEncType $type
     */
    private function setEncType(IEncType $type)
    {
        $this->enctype = $type->getCode();
    }

    /**
     * When submitted, form open a new page
     *
     * @return $this
     */
    public function openInNewPage()
    {
        $this->openInNewPage = true;
        return $this;
    }


    /************************************
     * Interface methods
     ***********************************/

    /**
     * Check form construction options
     *
     * @return bool
     */
    public function check(): bool
    {
        /********************************************
         * Get items from all pages
         *******************************************/
        $this->pagesName = $this->objBuilder->getPagesName();
        $this->numPagine = count($this->pagesName);

        for ($n = 0; $n < $this->numPagine; $n++) {
            $this->frmElementsByPage[] = $this->objBuilder->getElements($this->pagesName[$n]);
        }

        // Check for errors in the builder
        if (!$this->objBuilder->check()) {
            $this->importLogs($this->objBuilder);
        } else {
            /*Ok*/
        }

        /********************************************
         * Check for errors in each element
         *******************************************/
        $ids = array();
        $elementWithAutofocus = 0;
        $accesKeys = array();
        $tabIndexValues = array();
        foreach ($this->frmElementsByPage as $elPage) {
            foreach ($elPage as $element) {
                // Used in reporting errors
                $elId = $element->getId();

                // If element does not pass its check -> error
                if (!$element->check()) {
                    $this->importLogs($element);
                }

                /********************************************
                 * Bootstrap checks
                 *******************************************/
                if ($element->isRatioSetted() && !$element->isHorizontal()) {
                    $this->addLog("id='$elId': _VOC_", $this->voc->formRatioErr());
                } else {
                    /*Ok*/
                }
                if (!$element->isHorizontal()) {
                    $element->setLabelOnTop();
                } // labelOnTop, label wide the whole field so alignment is horizontal
                elseif (!$element->isRatioSetted()) {  // If not set ratio, set default value
                    $element->setElementRatio(9);
                } else {
                    // Alignment is horizontal, ratio is set but let me check if one of the two dimensions is zero,
                    // since it would not make sense, I warn it
                    if ($element->getCol1() == 0 && !$element->isLabelDisabled()) {
                        $this->addLog("id='$elId': _VOC_", $this->voc->formDimensionNotSet());
                    } else {
                        /*Ok*/
                    }
                    if ($element->getCol2() == 0) {
                        $this->addLog("id='$elId': Form element _VOC_", $this->voc->formDimensionNotSet());
                    } else {
                        /*Ok*/
                    }
                }

                // If there is no label, width = maximum Set col2=12
                if (!$this->isText($element)) {
                    if ($element->getLabel() == '' && !$element->isLabelDisabled()) {
                        $element->setLabelOnTop();
                    } else {
                        /*Ok*/
                    }
                } else {
                    /*ok text, no setLabelOnTop()*/
                }

                // Fields of the form > of 12 goes against the bootstrap proportions,
                //  < of 1 would not make sense to create fields and not show them ..
                $dim = $element->getElementDim();
                if ($dim > 12 || $dim < 1) {
                    $this->addLog("id='$elId': _VOC_ ($dim)", $this->voc->formDimensionWrong());
                } else {
                    /*Ok*/
                }

                /********************************************
                 * Html checks
                 *******************************************/

                // Check duplicated id
                if ($element->getId() != '') {
                    $ids[] = $elId;
                }
                if ($element->getContextMenuId() != '') {
                    $ids[] = $element->getContextMenuId();
                }

                // Count autofocus elements
                if (method_exists($element, 'getAutofocus')) {
                    if ($element->getAutofocus()) {
                        $elementWithAutofocus++;
                    }
                } // check this at the end of loop

                // Count accesskey
                $key = $element->getAccesskey();
                if ($key != '') {
                    $accesKeys[] = $key;
                } // check this at the end of loop

                // Check contextmenu
                // If set, both must be populated
                if (($element->getContextMenuId() != '') && ($element->getContextMenuHtml() == '')) {
                    $this->addLog(
                        "id='$elId': Contexmenu id='{$element->getContextMenuId()}' _VOC_",
                        $this->voc->formSetNoHtml()
                    );
                }
                if (($element->getContextMenuId() == '') && ($element->getContextMenuHtml() != '')) {
                    $this->addLog("id='$elId': Contexmenu _VOC_", $this->voc->formSetNoId());
                }

                // Check global attribute data-*
                $dataGAName = $element->getDataGAName();
                $dataGAValue = $element->getDataGAValue();
                if ($dataGAName != '') {
                    // Global attribute data-* name must be lowercase
                    $lowerDataGAName = strtolower($dataGAName);
                    if (strcmp($dataGAName, $lowerDataGAName) !== 0) {
                        $this->addLog("id='$elId': _VOC_", $this->voc->formDataNoUppercase());
                    }

                    // Global attribute value make no sense if empty
                    if ($dataGAValue == '') {
                        $this->addLog("id='$elId': _VOC_", $this->voc->formDataSetEmpty());
                    }
                }
                if (is_numeric($dataGAValue)) {
                    $this->addLog("id='$elId': _VOC_", $this->voc->formDataSetNumeric());
                }

                // Check draggable
                // @todo controllare se impostato draggable e se ci sn eventi drag, altrimenti segnalare errore
                //  - https://www.w3schools.com/tags/tryit.asp?filename=tryhtml5_global_draggable

                // Check lang
                $elLang = $element->getLangGA();
                if ($elLang != '') {
                    if (!$this->checkLang($elLang)) {
                        $this->addLog("id='$elId': _VOC_", $this->voc->formLangNotAllowed());
                    }
                }

                // Check spellcheck
                if ($element->getSpellcheck()) {
                    /*
                    Can be set only for
                        Text values in input elements (not password)
                        Text in <textarea> elements
                        Text in editable elements
                    */
                    // If editable
                    if ($element->getContentEditable()) {/* Ok allow*/
                    } elseif (0) {
                        /* @todo ok for textarea */
                    } elseif (0) {
                        /* @todo ok for input element but not for password */
                    } else {
                        $this->addLog("id='$elId': _VOC_", $this->voc->formSpellcheckNotAllowed());
                    }
                }

                // Check if there are duplicate tab indexes
                $tabIndex = $element->getTabIndex();
                if ($tabIndex) {
                    $tabIndexValues[] = $tabIndex;
                }
            }
        }

        // Duplicate id check
        if ($this->utArr->checkDuplicateValues($ids)) {
            $this->addLog("_VOC_ : {$this->utArr->getArrayDuplicateValues($ids)}.", $this->voc->formDuplicatedId());
        }

        // Autofocus check
        if ($elementWithAutofocus > 1) {
            $this->addLog('_VOC_', $this->voc->formOnlyOneAutofocus());
        }

        // Accesskey check
        foreach ($accesKeys as $key) {
            if (strlen($key) > 1) {
                $this->addLog("_VOC_ ($key).", $this->voc->formAcceskeyTooLong());
            }
        }
        if ($this->utArr->checkDuplicateValues($accesKeys)) {
            $this->addLog('_VOC_', $this->voc->formAcceskeyDuplicated());
        }

        // Duplicate tabindex check
        if ($this->utArr->checkDuplicateValues($tabIndexValues)) {
            $this->addLog('_VOC_', $this->voc->formTabIndexDuplicated());
        }

        /************************************
         * Checks on the options of the FORM
         ***********************************/
        // Enctype & post only
        if (($this->utStr->areEqual($this->method, 'GET')) && ($this->enctype != '')) {
            $this->addLog('_VOC_', $this->voc->formEnctypeGetErr());
        }

        if ($this->getLogsQty() == 0) {
            $this->valid = true;
        }
        $this->checked = true;
        return $this->valid;
    }

    /**
     * Import log from form object
     *
     * @param $obj
     */
    private function importLogs($obj)
    {
        $this->addLog($obj->getLogs());
    }

    /**
     * Check if lang is valid
     *
     * @param string $lang
     *
     * @return bool
     */
    private function checkLang(string $lang): bool
    {
        $arrlang = array(
            'ab', 'aa', 'af', 'ak', 'sq', 'am', 'ar', 'an', 'hy', 'as', 'av', 'ae', 'ay', 'az', 'bm', 'ba',
            'eu', 'be', 'bn', 'bh', 'bi', 'bs', 'br', 'bg', 'my', 'ca', 'ch', 'ce', 'ny', 'zh', 'zh-Hans',
            'zh-Hant', 'cv', 'kw', 'co', 'cr', 'hr', 'cs', 'da', 'dv', 'nl', 'dz', 'en', 'eo', 'et', 'ee',
            'fo', 'fj', 'fi', 'fr', 'ff', 'gl', 'gd', 'gv', 'ka', 'de', 'el', 'kl', 'gn', 'gu', 'ht', 'ha',
            'he', 'hz', 'hi', 'ho', 'hu', 'is', 'io', 'ig', 'id', 'in', 'ia', 'ie', 'iu', 'ik', 'ga', 'it',
            'ja', 'jv', 'kl', 'kn', 'kr', 'ks', 'kk', 'km', 'ki', 'rw', 'rn', 'ky', 'kv', 'kg', 'ko', 'ku',
            'kj', 'lo', 'la', 'lv', 'li', 'ln', 'lt', 'lu', 'lg', 'lb', 'gv', 'mk', 'mg', 'ms', 'ml', 'mt',
            'mi', 'mr', 'mh', 'mo', 'mn', 'na', 'nv', 'ng', 'nd', 'ne', 'no', 'nb', 'nn', 'ii', 'oc', 'oj',
            'cu', 'or', 'om', 'os', 'pi', 'ps', 'fa', 'pl', 'pt', 'pa', 'qu', 'rm', 'ro', 'ru', 'se', 'sm',
            'sg', 'sa', 'sr', 'sh', 'st', 'tn', 'sn', 'ii', 'sd', 'si', 'ss', 'sk', 'sl', 'so', 'nr', 'es',
            'su', 'sw', 'ss', 'sv', 'tl', 'ty', 'tg', 'ta', 'tt', 'te', 'th', 'bo', 'ti', 'to', 'ts', 'tr',
            'tk', 'tw', 'ug', 'uk', 'ur', 'uz', 've', 'vi', 'vo', 'wa', 'cy', 'wo', 'fy', 'xh', 'yi', 'ji',
            'yo', 'za', 'zu'
        );

        return in_array($lang, $arrlang);
    }

    /**
     * Render form. you must echo the returning string of html code
     *
     * @return string  html code
     */
    public function show(): string
    {
        $html = '';

        if (!$this->checked) {
            $this->check();
        }

        if ($this->valid) {
            if (!$this->isMultiPage) {
                $html .= $this->startFormTag();
            }


            /**************************************
             * Opening jQuery block tabs
             **************************************/
            if ($this->numPagine > 1) {
                $html .= '<div class="paginastep">
                            <div id="tabs">
                                <ul>';

                foreach ($this->pagesName as $key => $name) {
                    $tabNum = $key + 1;
                    $html .= "<li><a href='#tabs-$tabNum'>$name</a></li>";
                }
                $html .= '</ul>';
            }

            /**************************************
             * Processing for each page
             **************************************/
            for ($pag = 0; $pag < $this->numPagine; $pag++) {
                // Open tab
                $tabNum = $pag + 1;
                if ($this->numPagine > 1) {
                    $html .= "<div id='tabs-$tabNum'>";
                }

                /**************************************
                 * Processing for each element
                 **************************************/

                // This for add at the bottom of the form the wording for the elements with mandatory compilation
                $numElem = count($this->frmElementsByPage[$pag]);
                $i = 1;

                // @todo manage last name if there are two groups of checkboxes
                // @todo manage inline in the class (.checkbox-inline)

                foreach ($this->frmElementsByPage[$pag] as $element) {
                    $html .= $this->checkMultipleOjbPerLineStart($element);

                    $html .= '<div ';

                    if ($this->isDiv($element)) {
                        $html .= 'class="row" ';
                    }

                    if (method_exists($element, 'getHidden')) {
                        if (!$element->getHidden()) {
                            $html .= 'class="form-group" ';
                        }
                    } else {
                        $html .= 'class="form-group" ';
                    }

                    $html .= '>';

                    $html .= $this->getHtml($element);

                    $html .= '</div>'; // Close form-group

                    $html .= $this->checkMultipleOjbPerLineEnd($element);


                    // Check to make the mandatory choice appear
                    if (!$this->isMultiPage) { // Does not add the writing for each block inserted
                        if ($i == ($numElem - 1)) {
                            $html .= '<div class="col-xs-12"><p class="qPanelFormRequiredString">'
                                . $this->objBuilder->getRequiredSymbol() . $this->objBuilder->getRequiredString()
                                . '</p ></div>';
                        }
                        $i++;
                    }
                }

                $html .= $this->pageButtons($pag);

                // Closing tab
                if ($this->numPagine > 1) {
                    $html .= '</div>';
                }
            }


            /**************************************
             * Close jQuery tabs block
             **************************************/
            if ($this->numPagine > 1) {
                $html .= '</div></div>';
            }

            // Close the form
            if (!$this->isMultiPage) {
                $html .= '</form>';
            }
        }

        return $html;
    }

    /**
     * Make buttons if form is in multi page mode
     *    buttons forward / back at the bottom of the page
     *
     * @param int $pag
     *
     * @return string
     */
    private function pageButtons(int $pag): string
    {
        $tabPag = $pag + 1;
        $html = '';
        if ($this->numPagine > 1) {
            if ($pag == 0) {
                $nextPag = $tabPag + 1;
                $html .= "  <div class='row'>
                                <div class='col-xs-12'>
                                    <button id='nextPage$nextPag' type='button' 
                                    class='btn btn-primary btn-lg'> > </button>
                                </div>
                            </div>";
            } else {
                if ($pag == ($this->numPagine - 1)) {
                    $prevPag = $tabPag - 1;

                    $html .= "  <div class='row'>
                                    <div class='col-xs-12'>
                                        <button id='prevPage$prevPag' type='button' 
                                        class='btn btn-primary btn-lg'> < </button>
                                    </div>
                                </div>";
                } else {
                    $nextPag = $tabPag + 1;
                    $prevPag = $tabPag - 1;

                    $html .= "  <div class='row'>
                                    <div class='col-xs-12'>
                                        <button id='prevPage$prevPag' type='button' 
                                        class='btn btn-primary btn-lg'> < </button>
                                        <button id='nextPage$nextPag' type='button' 
                                        class='btn btn-primary btn-lg'> > </button>
                                    </div>
                                </div>";
                }
            }
        }

        return $html;
    }

    /**
     * Check if special starting html code for manage multiple form object per line is needed
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $element
     *
     * @return string
     */
    private function checkMultipleOjbPerLineStart(IFormElements $element): string
    {
        $html = '';
        // Count if multiple objects are shown on the same row
        $elemDim = $element->getElementRowClass();
        if ($elemDim != '') {
            $html .= "<div class='$elemDim'>";
        }
        return $html;
    }

    /**
     * Check if special ending html code for manage multiple form object per line is needed
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $element
     *
     * @return string
     */
    private function checkMultipleOjbPerLineEnd(IFormElements $element): string
    {
        $html = '';

        $elemDim = $element->getElementRowClass();
        if ($elemDim != '') {
            $html .= '</div>';
        }
        return $html;
    }


    /**
     * Return html code for open an html form
     *
     * @return string
     */
    private function startFormTag(): string
    {
        $html = "<form name='{$this->id}' method='{$this->method}' action='"
            . $this->url->makeUrl($this->actionPage) . "' ";

        $html .= $this->getGlobalAttributes();

        if ($this->autocomplete) {
            $html .= 'autocomplete="on" ';
        } else {
            $html .= 'autocomplete="off" ';
        }

        if ($this->openInNewPage) {
            $html .= 'target="_blank" ';
        }
        if ($this->enctype != '') {
            $html .= "enctype='{$this->enctype}' ";
        }

        $html .= $this->getGlobalEventAttributesHtml();

        $html .= '>';

        return $html;
    }

    /**
     * Get html of given form element
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $el
     *
     * @return string
     */
    private function getHtml(IFormElements $el): string
    {
        $html = '';

        // Label
        $html .= $this->getLabel($el);

        // Obj
        $html .= $el->make();

        if ($el->getContextMenuId() != '') {
            $html .= $el->getContextMenuHtml();
        }

        // Closing label for checkbox

        if ($this->isCheckbox($el)) {
            if ($el->isRequired()) {
                $html .= $this->objBuilder->getRequiredSymbol();
            }
            $html .= '</label>';
        }

        return $html;
    }

    /**
     * Get label of given html form element
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $el
     *
     * @return string
     */
    private function getLabel(IFormElements $el): string
    {
        $html = '';
        $label = $el->getLabel();

        if ($label != '') { // We discard objects that do not have labels, for example titles or hidden fields
            $html .= '<label class="';

            if ($this->isCheckbox($el)) { // For checkbox
                $html .= 'checkbox " >';
            } elseif ($this->isRadio($el)) { // For checkbox
                $html .= 'radio " >';
            } else { // for all other types of obj
                $html .= 'control-label ';
                if ($el->isHorizontal()) {
                    $html .= 'col-xs-' . (12 - $el->getElementDim()) . ' labelToLeft';
                }

                $html .= '" for="' . $el->getId() . '" >' . $label;
                if ($el->isRequired()) {
                    $html .= $this->objBuilder->getRequiredSymbol();
                }
                $html .= '</label>';
            }
        }
        return $html;
    }

    /**
     * Return if given form element is a checkbox
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $element
     *
     * @return bool
     */
    private function isCheckBox(IFormElements $element): bool
    {
        return $this->utStr->areEqual($element->getElementType(), 'checkbox');
    }

    /**
     * Return if given form element is a div
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $element
     *
     * @return bool
     */
    private function isDiv(IFormElements $element): bool
    {
        return $this->utStr->areEqual($element->getElementType(), 'div');
    }

    /**
     * Return if given form element is a radio
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $element
     *
     * @return bool
     */
    private function isRadio(IFormElements $element): bool
    {
        return $this->utStr->areEqual($element->getElementType(), 'radio');
    }

    /**
     * Return if give element is a text
     *
     * @param \qFW\mvc\view\form\elements\IFormElements $element
     *
     * @return bool
     */
    private function isText(IFormElements $element): bool
    {
        return $this->utStr->areEqual($element->getElementType(), 'text');
    }
}
