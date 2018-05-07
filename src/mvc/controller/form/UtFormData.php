<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\controller\form;

use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\url\Sanitize;
use qFW\mvc\model\cf\CodiceFiscale;
use qFW\mvc\model\cf\ICodiceFiscale;

/**
 * Class UtFormData
 *
 * @package qFW\mvc\controller\form
 */
class UtFormData
{
    /** @var \qFW\mvc\controller\dataTypes\UtString */
    private $utStr;

    /** @var \qFW\mvc\controller\lang\ILang */
    private $lang;

    /** @var \qFW\mvc\controller\url\Sanitize */
    private $sanitize;

    /**
     * UtFormData constructor.
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(ILang $lang)
    {
        $this->lang = $lang;
        $this->utStr = new UtString($lang);
        $this->sanitize = new Sanitize();
    }

    /**
     * Validate array of italian personal id card number
     *
     * @param array $arrCf
     *
     * @return bool
     * @throws \Exception
     */
    public function areCf(array $arrCf): bool
    {
        $valid = true;
        $codeCf = new CodiceFiscale($this->lang);

        foreach ($arrCf as $cf) {
            $codeCf->setCF($cf);
            $valid &= $this->validateCf($codeCf);
        }

        return (bool)$valid;
    }

    /**
     * @param \qFW\mvc\model\cf\ICodiceFiscale $cf
     *
     * @return bool
     */
    public function validateCf(ICodiceFiscale $cf)
    {
        return $cf->getValidCode();
    }


    /**
     * @param array $notEmpty
     *
     * @return bool
     */
    public function areNotEmpty(array $notEmpty): bool
    {
        $valid = true;

        foreach ($notEmpty as $val) {
            $valid &= $this->validateNotEmpty($val);
        }

        return (bool)$valid;
    }

    /**
     * @param $val
     *
     * @return bool
     */
    public function validateNotEmpty($val)
    {
        if ($val != '') {
            $ret = true;
        } else {
            $ret = false;
        }
        return $ret;
    }

    /**
     * @param array $numbers
     *
     * @return bool
     */
    public function areNumbers(array $numbers): bool
    {
        $valid = true;

        foreach ($numbers as $number) {
            $valid &= $this->validateNumber($number);
        }

        return (bool)$valid;
    }

    /**
     * @param array $date
     *
     * @return bool
     */
    public function areDate(array $date): bool
    {
        $valid = true;

        foreach ($date as $data) {
            $valid &= $this->validateDate($data);
        }

        return (bool)$valid;
    }

    /**
     * @param array $ids
     *
     * @return bool
     */
    public function areIds(array $ids): bool
    {
        $valid = true;

        foreach ($ids as $id) {
            $valid &= $this->validateId($id);
        }

        return (bool)$valid;
    }

    /**
     * @param int $num
     *
     * @return bool
     */
    public function validateId(int $num): bool
    {

        if ($num > 0) {
            $ret = true;
        } else {
            $ret = false;
        } // 0 is not a valid id in MySQL database

        return $ret;
    }

    /**
     * @param string $date
     * -> https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
     *
     * @return bool
     */
    public function validateDate(string $date): bool
    {
        $valid = true;

        if (!is_null($date)) {
            $d = \DateTime::createFromFormat('d/m/Y', $date);
            $valid = ($d && $d->format('d/m/Y') === $date);
        } else { /* data = not available */
        }

        return (bool)$valid;
    }

    /**
     * @param $number
     *
     * @return bool
     */
    public function validateNumber($number): bool
    {
        return (bool)is_numeric($number);
    }

    /**
     * @param array $text
     *
     * @return bool
     */
    public function areText(array $text): bool
    {
        $valid = true;

        foreach ($text as $testo) {
            if ($testo == '') {
                $valid = false;
            } else {/*ok*/
            }
        }
        return $valid;
    }

    /**
     * @param array $ips
     *
     * @return bool
     */
    public function areIps(array $ips): bool
    {
        $valid = true;

        foreach ($ips as $ip) {
            // If IP contains more ips separated by '|'
            if ($this->utStr->strSearch($ip, '|')) {
                $arrExpl = explode('|', $ip);
                foreach ($arrExpl as $part) {
                    $valid &= $this->isIp($part);
                }
            } else {
                $valid &= $this->isIp($ip);
            }
        }

        return (bool)$valid;
    }

    /**
     * @param array $paths
     *
     * @return bool
     */
    public function arePaths(array $paths): bool
    {
        $valid = true;

        foreach ($paths as $path) {
            $valid &= $this->isPath($path);
        }

        return (bool)$valid;
    }

    /**
     * Check if is an array of network ports
     *
     * @param array $ports
     *
     * @return bool
     */
    public function arePorts(array $ports): bool
    {
        $valid = true;

        foreach ($ports as $porta) {
            $valid &= $this->isPort($porta);
        }

        return (bool)$valid;
    }

    /**
     * @param array $urls
     * @param bool  $testCurl
     *
     * @return bool
     * @throws \Exception
     */
    public function areUrls(array $urls, bool $testCurl = true): bool
    {
        $valid = true;
        foreach ($urls as $url) {
            $valid &= $this->isUrl($url, $testCurl);
        }
        return (bool)$valid;
    }


    /**
     * @param string $url
     * @param bool   $testCurl
     *
     * @return bool
     * @throws \Exception
     */
    public function isUrl(string $url, bool $testCurl = true): bool
    {
        return $this->sanitize->isValidUrl($url, $testCurl);
    }

    /**
     * @param array $emails
     * @param bool  $testCurl
     *
     * @return bool
     * @throws \Exception
     */
    public function areEmails(array $emails, bool $testCurl = true): bool
    {
        $urlEmailArr = array();
        $valid = true;

        // Extract all email domain
        foreach ($emails as $urlEmail) {
            // Email has got only one @ ?
            $urlEmailTmp = explode('@', $urlEmail, 2);
            if (count($urlEmailTmp) != 2) {
                $valid = false;
                break;
            }

            // Does this email have got a new domain ?
            if (!array_key_exists($urlEmailTmp, $urlEmailArr)) {
                $urlEmailArr[$urlEmailTmp] = true;
            } else {
                /*ok It has already been found */
            }
        }

        if ($valid) {
            foreach ($urlEmailArr as $urlEmail) {
                $valid &= $this->isUrl("http://$urlEmail", $testCurl);
                if (!$valid) {
                    break;
                } // break because check if url is a valid domain is a high load task
            }
        }

        return (bool)$valid;
    }

    /**
     * Chech if
     * - there is only one @
     * - domain url is a valid domain
     *
     * @param string $email
     * @param bool   $testCurl
     *
     * @return bool
     * @throws \Exception
     */
    public function isEmail(string $email, bool $testCurl = true): bool
    {
        $ret = false;

        $urlEmailTmp = explode('@', $email, 2);
        if (count($urlEmailTmp) == 2) {
            $urlEmail = "http://{$urlEmailTmp[1]}";
            $ret = $this->isUrl($urlEmail, $testCurl);
        }

        return $ret;
    }


    /**
     * @param string $ip
     *
     * @return bool
     */
    public function isIp(string $ip): bool
    {
        $valid = false;

        if ($this->utStr->areEqual('localhost', $ip)) {
            $valid = true;
        } elseif ($this->utStr->strCount($ip, ".") == 3) { // If there are only three '.'
            $partialValid = 0;

            // Explode by '.' and check every group is numeric and from 0 to 255
            $arrExpl = explode('.', $ip, 4);
            foreach ($arrExpl as $part) {
                if (is_numeric($part)) {
                    if (($part < 256) && ($part >= 0)) {
                        $partialValid++;
                    } else {/*ok*/
                    }
                } else {/*ok*/
                }
            }

            // If all 4 groups are valid
            if ($partialValid == 4) {
                $valid = true;
            } else {
                /*Ok, false*/
            }
        }

        return $valid;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function isPath(string $path): bool
    {
        $valid = false;

        if ((substr($path, -1) == '/') && (!$this->utStr->strSearch($path, ':'))) {
            $valid = true;
        } else {/*Ok false*/
        }
        return $valid;
    }

    /**
     * @param $porta
     *
     * @return bool
     */
    public function isPort($porta): bool
    {
        $valid = false;

        if (is_int($porta)) {
            if (($porta < 65535) && ($porta > 0)) { // 0 is reserved
                if (($porta)) {
                    $valid = true;
                }
            } else {/*Ok false*/
            }
        } else {/*Ok false*/
        }
        return $valid;
    }
}
