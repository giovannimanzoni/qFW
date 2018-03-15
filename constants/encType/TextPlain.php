<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\constants\encType;

/**
 * Class TextPlain
 *
 * Get text/plain encoding string for Forms
 *
 * @package qFW\constants\encType
 */
class TextPlain implements IEncType
{
    /**
     * TextPlain constructor.
     */
    public function __construct()
    {
    }

    /**
     * Return specific string for html form
     *
     * @return string
     */
    public function getCode(): string
    {
        return 'text/plain';
    }
}
