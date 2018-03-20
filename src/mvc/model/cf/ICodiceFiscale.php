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
    public function setCF(string $cf);

    /**
     * @return bool
     */
    public function getCodiceValido(): bool;

    /**
     * @return string
     */
    public function getErrore(): string;

    /**
     * @return string
     */
    public function getSesso(): string;

    /**
     * @return mixed
     */
    public function getComuneNascita();

    /**
     * @return string
     */
    public function getAANascita(): string;

    /**
     * @param bool $ultraCent
     *
     * @return string
     */
    public function getAAAANascita(bool $ultraCent = false): string;

    /**
     * @return string
     */
    public function getMMNascita(): string;

    /**
     * @return string
     */
    public function getGGNascita(): string;
}
