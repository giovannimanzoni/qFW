<?php declare(strict_types=1);

namespace qFW\mvc\controller\lang;

/**
 * Class LangIt
 *
 * @package qFW\mvc\controller\lang
 */
final class LangIt implements ILang
{
    /**
     * LangIt constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return 'IT';
    }
}
