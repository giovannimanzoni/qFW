<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\controller\email;

use qFW\mvc\model\crypto\uniqid\Uniqid;

/**
 * Class Email
 *
 * @package qFW\mvc\controller\email
 */
class Email
{
    private const CHARACTER_SET = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';

    private const ID_KEY_QFW = 'ID_KEY_QFW';

    private $emailClean = '';

    private $emailObfuscate = '';

    /**
     * email constructor.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->emailClean = $email;
    }

    /**
     * Get obfuscate email
     *
     * @return string
     */
    public function getObf()
    {
        $uniq= new Uniqid();

        if ($this->emailObfuscate == '') {
            $this->emailObfuscate = $this->obfuscate();
        }

        return str_replace(self::ID_KEY_QFW, $uniq->get(), $this->emailObfuscate);
    }


    /**
     * Return javascript for hide email
     *
     * @return string
     */
    private function obfuscate(): string
    {
        $key = str_shuffle(self::CHARACTER_SET);
        $cipher_text = '';

        for ($i = 0; $i < strlen($this->emailClean); $i += 1) {
            $cipher_text .= $key[strpos(self::CHARACTER_SET, $this->emailClean[$i])];
        }

        $script = 'var a="' . $key . '";var b=a.split("").sort().join("");var c="' . $cipher_text . '";var d="";';
        $script .= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
        $script .= 'document.getElementById("' . self::ID_KEY_QFW.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
        $script = "eval(\"" . str_replace(array("\\", '"'), array("\\\\", '\"'), $script) . "\")";
        $script = '<script type="text/javascript">/*<![CDATA[*/' . $script . '/*]]>*/</script>';

        return '<span id="' . self::ID_KEY_QFW . '">[javascript protected email address]</span>' . $script;
    }
}
