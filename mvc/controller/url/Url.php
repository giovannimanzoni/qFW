<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
declare(strict_types=1);

namespace qFW\mvc\controller\url;


/**
 * Class Url
 *
 * @package qFW\mvc\controller\url
 */
class Url
{

    /**
     * @param string $page
     */
    public static function redirect(string $page)
    {
        header('Location: '.self::makeUrl($page));
        ob_end_flush();
        exit;
    }


    /**
     * @param string $basePath
     *
     * @todo usare design pattern x istanziare tuttii calcoli una volta sola e leggerli da variabiel una volta calcolati
     *
     * @return string
     */
    public static function makeUrl(string $basePath, bool $setHttps=false): string
    {
        if($setHttps) {
            $https=true;
            $port=$defaultPort=443;
        } else {
            $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && strtolower($_SERVER['HTTPS']) !== 'off';
            $port = $_SERVER['SERVER_PORT'];
            $defaultPort = $https ? 443 : 80;
        }
        $baseUrl=($https ? 'https://' : 'http://')
            .$_SERVER['SERVER_NAME'].($port==$defaultPort ? '' : ':'.$port)
            .$basePath;

        return $baseUrl;
    }


    /**
     * Get name of the script
     *
     * @return string
     */
    public static function getScriptDirName(): string
    {
        return rtrim(dirname($_SERVER['SCRIPT_FILENAME']), '\/');
    }

    /**
     * @return string
     */
    public static function getScriptName(): string
    {
        return basename($_SERVER['PHP_SELF']);
    }


}
