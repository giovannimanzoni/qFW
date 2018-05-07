<?php declare(strict_types=1);

namespace qFW\mvc\controller\lang;

/**
 * Class LangEn
 *
 * @package qFW\mvc\controller\lang
 */
final class LangEn implements ILang
{
    /**
     * LangEn constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return 'EN';
    }
}
