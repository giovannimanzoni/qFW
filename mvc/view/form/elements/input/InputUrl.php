<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\form\elements\input;

/**
 * Class InputUrl
 *
 * @package qFW\mvc\view\form\elements\input
 */
class InputUrl implements IFormInput
{

    /**
     * Get type of input
     *
     * @return string
     */
    public function getType(): string
    {
        return 'url';
    }

    /**
     * Return if all the property set are valid
     *
     * @return bool
     */
    public function check(): bool
    {
        return true;
    }
}
