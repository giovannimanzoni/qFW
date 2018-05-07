<?php declare(strict_types=1);

namespace qFW\mvc\controller\lang;

/**
 * Interface ILang
 *
 * @package qFW\mvc\controller\lang
 */
interface ILang
{
    /**
     * ILang constructor.
     */
    public function __construct();

    /**
     * @return string
     */
    public function getLang(): string;
}
