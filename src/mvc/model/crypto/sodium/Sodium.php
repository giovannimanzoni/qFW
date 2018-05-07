<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\crypto\sodium;

/**
 * Class Sodium
 *
 * -> https://paragonie.com/blog/2017/06/libsodium-quick-reference-quick-comparison-similar-functions-and-which-one-use
 *
 * @package qFW\mvc\model\crypto\sodium
 */
class Sodium
{
    protected $opslimit = SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE;
    protected $memlimit = SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE;

    /**
     * Sodium constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $opslimit
     * @param $memlimit
     *
     * @return $this
     */
    public function setPwHash($opslimit, $memlimit)
    {
        $this->opslimit = $opslimit;
        $this->memlimit = $memlimit;
        return $this;
    }

    /**
     * @param string $pwd
     *
     * @return string
     */
    public function getHash(string $pwd): string
    {
        return sodium_crypto_pwhash_str($pwd, $this->opslimit, $this->memlimit);
    }

    /**
     * -> https://paragonie.com/book/pecl-libsodium/read/07-password-hashing.md
     * Remember to run sodium_memzero($pwd); after call this function
     *
     * @param string $hash
     * @param string $pwd
     *
     * @return mixed
     */
    public function pwdVerify(string $hash, string $pwd): bool
    {
        return sodium_crypto_pwhash_str_verify($hash, $pwd);
    }
}
