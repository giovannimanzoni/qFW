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
 * Class Sanitize
 *
 * Sanitize POST and GET data
 *
 * @package qFW\mvc\controller\url
 */
class Sanitize
{
    /** @var bool true if cleanPostData() finds an error */
    private $cleanErr = false;

    /**
     * Sanitize constructor.
     */
    public function __construct()
    {
    }

    /**
     * Clean post vars
     *
     * @param string $data
     *
     * @return string
     */
    public function cleanPostData(string $data): string
    {
        $ret = '';

        $lenInput = strlen($data);
        $strClean = self::cleanHtml(self::cleanXSS($data));
        $lenOut = strlen($strClean);
        if ($lenInput == $lenOut) {
            $ret = trim($strClean);
        } else {
            // Post-tampered values
            $this->cleanErr = true;
        }
        //todo: else errore !!
        return $ret;
    }

    /**
     * return cleanPostData error state
     *
     * @return bool
     */
    public function getCleanErr(): bool
    {
        return $this->cleanErr;
    }


    /**
     * Check if the resource on the network exists
     * -> https://stackoverflow.com/questions/2280394/how-can-i-check-if-a-url-exists-via-php
     *
     * @param string $url
     * @param bool   $testCurl
     *
     * @return bool
     * @throws \Exception
     */
    public function isValidUrl(string $url, bool $testCurl = true): bool
    {
        $ret = true;
        // First do some quick sanity checks:
        if (!$url || !is_string($url)) {
            $ret = false;
        } // Quick check url is roughly a valid http request: ( http://blah/... )
        elseif (!preg_match('/^http(s)?:\/\/[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url)) {
            $ret = false;
        } // The next bit could be slow:
        elseif ($testCurl) {
            if ($this->getHttpResponseCodeUsingCurl($url) != 200) {
                $ret = false;
            }
        } else {
            /*Ok*/
        }
        // All good!
        return $ret;
    }

    /**
     * Remove all html and javascript code
     *
     * @param string $dirtyHtml
     *
     * @return string
     */
    private function cleanHtml(string $dirtyHtml): string
    {
        //@todo configurarlo con tidy ?
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', ''); // Allow Nothing
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($dirtyHtml);
    }

    /**
     * XSS Protection
     *
     * -> https://stackoverflow.com/questions/1336776/xss-filtering-function-in-php
     *
     * @param string $data
     *
     * @return string
     */
    private function cleanXSS(string $data): string
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        // @codingStandardsIgnoreStart
        $data = preg_replace(
            '#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
            '$1=$2nojavascript...',
            $data
        );
        $data = preg_replace(
            '#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
            '$1=$2novbscript...',
            $data
        );
        $data = preg_replace(
            '#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u',
            '$1=$2nomozbinding...',
            $data
        );

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace(
            '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i',
            '$1>',
            $data
        );
        $data = preg_replace(
            '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i',
            '$1>',
            $data
        );
        $data = preg_replace(
            '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu',
            '$1>',
            $data
        );
        // @codingStandardsIgnoreEnd

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            // @codingStandardsIgnoreStart
            $data = preg_replace(
                '#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i',
                '',
                $data
            );
            // @codingStandardsIgnoreEnd
        } while ($old_data !== $data);

        // We are done...
        return $data;
    }

    /**
     * @param      $url
     * @param bool $followredirects
     *
     * @return bool|mixed
     * @throws \Exception
     */
    private function getHttpResponseCodeUsingCurl($url, $followredirects = true)
    {
        // Returns int responsecode, or false (if url does not exist or connection timeout occurs)
        // NOTE: could potentially take up to 0-30 seconds , blocking further code execution
        // (more or less depending on connection, target site, and local timeout settings))
        // if $followredirects == false: return the FIRST known httpcode (ignore redirects)
        // if $followredirects == true : return the LAST  known httpcode (when redirected)
        if (!$url || !is_string($url)) {
            return false;
        } else {
            /*Ok*/
        }
        $ch = @curl_init($url);
        if ($ch === false) {
            throw new \Exception('curl init failed');
        } else {
            /*OK*/
        }
        @curl_setopt($ch, CURLOPT_HEADER, true);    // We want headers
        @curl_setopt($ch, CURLOPT_NOBODY, true);    // Dont need body
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // Catch output (do NOT print!)
        if ($followredirects) {
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            @curl_setopt( // Fairly random number, but could prevent unwanted endless redirects with followlocation=true
                $ch,
                CURLOPT_MAXREDIRS,
                10
            );
        } else {
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        }
        @curl_setopt( // Fairly random number (seconds)... but could prevent waiting forever to get a result
            $ch,
            CURLOPT_CONNECTTIMEOUT,
            10
        );
        @curl_setopt( // Fairly random number (seconds)... but could prevent waiting forever to get a result
            $ch,
            CURLOPT_TIMEOUT,
            10
        );
        @curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1'
        );   // Pretend we're a regular browser
        @curl_exec($ch);
        if (@curl_errno($ch)) {   // should be 0
            @curl_close($ch);
            return false;
        } else {
            /*Ok*/
        }
        $code = @curl_getinfo( // Note: php.net documentation shows this returns a string, but really it returns an int
            $ch,
            CURLINFO_HTTP_CODE
        );
        @curl_close($ch);
        return $code;
    }
}
