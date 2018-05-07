<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\log\None;

use qFW\log\ALogger;
use qFW\log\ILogger;

/**
 * Class None
 *
 * Disable log without breaking the code
 *
 * @package qFW\log\None
 */
class None extends ALogger implements ILogger
{

    /**
     * Empty function for do not break proxy engine
     *
     * @return string
     */
    public function getLogs(): string
    {
        $html='';
        unset($_SESSION['ALOGGER']);
        return $html;
    }
}
