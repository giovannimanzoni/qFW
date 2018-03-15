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

    /**
     * @param array $arrCf
     *
     * @return bool
     * @throws \Exception
     */
    public static function areCf(array $arrCf): bool
    {
        $valid=true;
        $codeCf = new CodiceFiscale();

        foreach($arrCf as $cf) {
            $codeCf ->SetCF($cf);
            $valid &= self::validateCf($codeCf);
        }

        return (bool)$valid;
    }

    /**
     * @param \qFW\mvc\model\cf\ICodiceFiscale $cf
     *
     * @return bool
     */
    public static function validateCf(ICodiceFiscale $cf)
    {
        return $cf->GetCodiceValido();
    }



    /**
     * @param array $notEmpty
     *
     * @return bool
     */
    public static function areNotEmpty(array $notEmpty): bool
    {
        $valid=true;

        foreach($notEmpty as $val) {
            $valid &= self::validateNotEmpty($val);
        }

        return (bool)$valid;
    }

    /**
     * @param $val
     *
     * @return bool
     */
    public static function validateNotEmpty($val)
    {
        if($val!='') $ret=true;
        else $ret=false;
        return$ret;
    }

    /**
     * @param array $numeri
     *
     * @return bool
     */
    public static function areNumbers(array $numeri): bool
    {
        $valid=true;

        foreach($numeri as $numero) {
            $valid &= self::validateNumero($numero);
        }

        return (bool)$valid;
    }

    /**
     * @param array $date
     *
     * @return bool
     */
    public static function areDate(array $date): bool
    {
        $valid=true;

        foreach($date as $data) {
            $valid &= self::validateDate($data);
        }

        return (bool)$valid;
    }

    /**
     * @param array $ids
     *
     * @return bool
     */
    public static function areIds(array $ids): bool
    {
        $valid=true;

        foreach($ids as $id) {
            $valid &= self::validateId($id);
        }

        return (bool)$valid;
    }

    /**
     * @param int $num
     *
     * @return bool
     */
    public static function validateId(int $num): bool
    {

        if ($num>0) $ret=true;
        else        $ret = false; // non valido id nel database == a 0

        return $ret;
    }


// https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format

    /**
     * @param string $date
     *
     * @return bool
     */
    public static function validateDate(string $date): bool
    {
        $valid=true;

        if(!is_null($date)) {
            $d = \DateTime::createFromFormat('d/m/Y', $date);
            $valid = ($d && $d->format('d/m/Y') === $date);
        }
        else { /* data = non disponibile */ }

        return (bool) $valid;
    }

    /**
     * @param $numero
     *
     * @return bool
     */
    public static function validateNumero($numero): bool
    {
        return (bool) is_numeric($numero);
    }

    /**
     * @param array $text
     *
     * @return bool
     */
    public static function areText(array $text): bool
    {
        $valid=true;

        foreach($text as $testo) {
            if($testo=='') $valid=false;
            else {/*ok*/}
        }
        return $valid;
    }

    /**
     * @param array $ips
     *
     * @return bool
     */
    public static function areIps(array $ips): bool
    {
        $valid=true;

        foreach($ips as $ip) {

            // se ip contiene piu ip separati da '|'
            if(UtString::strSearch($ip,'|')){

                $arrExpl=explode('|', $ip);
                foreach($arrExpl as $part) {
                    $valid &= self::isIp($part);
                }
            }
            else $valid &= self::isIp($ip);
        }

        return (bool) $valid;
    }

    /**
     * @param array $paths
     *
     * @return bool
     */
    public static function arePaths(array $paths): bool
    {
        $valid=true;

        foreach($paths as $path) $valid &= self::isPath($path);

        return (bool)$valid;
    }

    /**
     * @param array $ports
     *
     * @return bool
     */
    public static function arePorts(array $ports): bool
    {
        $valid=true;

        foreach($ports as $porta) $valid &= self::isPort($porta);

        return (bool)$valid;
    }

    /**
     * @param array $urls
     *
     * @return bool
     */
    public static function areUrls(array $urls): bool
    {
        $valid=true;
        foreach($urls as $url) $valid &= self::isUrl($url);
        return (bool)$valid;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public static function isUrl(string $url): bool
    {
        $exist= Sanitize::isValidUrl($url);
        if(!$exist) $_SESSION['err'].="Il dominio $url non esiste.";
        else{/*ok*/}
        return $exist;
    }

    /**
     * @param string $ip
     *
     * @return bool
     */
    public static function isIp(string $ip): bool
    {
        $valid=false;

        // se ci sono solo 3 '.'
        if(UtString::areEqual('localhost',$ip)) $valid=true;
        elseif(UtString::strCount($ip,".")==3) {
            $partialValid=0;

            // esplode per '.' e controlla che ogni gruppo sia numerico e compresto tra 0 e 255
            $arrExpl=explode('.', $ip, 4);
            foreach($arrExpl as $part){
                if(is_numeric($part)) {
                    if (($part < 256) && ($part >= 0))
                        $partialValid++;
                    else{/*ok*/}
                }
                else{/*ok*/}
            }

            // se i 4 gruppi di numeri sono validi
            if($partialValid==4)
                $valid=true;
        }

        return $valid;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public static function isPath(string $path): bool
    {
        $valid=false;

        if((substr($path, -1)=='/') && (!UtString::strSearch($path, ':')) )$valid=true;
        else{/*ok false*/}
        return $valid;
    }

    /**
     * @param $porta
     *
     * @return bool
     */
    public static function isPort($porta): bool
    {
        $valid=false;

        if(is_int($porta)) {
            if (($porta < 65535) && ($porta > 0)) { // 0 is reserved
                if (($porta)) {
                    $valid = true;
                }
            }
            else {/*ok false*/}
        }
        else {/*ok false*/}
        return $valid;
    }

}
