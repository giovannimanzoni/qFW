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
use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\vocabulary\Voc;

/**
 * Class UtString
 *
 * @package qFW\mvc\controller\dataTypes
 */
class UtString
{
    private const YES = 1;

    private const NO = 2;

    /** @var \qFW\mvc\controller\lang\ILang */
    private $lang;

    /** @var \qFW\mvc\controller\dataTypes\UtNumbers */
    private $utNum;

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    /**
     * UtString constructor.
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(ILang $lang)
    {
        $this->lang = $lang;
        $this->utNum = new UtNumbers();

        $vocLang = 'qFW\mvc\controller\vocabulary\Voc' . $this->lang->getLang();
        $this->voc = new $vocLang();
    }

    /**
     * Check if two variable are equal
     *
     * @param $var1
     * @param $var2
     *
     * @return bool
     */
    public function areEqual($var1, $var2): bool
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
     * @param string $fullString   Full string
     * @param string $stringToFind String to find
     *
     * @return bool                True or false if string is found
     */
    public function strSearch(string $fullString, string $stringToFind): bool
    {
        if (strpos($fullString, $stringToFind) !== false) {
            $found = true;
        } else {
            $found = false;
        }

        return $found;
    }

    /**
     * Convert variable to string
     *
     * @param $mixed
     *
     * @return string
     * @throws \Exception
     */
    public function getCleanString($mixed): string
    {
        $cleanString = $mixed;

        if (!is_string($mixed)) {
            if (is_bool($mixed)) {
                throw new \Exception('Can not clean boolean as string.');
            } else {
                $cleanString = (strval($mixed));
            }
        } else {
            /*Ok, is string*/
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
    public function formatPrice($value): string
    {
        return number_format($this->utNum->getCleanFloat($value), 2, ',', '');
    }

    /**
     * Wrapper for substr_count(), for more mnemonic name..
     *
     * @param string $text
     * @param string $search
     *
     * @return int
     */
    public function strCount(string $text, string $search): int
    {
        return substr_count($text, $search);
    }

    /**
     * @param string $text
     * @param int    $truncate
     *
     * @return string
     */
    public function limitString(string $text, int $truncate): string
    {
        return substr($text, 0, $truncate - 3) . '...';
    }

    /**
     * Return float from given argument
     *
     * @param $mixed
     *
     * @return string
     */
    public function floatToString($mixed): string
    {
        if (is_string($mixed)) {
            $ret = str_replace(',', '.', $mixed);
        } else {
            $ret = strval($mixed);
        }

        return $ret;
    }

    /**
     * Return 'Yes' or 'No'.
     * This method is usefull for HTML select in forms
     * We used 1 and 2 to avoid 0 as possible value when sanitize POST data and set to 0 if not present
     *
     * @param int $id
     *
     * @return string
     */
    public function getYesNoString(int $id): string
    {
        $str = '--';
        if ($id == self::YES) {
            $str = $this->voc->yesC();
        } elseif ($id == self::NO) {
            $str = $this->voc->noC();
        } else {
            /*Ok, return default value*/
        }
        return $str;
    }

    /**
     * Sum of numbers and get sum formatted as price
     *
     * @param array ...$prices
     *
     * @return string
     * @throws \Exception
     */
    public function sumPrices(...$prices): string
    {
        $sum = 0;
        foreach ($prices as $price) {
            $sum += $price;
        }
        return self::formatPrice($sum);
    }

    /**
     * Password validation
     *
     * @param string $pwd password
     *
     * @return string   error message
     */
    public function validatePwdMod1(string $pwd): string
    {
        $err = '';
        if (strlen($pwd) < '8') {
            $err = $this->voc->pwdMust8Chrs();
        } elseif (strlen($pwd) > FieldLenght::DIM_PWD) {
            $err = $this->voc->pwdMustMaxChrs();
        } elseif (!preg_match("#[0-9]+#", $pwd)) {
            $err = $this->voc->pwdMustOneNumber();
        } elseif (!preg_match("#[A-Z]+#", $pwd)) {
            $err = $this->voc->pwdMustUppercaseChrs();
        } elseif (!preg_match("#[a-z]+#", $pwd)) {
            $err = $this->voc->pwdMustTinyChrs();
        } else {
            /*Ok, pwd is valid*/
        }
        return $err;
    }
}
