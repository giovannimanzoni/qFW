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

/**
 * Trait TBootstrap
 *
 * Manage Bootstrap features
 *
 * @package qFW\mvc\view\form\elements
 */
trait TBootstrap
{
    private $col1 = 0;
    private $col2 = 0;
    private $ratioSetted = false;
    private $rowClass = 'col-xs-12';
    private $horizontal = true;  // True: label a sx, false: label sopra all'oggetto


    /*************************************************
     * Used for creating the object
     ************************************************/

    /**
     * Set label above objects. horizontal default
     *
     * @return $this
     */
    public function setLabelOnTop()
    {
        $this->horizontal = false;
        $this->col1 = 12;
        $this->col2 = 12;
        return $this;
    }

    /**
     * Set ratio between label and object if placed horizontally. default label = 3 e obj = 9
     *
     * @param int $col2
     *
     * @return $this
     */
    public function setElementRatio(int $col2)
    {
        $this->col1 = self::BOOTSTRAP_COLUMNS - $col2;
        $this->col2 = $col2;
        $this->ratioSetted = true;
        return $this;
    }

    /**
     * Set the size of label+object on the bootstrap line.
     *      default set toBOOTSTRAP_COLUMNS that is, only one object per line
     * @param string $class
     *
     * @return $this
     */
    public function setElementRowClass(string $class)
    {
        $this->rowClass = $class;
        return $this;
    }


    /*************************************************
     * Used for the construction of the form
     ************************************************/

    /**
     * @return int
     */
    public function getElementDim(): int
    {
        return $this->col2;
    }

    /**
     * @return bool
     */
    public function isHorizontal(): bool
    {
        return $this->horizontal;
    }

    /**
     * @return string
     */
    public function getElementRowClass(): string
    {
        return $this->rowClass;
    }

    /**
     * @return bool
     */
    public function isRatioSetted(): bool
    {
        return $this->ratioSetted;
    }

    /**
     * @return int
     */
    public function getCol1(): int
    {
        return $this->col1;
    }

    /**
     * @return int
     */
    public function getCol2(): int
    {
        return $this->col2;
    }
}
