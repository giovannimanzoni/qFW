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
     * UtArray constructor.
     */
    public function __construct()
    {
    }

    /**
     * Check if there is duplicated values in the array
     *
     * @link https://stackoverflow.com/questions/1170807/how-to-detect-duplicate-values-in-php-array
     *
     * @param array $arr Array to check
     *
     * @return bool      Response
     */
    public function checkDuplicateValues(array $arr): bool
    {
        $retVal = false;
        if ($arr) {
            if (count(array_unique($arr)) < count($arr)) {
                $retVal = true;
            } else {
                /* Ok, return false*/
            }
        } else {
            /*Ok, empty array*/
        }

        return $retVal;
    }

    /**
     * Return the duplicate values of an array
     *
     * -> https://stackoverflow.com/questions/1259407/php-return-only-duplicated-entries-from-an-array
     *
     * @param array  $array
     * @param string $separator default ' '
     *
     * @return string
     */
    public function getArrayDuplicateValues(array $array, string $separator = ' '): string
    {
        return implode($separator, array_unique(array_diff_assoc($array, array_unique($array))));
    }

    /**
     * Look for at least one of the values ​​to look for in the reference array
     *
     * @param array $arr
     * @param array ...$values
     *
     * @return bool
     */
    public function searchValuesInArray(array $arr, ...$values): bool
    {
        $ret = false;
        foreach ($values as $val) {
            if (in_array($val, $arr)) {
                $ret = true;
                break;
            } else {
                /* Ck continue search*/
            }
        }
        return $ret;
    }
}
