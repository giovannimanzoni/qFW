<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\controller\dataTypes;

/**
 * Class UtNumbers
 *
 * @package qFW\mvc\controller\dataTypes
 */
class UtNumbers
{
    /**
     * UtNumbers constructor.
     */
    public function __construct()
    {
    }

    /**
     * Return float from given argument
     *
     * @param $mixed
     *
     * @return float
     * @throws \Exception
     */
    public function getCleanFloat($mixed): float
    {

        // If string, change ',' in '.'
        if (is_string($mixed)) {
            $tmp = str_replace(',', '.', $mixed);
            $cleanFloat = floatval($tmp);
        } elseif (is_numeric($mixed)) {
            if (!is_float($mixed)) {
                $cleanFloat = floatval($mixed);
            } else {
                $cleanFloat = $mixed;
            }
        } else {
            if ($mixed === false) {
                $str = 'boolean false';
            } else {
                if ($mixed === true) {
                    $str = 'boolean true';
                } else {
                    $str = $mixed;
                }
            }

            throw new \Exception("getCleanFloat() Impossible convert $str to float");
        }

        return $cleanFloat;
    }

    /**
     * Convert given parameter to int
     *
     * @fixme non far tornare 0 ma exception
     *
     * @param $mixed
     *
     * @return int
     */
    public function getCleanInt($mixed): int
    {
        if (is_numeric($mixed)) {
            if (!is_int($mixed)) {
                $cleanInt = intval($mixed);
            } else {
                $cleanInt = $mixed;
            }
        } else {
            $cleanInt = (int)$mixed;
        }

        return $cleanInt;
    }

    /**
     * Convert given parameter to boolean
     *
     * @param $mixed
     *
     * @return bool
     * @throws \Exception
     */
    public function getCleanBool($mixed): bool
    {

        if (is_bool($mixed)) {
            $cleanBool = $mixed;
        } else {
            $int = $this->getCleanInt($mixed);
            if ($int == 1) {
                $cleanBool = true;
            } elseif ($int == 0) {
                $cleanBool = false;
            } else {
                throw new \Exception('getCleanBool() Not boolean argument.');
            }
        }

        return $cleanBool;
    }
}
