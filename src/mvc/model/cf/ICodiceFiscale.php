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
    public function getValidCode(): bool;

    /**
     * @return string
     */
    public function getError(): string;

    /**
     * @return string
     */
    public function getSex(): string;

    /**
     * @return mixed
     */
    public function getPlaceBirth();

    /**
     * @return string
     */
    public function getYYBirth(): string;

    /**
     * @param bool $ultraCent
     *
     * @return string
     */
    public function getYYYYBirth(bool $ultraCent = false): string;

    /**
     * @return string
     */
    public function getMMBirth(): string;

    /**
     * @return string
     */
    public function getDDBirth(): string;
}
