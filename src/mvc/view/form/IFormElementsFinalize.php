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

/**
 * Interface IFormElementsFinalize
 *
 * Standardize methods for all form html elements
 *
 * @package qFW\mvc\view\form
 */
interface IFormElementsFinalize
{
    /**
     * Check if this form element has got some errors
     *
     * @return bool
     */
    public function check(): bool;

    /**
     * Make html for this form element
     *
     * @return string   html
     */
    public function make(): string;

    /**
     * Get logs for this form element
     *
     * @return string
     */
    public function getLogs(): string;
}
