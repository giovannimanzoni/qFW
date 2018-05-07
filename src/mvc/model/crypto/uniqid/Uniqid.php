<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\crypto\uniqid;

/**
 * Class Uniqid
 *
 * -> http://php.net/manual/en/function.uniqid.php
 * -> https://stackoverflow.com/questions/38716613/generate-a-single-use-token-in-php-random-bytes-or-openssl-random-pseudo-bytes
 *
 * @package App\external\qFW\src\mvc\model\crypto\uniqid
 */
class Uniqid
{
    /**
     * Uniqid constructor.
     */
    public function __construct()
    {
    }


    /**
     * @param int $lenght
     *
     * @return string
     */
    public function get($lenght = 13): string
    {
        try {
            $bytes = random_bytes((int)ceil($lenght / 2));
            $ret = substr(bin2hex($bytes), 0, $lenght);
        } catch (\Exception $e) {
            if ($lenght > 13) {
                $moreEntropy = true;
            } else {
                $moreEntropy = false;
            }
            $ret = uniqid('', $moreEntropy);

            // cut only if generate string is > of cutting lenght
            if (($lenght != 13) && (($lenght < 22) && $moreEntropy)) {
                $ret = substr($ret, 0, $lenght);
                if ($moreEntropy) {
                    $ret = str_replace('.', '', $ret);
                }
            } else { // Could not cut; add more characters
                do {
                    usleep(100);
                    $ret .= uniqid('', true);
                    $ret = str_replace('.', '', $ret);
                } while ($ret < $lenght);

                //Cut
                $ret = substr($ret, 0, $lenght);
                $_SESSION['adminLog'] .= "Was not possible to gather sufficient entropy for random_bytes() : $e";
            }
        }

        return $ret;
    }
}
