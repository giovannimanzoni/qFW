<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);
namespace qFW\mvc\view\form\elements;

use qFW\mvc\view\form\TError;
use qFW\mvc\view\form\TGlobalAttributes;

/**
 * Class FormDiv
 *
 * For use div inside html form
 *
 * @package qFW\mvc\view\form\elements
 */
class FormDiv implements IFormElements
{
    /** @var string html id  */
    private $id='';

    use TError;
    use TGlobalAttributes;

    /**
     * FormDiv constructor.
     *
     * @param string $id html id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /*********************************************************************************************************
     * metodi definiti nell'interfaccia ma non usati
     ********************************************************************************************************/

    // @codingStandardsIgnoreStart

    public function setDefaultValue(int $defaultValue)
    {
    }

    public function setLabel(string $label)
    {
    }

    public function setLabelOnTop()
    {
    }

    public function setDefaultText(string $text)
    {
    }

    public function getLabel(): string
    {
        return '';
    }

    public function isHorizontal(): bool
    {
        return true;
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function isRatioSetted(): bool
    {
        return false;
    }

    public function isLabelDisabled(): bool
    {
        return false;
    }

    public function getElementType(): string
    {
        return 'div';
    }

    public function setElementRowClass(string $class)
    {
    }

    public function setElementRatio(int $col2)
    {

    }

    public function getElementRowClass(): string
    {
        return 'col-xs-12';
    }

    public function getElementDim(): int
    {
        return self::BOOTSTRAP_COLUMNS;
    }

    public function getId(): string
    {
        return $this->id;
    }
    // @codingStandardsIgnoreEnd


    /*********************************************************************************************************
     * metodi usati
     ********************************************************************************************************/

    /**
     * Check all possible errors
     *
     * @return bool response. true if no error found
     */
    public function check(): bool
    {
        return true;
    }

    /**
     * Make html
     *
     * @return string return html
     */
    public function make(): string
    {
        $html = "<div id='{$this->id}'></div>";
        return $html;
    }
}
