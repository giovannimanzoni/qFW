<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\log;

/**
 * Class HtmlName
 *
 * Manage logs for be compatible with html code
 *
 * @package qFW\log
 */
class HtmlName extends AName implements ILogOutput
{
    /**
     * Get path for call right function by class name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getClassName(__NAMESPACE__, $this);
    }
}
