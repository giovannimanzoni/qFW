<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\model\cf;

/**
 * Interface ICodiceFiscale
 *
 * @package qFW\mvc\model\cf
 */
interface ICodiceFiscale
{
    /**
     * @param string $cf
     *
     * @return mixed
     */
    public function SetCF(string $cf);

    /**
     * @return bool
     */
    public function GetCodiceValido(): bool;

    /**
     * @return string
     */
    public function GetErrore(): string;

    /**
     * @return string
     */
    public function GetSesso(): string;

    /**
     * @return mixed
     */
    public function GetComuneNascita();

    /**
     * @return string
     */
    public function GetAANascita(): string;

    /**
     * @param bool $ultraCent
     *
     * @return string
     */
    public function GetAAAANascita(bool $ultraCent=false) : string;

    /**
     * @return string
     */
    public function GetMMNascita(): string;

    /**
     * @return string
     */
    public function GetGGNascita(): string;

}
