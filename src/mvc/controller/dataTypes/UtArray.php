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
 * Class UtArray
 *
 * Factory
 *
 * @package qFW\mvc\controller\dataTypes
 */
class UtArray
{

    /**
     * Check if there is duplicated values in the array
     *
     * @link https://stackoverflow.com/questions/1170807/how-to-detect-duplicate-values-in-php-array
     *
     * @param array $arr : array to check
     *
     * @return bool         : response
     */
    public static function checkDuplicateValues(array $arr): bool
    {
        $retVal = false;
        if ($arr) {
            if (count(array_unique($arr)) < count($arr)) {
                $retVal = true;
            }
        }

        return $retVal;
    }

    /**
     * Restituisce i valori duplicati di un array
     *
     * -> https://stackoverflow.com/questions/1259407/php-return-only-duplicated-entries-from-an-array
     *
     * @param array $array
     *
     * @return string
     */
    public static function getArrayDuplicateValues(array $array): string
    {
        return implode(' ', array_unique(array_diff_assoc($array, array_unique($array))));
    }

    /**
     * Cerca se nell'array di riferimento sono presenti almeno uno dei valori da cercare
     *
     * @param array $arr
     * @param array ...$values
     *
     * @return bool
     */
    public static function searchValuesInArray(array $arr, ...$values): bool
    {
        $ret = false;
        foreach ($values as $val) {
            if (in_array($val, $arr)) {
                $ret = true;
                break;
            }
        }
        return $ret;
    }
}
