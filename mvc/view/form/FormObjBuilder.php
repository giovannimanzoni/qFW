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

use qFW\log\ILogOutput;

/**
 * Class FormObjBuilder
 *
 * Form builder for build form
 *
 * @package qFW\mvc\view\form
 */
class FormObjBuilder implements IFormObjBuilder
{
    /** @var array  hold all pages of the form*/
    private $formPages = array();

    /** @var string  symbol to use to mark form elements as required in label description*/
    private $requiredSimbol = '*';

    /** @var string string to use to explain that marked elements are required */
    private $requiredString = ' = campo obbligatorio.';

    use TError;

    /***********************************
     * popolamento del form
     **********************************/
    /**
     * FormObjBuilder constructor.
     *
     * @param \qFW\log\ILogOutput $outputLog
     */
    public function __construct(ILogOutput $outputLog)
    {
        $this->createLogger($outputLog);
    }

    /***********************************
     * pagine
     **********************************/

    /**
     * Add page to the form
     *
     * @param \qFW\mvc\view\form\IFormPage $page
     *
     * @return \qFW\mvc\view\form\FormObjBuilder
     */
    public function addPage(IFormPage $page): FormObjBuilder
    {
        $pageName = $page->getPageName();
        if (array_key_exists($pageName, $this->formPages)) {
            $this->addLog("Aggiunta pagina con nome già presente: $pageName.");
        } else {
            $this->formPages[$pageName] = $page;
        }
        return $this;
    }

    /**
     * Get pages name
     *
     * @return array
     */
    public function getPagesName(): array
    {
        $names = array();
        foreach ($this->formPages as $page) {
            $names[] = $this->getPageName($page);
        }
        return $names;
    }

    /**
     * Get page name
     *
     * @param \qFW\mvc\view\form\IFormPage $page
     *
     * @return string
     */
    private function getPageName(IFormPage $page)
    {
        return $page->getPageName();
    }


    /***********************************
     * elementi
     **********************************/

    /**
     * Get element of given page name
     *
     * @param string $pageName
     *
     * @return array
     */
    public function getElements(string $pageName): array
    {
        $ret = array();

        if (array_key_exists($pageName, $this->formPages)) {
            $ret = $this->getPageElements($this->formPages[$pageName]);
        } else {
            $this->addLog("La pagina $pageName non eiste.");
        }

        return $ret;
    }

    /**
     * Return array of element for this page
     *
     * @param \qFW\mvc\view\form\IFormPage $page
     *
     * @return array
     */
    private function getPageElements(IFormPage $page): array
    {
        return $page->getPageElements();
    }

    /**
     * Build methods for builder design pattern
     *
     * @return \qFW\mvc\view\form\FormObjBuilder
     */
    public function build(): FormObjBuilder
    {
        $numPagine = count($this->formPages);

        if ($numPagine == 0) {
            $this->addLog('Form has got no pages.');
        }

        return $this;
    }

    /**
     * Check if form builder options are valid
     *
     * @return bool
     */
    public function check(): bool
    {
        return $this->getCheckEsito();
    }

    /***********************************
     * proprietà del form
     **********************************/

    /**
     * Set required symbol for the label
     *
     * @param string $symbol
     *
     * @return \qFW\mvc\view\form\FormObjBuilder
     */
    public function setRequiredSymbol(string $symbol): FormObjBuilder
    {
        $this->requiredSimbol = $symbol;
        return $this;
    }

    /**
     * Set string for required form element
     *
     * @param string $text
     *
     * @return \qFW\mvc\view\form\FormObjBuilder
     */
    public function setRequiredString(string $text): FormObjBuilder
    {
        $this->requiredString = $text;
        return $this;
    }

    /**
     * Get the symbol used for mark required form elements
     *
     * @return string
     */
    public function getRequiredSymbol(): string
    {
        return $this->requiredSimbol;
    }

    /**
     * Get string for required form element
     *
     * @return string
     */
    public function getRequiredString(): string
    {
        return $this->requiredString;
    }
}
