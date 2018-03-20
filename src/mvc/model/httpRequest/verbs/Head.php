<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\httpRequest\verbs;

/**
 * Class Head
 *
 * Head verb for API call
 *
 * @package qFW\mvc\model\httpRequest\verbs
 */
class Head implements IVerbs
{

    /**
     * Head constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get name of verb for call it
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'HEAD';
    }
}
