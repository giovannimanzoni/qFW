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
     * Return float from given argument
     *
     * @param $mixed
     *
     * @return float
     * @throws \Exception
     */
    public static function getCleanFloat($mixed): float
    {

        // se stringa, cambia virgola in punto
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

            throw new \Exception("Impossibile trasformare $str in float");
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
    public static function getCleanInt($mixed): int
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
    public static function getCleanBool($mixed): bool
    {

        if (is_bool($mixed)) {
            $cleanBool = $mixed;
        } else {
            $int = self::getCleanInt($mixed);
            if ($int == 1) {
                $cleanBool = true;
            } elseif ($int == 0) {
                $cleanBool = false;
            } else {
                throw new \Exception('Not boolean argument.');
            }
        }

        return $cleanBool;
    }
}
