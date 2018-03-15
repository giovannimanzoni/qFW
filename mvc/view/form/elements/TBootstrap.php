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
    private $horizontal = true;  // true: label a sx, false: label sopra all'oggetto


    /*************************************************
     * usati per la creazione dell'oggetto
     ************************************************/

    // Imposta label sopra agli oggetti. default orizzontale
    public function setLabelOnTop()
    {
        $this->horizontal = false;
        $this->col1 = 12;
        $this->col2 = 12;
        return $this;
    }

    // Imposta rapporto tra label e oggetto se messi in orizzontale. default label = 3 e obj = 9
    public function setElementRatio(int $col2)
    {
        $this->col1 = self::BOOTSTRAP_COLUMNS - $col2;
        $this->col2 = $col2;
        $this->ratioSetted = true;
        return $this;
    }

    // Imposta la dimensione di label+oggetto sulla riga di bootstrap.
    //  default impostato a BOOTSTRAP_COLUMNS cioè un solo oggetto pewr riga
    public function setElementRowClass(string $class)
    {
        $this->rowClass = $class;
        return $this;
    }


    /*************************************************
     * usati per la costruzione del form
     ************************************************/

    public function getElementDim(): int
    {
        return $this->col2;
    }

    public function isHorizontal(): bool
    {
        return $this->horizontal;
    }

    public function getElementRowClass(): string
    {
        return $this->rowClass;
    }

    public function isRatioSetted(): bool
    {
        return $this->ratioSetted;
    }

    public function getCol1(): int
    {
        return $this->col1;
    }

    public function getCol2(): int
    {
        return $this->col2;
    }
}
