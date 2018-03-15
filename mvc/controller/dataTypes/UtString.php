<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
// DO NOT ENABLE STRICT TYPE HERE

namespace qFW\mvc\controller\dataTypes;

use qFW\mvc\controller\form\FieldLenght;

/**
 * Class UtString
 *
 * @package qFW\mvc\controller\dataTypes
 */
class UtString
{

    /**
     * Check if two variable are equal
     *
     * @param $var1
     * @param $var2
     *
     * @return bool
     */
    public static function areEqual($var1, $var2): bool
    {
        if (($var1 <=> $var2) == 0) {
            $flag = true;
        } else {
            $flag = false;
        }

        return $flag;
    }

    /**
     * Check if one string is found into an other one.
     *
     * @param string $fullString   : full string
     * @param string $stringToFind : string to find
     *
     * @return bool                : true or false if string is found
     */
    public static function strSearch(string $fullString, string $stringToFind): bool
    {
        if (strpos($fullString, $stringToFind) !== false) {
            $found = true;
        } else {
            $found = false;
        }

        return $found;
    }

    /**
     * Convert variable in string
     *
     * @param $mixed
     *
     * @return string
     * @throws \Exception
     */
    public static function getCleanString($mixed): string
    {

        $cleanString=$mixed;

        if(!is_string($mixed)) {
            if(is_bool($mixed)) throw new \Exception('Can not clean boolean as string.');
            $cleanString = (strval($mixed));
        }
        return $cleanString;
    }

    /**
     * Format given parameter as price with comma as decimal separator and 2 decimal digit
     *
     * @param $value
     *
     * @return string
     * @throws \Exception
     */
    public static function formatPrice($value): string
    {
        return number_format(UtNumbers::getCleanFloat($value),2,',','');
    }

    /**
     * Wrapper per substr_count(), in caso in cui il nome non sia mnemonico..
     *
     * @param string $frase
     * @param string $cerca
     *
     * @return int
     */
    public static function strCount(string $frase, string $cerca): int
    {
        return substr_count($frase,$cerca);
    }

    /**
     * @param string $text
     * @param int    $truncate
     *
     * @return string
     */
    public static function limitString(string $text,int $truncate): string
    {

        return substr ( $text , 0 ,$truncate-3) . '...';
    }

    /**
     * Return float from given argument
     *
     * @param $mixed
     *
     * @return string
     */
    public static function floatToString($mixed): string
    {
        if(is_string($mixed)) {
            $ret = str_replace(',', '.', $mixed);
        }
        else {
            $ret=strval($mixed);
        }

        return  $ret;
    }


    /**
     * Return 'Sì' or 'No'.
     * .This method is usefull for HTML select in forms
     * I used 1 or 2 to avoid 0 as possible value when sanitize POST data and set to 0 if not present
     *
     * @param int $id
     *
     * @return string
     */
    public static function getSiNoString(int $id): string
    {
        $str='--';
        if($id==1) $str='Sì';
        else if($id==2) $str='No';
        return $str;
    }

    /**
     * Sum of numbers and get sum formatted as price
     *
     * @param array ...$prezzi
     *
     * @return string
     * @throws \Exception
     */
    public static function sommaPrezzi( ...$prezzi): string
    {

        $sum=0;
        foreach ( $prezzi as $prezzo ) {
            $sum += $prezzo;
        }
        return self::formatPrice($sum);
    }


    /**
     * Validazione password
     *
     * @param string $pwd   password
     *
     * @return string   messaggio di errore
     */
    public static function validatePwdMod1(string $pwd): string
    {
        $err='';
        if (strlen($pwd) <= '8') {
            $err = 'La password deve contenere almeno 8 caratteri.';
        }
        elseif (strlen($pwd) > FieldLenght::DIM_PWD) {
            $err = 'La password deve contenere meno di '.FieldLenght::DIM_PWD.' caratteri.';
        }
        elseif(!preg_match("#[0-9]+#",$pwd)) {
            $err = 'La password deve contenere almeno un numero.';
        }
        elseif(!preg_match("#[A-Z]+#",$pwd)) {
            $err = 'La password deve contenere almeno una lettera maiuscola.';
        }
        elseif(!preg_match("#[a-z]+#",$pwd)) {
            $err = 'La password deve contenere almeno una lettera minuscola.';
        }
        return $err;
    }

}
