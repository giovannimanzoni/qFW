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
use qFW\mvc\controller\url\Url;
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
    /** @var \qFW\mvc\view\form\IFormObjBuilder  hold constructor*/
    private $objBuilder;

    /** @var string action page*/
    private $actionPage='';

    /** @var bool hold if form is multi page or single page. true = multi page */
    private $isMultiPage;

    //opzionali

    /** @var string hold form method */
    private $method = 'POST';

    /** @var bool  hold if form will open a new page when submitted*/
    private $openInNewPage = false;

    //gestione errori

    /** @var bool  hold if form options/paramethers are checked*/
    private $checked = false;

    /** @var bool  hold if form options values are valid*/
    private $valid = false;

    /** @var bool  hold if form has got auto complete features*/
    private $autocomplete = false;

    /** @var string  hold encoding type of the form*/
    private $enctype = '';

    /** @var array  hold pages names*/
    private $pagesName = array();

    /** @var int  hold number of pages*/
    private $numPagine = 0;

    /** @var array  each element hold element of every form pages*/
    private $frmElementsByPage = array();

    use TError;
    use TGlobalAttributes;
    use TGlobalEventAttributes;

    /**
     * FormObj constructor.
     *
     * @param string                                      $id           html id
     * @param \qFW\mvc\view\form\IFormObjBuilder $objBuilder   object
     * @param string                                      $actionPage   action page
     * @param \qFW\log\ILogOutput                      $outputLog
     * @param bool                                        $makeBlock    multipages form ?
     */
    public function __construct(
        string $id,
        IFormObjBuilder $objBuilder,
        string $actionPage,
        ILogOutput $outputLog,
        bool $makeBlock = false
    ) {
        $this->createLogger($outputLog);

        $this->id = $id;
        $this->objBuilder = $objBuilder;
        $this->actionPage = $actionPage;
        $this->setEncType(new UrlEncoded()); // default
        $this->isMultiPage = $makeBlock;
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
     * when submitted, form open a new page
     *
     * @return $this
     */
    public function openInNewPage()
    {
        $this->openInNewPage = true;
        return $this;
    }


    /************************************
     * metodi dell'interfaccia
     ***********************************/

    /**
     * Check form construction options
     *
     * @return bool
     */
    public function check(): bool
    {

        /********************************************
         * Ricava elementi di tutte le pagine
         *******************************************/
        $this->pagesName = $this->objBuilder->getPagesName();
        $this->numPagine = count($this->pagesName);

        for ($n = 0; $n < $this->numPagine; $n++) {
            $this->frmElementsByPage[] = $this->objBuilder->getElements($this->pagesName[$n]);
        }

        // controlla errori nel builder
        if (!$this->objBuilder->check()) {
            $this->importLogs($this->objBuilder);
        }


        /********************************************
         * Controlla errori in ogni elemento
         *******************************************/
        $ids = array();
        $elementWithAutofocus = 0;
        $accesKeys = array();
        $tabIndexValues = array();
        foreach ($this->frmElementsByPage as $elPage) {
            foreach ($elPage as $element) {
                //usato nella segnalazione degli errori
                $elId = $element->getId();

                // se elemento non passa il suo check -> errore
                if (!$element->check()) {
                    $this->importLogs($element);
                }

                /********************************************
                 * Controlli Bootstrap
                 *******************************************/
                if ($element->isRatioSetted() && !$element->isHorizontal()) {
                    $this->addLog("id='$elId': Conflitto, parametri ratio e orizzontale impostati entrambi ");
                }
                if (!$element->isHorizontal()) {
                    $element->setLabelOnTop();
                } // labelOnTop, label larga tutto il campo
                // quindi allineamento è orizzontale
                elseif (!$element->isRatioSetted()) {  // se non impostato ratio, imposta valore di default
                    $element->setElementRatio(9);
                } else {    // allineamento è orizzontale, ratio è impostato ma controllo
                            //  se una delle due dimensioni è zero visto che non avrebbe senso, lo prevengo
                    if ($element->getCol1() == 0 && !$element->isLabelDisabled()) {
                        $this->addLog("id='$elId': Dimensione label non impostata.");
                    }
                    if ($element->getCol2() == 0) {
                        $this->addLog("id='$elId': Dimensione elemento non impostata.");
                    }
                }

                // se non c'è la label, larghezza = massima
                if ($element->getLabel() == '' && !$element->isLabelDisabled()) {
                    $element->setLabelOnTop();
                } // imposta col2=12

                // campi del form > di 12 va contro le proporzioni di bootstrap,
                //  < di 1 non avrebbe senso creare campi e non mostrarli..
                $dim = $element->getElementDim();
                if ($dim > 12 || $dim < 1) {
                    $this->addLog("id='$elId': Dimensione campo errate: " . $dim);
                }

                /********************************************
                 * Controlli html
                 *******************************************/


                // check id duplicati
                if ($element->getId() != '') {
                    $ids[] = $elId;
                }
                if ($element->getContextMenuId() != '') {
                    $ids[] = $element->getContextMenuId();
                }

                // conteggia elementi con autofocus
                if (method_exists($element, 'getAutofocus')) {
                    if ($element->getAutofocus()) {
                        $elementWithAutofocus++;
                    }
                } // controllato a fine ciclo

                // conteggia accesskey
                $key = $element->getAccesskey();
                if ($key != '') {
                    $accesKeys[] = $key;
                } // controllato a fine ciclo

                // controlla contextmenu
                // se settati, entrambi devono essere popolati
                if (($element->getContextMenuId() != '') && ($element->getContextMenuHtml() == '')) {
                    $this->addLog("id='$elId': Contexmenu con id='{$element->getContextMenuId()}'
                                        impostato ma senza html");
                }
                if (($element->getContextMenuId() == '') && ($element->getContextMenuHtml() != '')) {
                    $this->addLog("id='$elId': Contexmenu impostato senza id ");
                }

                // controlla global attrribute data-*
                $dataGAName = $element->getDataGAName();
                $dataGAValue = $element->getDataGAValue();
                if ($dataGAName != '') {
                    // global attribute data-* name must be lowercase
                    $lowerDataGAName = strtolower($dataGAName);
                    if (strcmp($dataGAName, $lowerDataGAName) !== 0) {
                        $this->addLog("id='$elId': The attribute name data-* 
                                            should not contain any uppercase letters");
                    }

                    // global attribute value make no sense if empty
                    if ($dataGAValue == '') {
                        $this->addLog("id='$elId': Valore per l'attributo data-* impostato vuoto");
                    }
                }
                if (is_numeric($dataGAValue)) {
                    $this->addLog("id='$elId': Valore per l'attributo data-* impostato di tipo numerico");
                }

                // controlla draggable
                // @todo controllare se impostato draggable e se ci sn eventi drag, altrimenti segnalare errore
                //  - https://www.w3schools.com/tags/tryit.asp?filename=tryhtml5_global_draggable

                // controllo lang
                $elLang = $element->getLang();
                if ($elLang != '') {
                    if (!$this->checkLang($elLang)) {
                        $this->addLog("id='$elId': Valore per l'attributo lang non consentito");
                    }
                }

                // controllo spellcheck
                if ($element->getSpellcheck()) {
                    /*
                    Abilitabile solo per
                        Text values in input elements (not password)
                        Text in <textarea> elements
                        Text in editable elements
                    */
                    // se editabile
                    if ($element->getContentEditable()) {/* ok ammesso*/
                    } elseif (0) {
                        /* @todo ok per textarea */
                    } elseif (0) {
                        /* @todo ok per input element ma non password */
                    } else {
                        $this->addLog("id='$elId': Impostato spellcheck su elemento che non lo può avere");
                    }
                }

                // controllo se tab index duplicati
                $tabIndex = $element->getTabIndex();
                if ($tabIndex) {
                    $tabIndexValues[] = $tabIndex;
                }
            }
        }
        // controllo id duplicato
        if (UtArray::checkDuplicateValues($ids)) {
            $this->addLog('Rilevati id duplicati : ' . UtArray::getArrayDuplicateValues($ids));
        }

        // controllo autofocus
        if ($elementWithAutofocus > 1) {
            $this->addLog('Solo un elemento può avere l\'autofocus');
        }

        // controllo  accesskey
        foreach ($accesKeys as $key) {
            if (strlen($key) > 1) {
                $this->addLog("Accesskey più lunga di un carattere: $key");
            }
        }
        if (UtArray::checkDuplicateValues($accesKeys)) {
            $this->addLog('Accesskey duplicate');
        }

        // controllo tabindex duplicati
        if (UtArray::checkDuplicateValues($tabIndexValues)) {
            $this->addLog('Rilevati tabindex duplicati');
        }

        /************************************
         * controlli sulle opzioni del form
         ***********************************/
        // enctype & post only
        if ((UtString::areEqual($this->method, 'GET')) && ($this->enctype != '')) {
            $this->addLog('Enctype non può essere impostato per form con method="GET"');
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
        $arrlang = array('ab', 'aa', 'af', 'ak', 'sq', 'am', 'ar', 'an', 'hy', 'as', 'av', 'ae', 'ay', 'az', 'bm', 'ba',
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
                         'yo', 'za', 'zu');

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
             * apertura blocco jQuery tabs
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
             * elaborazione per ogni pagina
             **************************************/
            for ($pag = 0; $pag < $this->numPagine; $pag++) {
                // apertura tab
                $tabNum = $pag + 1;
                if ($this->numPagine > 1) {
                    $html .= "<div id='tabs-$tabNum'>";
                }

                /**************************************
                 * elaborazione per ogni elemento
                 **************************************/

                // per aggiungere in fondo al form la dicitura per gli elementi con compilazione obbligatoria
                $numElem = count($this->frmElementsByPage[$pag]);
                $i = 1;

                // gestire last name nel caso ci siano due gruppi di checkbox
                // gestire inline
                // . nella classe (.checkbox-inline)

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

                    $html .= '</div>'; //close form-group

                    $html .= $this->checkMultipleOjbPerLineEnd($element);


                    // controllo per far comparire la scritta di scelta obbligatoria
                    if (!$this->isMultiPage) { // non aggiunge la scritta per ogni blocco inserito
                        if ($i == ($numElem - 1)) {
                            $html .= '<div class="col-xs-12"><p class="qPanelFormRequiredString">'
                                . $this->objBuilder->getRequiredSymbol() . $this->objBuilder->getRequiredString()
                                . '</p ></div>';
                        }
                        $i++;
                    }
                }

                $html .= $this->bottoniPagina($pag);

                //chiusura tab
                if ($this->numPagine > 1) {
                    $html .= '</div>';
                }
            }


            /**************************************
             * chiusura blocco jQuery tabs
             **************************************/
            if ($this->numPagine > 1) {
                $html .= '</div></div>';
            }

            // chiusura form
            if (!$this->isMultiPage) {
                $html .= '</form>';
            }
        }

        return $html;
    }

    //bottoni avanti/indietro in fondo alla pagina

    /**
     * Make buttons if form is in multi page mode
     *
     * @param int $pag
     *
     * @return string
     */
    private function bottoniPagina(int $pag): string
    {
        $tabPag = $pag + 1;
        $html = '';
        if ($this->numPagine > 1) {
            if ($pag == 0) {
                $nextPag = $tabPag + 1;
                $html .= "  <div class='row'>
                                <div class='col-xs-12'>
                                    <button id='nextPage$nextPag' type='button' 
                                    class='btn btn-primary btn-lg'>Avanti</button>
                                </div>
                            </div>";
            } else {
                if ($pag == ($this->numPagine - 1)) {
                    $prevPag = $tabPag - 1;

                    $html .= "  <div class='row'>
                                    <div class='col-xs-12'>
                                        <button id='prevPage$prevPag' type='button' 
                                        class='btn btn-primary btn-lg'>Indietro</button>
                                    </div>
                                </div>";
                } else {
                    $nextPag = $tabPag + 1;
                    $prevPag = $tabPag - 1;

                    $html .= "  <div class='row'>
                                    <div class='col-xs-12'>
                                        <button id='prevPage$prevPag' type='button' 
                                        class='btn btn-primary btn-lg'>Indietro</button>
                                        <button id='nextPage$nextPag' type='button' 
                                        class='btn btn-primary btn-lg'>Avanti</button>
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
        // conteggio se vanno mostrati piu oggetti sulla stessa riga
        $elemDim = $element->getElementRowClass();
        if ($elemDim != '') { // BOOTSTRAP_COLUMNS, come la x essere visibile anche qui ?
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

        $html = "<form name='{$this->id}' method='{$this->method}' action='".Url::makeUrl($this->actionPage) ."' ";

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

        //label
        $html .= $this->getLabel($el);

        // oggetto
        $html .= $el->make();

        if ($el->getContextMenuId() != '') {
            $html .= $el->getContextMenuHtml();
        }

        // chiusura label per checkbox

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

        if ($label != '') { // scartiamo oggetti che non hanno label, per esempio i titoli o i campi hidden
            $html .= '<label class="';

            if ($this->isCheckbox($el)) { // per checkbox
                $html .= 'checkbox " >';
            } elseif ($this->isRadio($el)) { // per checkbox
                $html .= 'radio " >';
            } else { // per tutti gli altri tipi di obj
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
        return UtString::areEqual($element->getElementType(), 'checkbox');
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
        return UtString::areEqual($element->getElementType(), 'div');
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
        return UtString::areEqual($element->getElementType(), 'radio');
    }
}
