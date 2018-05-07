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
 * For web url management
 *
 * @package qFW\mvc\controller\url
 */
class Url
{
    /** @var string base url, calculate it only one time */
    private $baseUrl = '';

    /** @var bool */
    private $setHttps;

    /**
     * Url constructor.
     *
     * @param bool $setHttps   Set true if domain is in https and you use frontend for handle https connection and
     *                         backend for http connection). Leave false for autodetect connection type on server where
     *                         PHP run
     */
    public function __construct(bool $setHttps = true)
    {
        $this->setHttps = $setHttps;
    }

    /**
     * Redirect on page in this domain. Otherwise use header() function, you do not need this function.
     *
     * @param string $page
     */
    public function redirect(string $page)
    {
        header('Location: ' . self::makeUrl($page));
        ob_end_flush();
        exit;
    }


    /**
     * @param string $basePath
     *
     * @return string
     */
    public function makeUrl(string $basePath): string
    {
        if ($this->baseUrl == '') {
            if ($this->setHttps) {
                $https = true;
                $port = $defaultPort = 443;
            } else {
                $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && strtolower($_SERVER['HTTPS']) !== 'off';
                $port = $_SERVER['SERVER_PORT'];
                $defaultPort = $https ? 443 : 80;
            }
            $this->baseUrl = ($https ? 'https://' : 'http://')
                . $_SERVER['SERVER_NAME'] . ($port == $defaultPort ? '' : ":$port");
        } else {
            /*Ok, it has already been initialized*/
        }

        return $this->baseUrl . $basePath;
    }


    /**
     * Get dir of the script
     *
     * @return string
     */
    public function getScriptDirName(): string
    {
        return rtrim(dirname($_SERVER['SCRIPT_FILENAME']), '\/');
    }

    /**
     * Get name of the script
     *
     * @return string
     */
    public function getScriptName(): string
    {
        return basename($_SERVER['PHP_SELF']);
    }
}
