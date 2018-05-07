<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\api\headers\accept;

use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\vocabulary\Voc;

/**
 * Class AcceptHeader
 *
 * Manage accepted headers
 *
 * @package qFW\mvc\model\api\headers\accept
 */
class AcceptHeader
{
    /** @var array  Hold supported formats*/
    private $supportedFormats = array();

    /** @var array  Hold accepted formats*/
    private $acceptedFormats = array();

    /** @var mixed|string  Hold default format. show always a response in one format*/
    private $defaultFormat = '';

    /** @var string Hold warning mex*/
    private $warningMex = '';

    /** @var \qFW\mvc\controller\vocabulary\Voc  */
    private $voc;

    /**
     * AcceptHeader constructor.
     *
     * @param array                          $supportedFormats
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(array $supportedFormats, ILang $lang)
    {
        $this->supportedFormats = $supportedFormats;
        $this->defaultFormat = $supportedFormats[0];
        $this->voc= new Voc();

        $this->acceptedFormats = $this->parseAcceptHeader();
    }

    /**
     * Parse accepted headers
     *
     * PHP Web Services ISBN: 978-1-449-35656-9
     *
     * @return array
     */
    private function parseAcceptHeader(): array
    {

        $hdr = $_SERVER['HTTP_ACCEPT'];
        $accept = array();
        foreach (preg_split('/\s*,\s*/', $hdr) as $key => $term) {
            $stdCls = new \stdclass;
            $stdCls->pos = $key;
            if (preg_match(",^(\S+)\s*;\s*(?:q|level)=([0-9\.]+),i", $term, $match)) {
                $stdCls->type = $match[1];
                $stdCls->q = (double)$match[2];
            } else {
                $stdCls->type = $term;
                $stdCls->q = 1;
            }
            $accept[] = $stdCls;
        }

        usort($accept, function ($a, $b) {
            /* First tier: highest q factor wins */
            $diff = $b->q - $a->q;
            if ($diff > 0) {
                $diff = 1;
            } else {
                if ($diff < 0) {
                    $diff = -1;
                } else {
                    /* Tie-breaker: first listed item wins */
                    $diff = $a->pos - $b->pos;
                }
            }
            return $diff;
        });

        $accept_data = array();
        foreach ($accept as $a) {
            $accept_data[$a->type] = $a->type;
        }
        return $accept_data;
    }

    /**
     * A summary informing the user what the associated element does.
     *
     * A *description*, that can span multiple lines, to go _in-depth_ into the details of this element
     * and to provide some background information or textual references.
     *
     * @return string
     */
    public function getSendFormat(): string
    {
        $warning = true;
        $sendFormat = $this->defaultFormat;
        foreach ($this->acceptedFormats as $format) {
            if (in_array($format, $this->supportedFormats)) {
                $sendFormat = $format;
                $warning = false;
                break;
            } else {
                /*Ok, continue*/
            }
        }

        if ($warning) {
            $this->setWaring();
        } else {
            /*OK*/
        }

        return $sendFormat;
    }

    /**
     * Set warning
     */
    private function setWaring()
    {
        $warningMex = $this->voc->headerAcceptNotSupported();
        foreach ($this->acceptedFormats as $accFormat) {
            $warningMex .= "$accFormat | ";
        }

        $warningMex .= $this->voc->headerAcceptAre();
        foreach ($this->supportedFormats as $supFormat) {
            $warningMex .= "$supFormat | ";
        }

        $this->warningMex = $warningMex;
    }

    /**
     * Get warning
     *
     * @return string
     */
    public function getWarning(): string
    {
        return $this->warningMex;
    }
}
