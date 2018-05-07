<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\api\json;

/**
 * Class JsonView
 *
 * Generate a json response to an api request
 *
 * @package qFW\mvc\view\api\json
 */
class JsonView
{
    /** @var int  Hold http status code*/
    private $status=0;

    /** @var array Hold warnings*/
    private $warnings = array();

    /** @var array Hold errors */
    private $errors = array();

    /**
     * @param array $data
     *
     * @return bool
     * @throws \Exception
     */
    public function render(array $data = array()): bool
    {
        $sendJson["data"] = $data;
        $sendJson["warnings"] = $this->warnings;
        $sendJson["errors"] = $this->errors;

        if ($this->status) {
            http_response_code($this->status);
        } else {
            /*Ok*/
        }

        header('Content-Type: application/json');
        echo $this->safeJsonEncode($sendJson);
        return true;
    }

    /**
     * Set status code for
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get http status code
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Add warning to warning list
     *
     * @param string $warning
     *
     * @return $this
     */
    public function addWarning(string $warning)
    {
        $this->warnings[] = $warning;
        return $this;
    }

    /**
     * Add error to error list
     *
     * @param string $error
     *
     * @return $this
     */
    public function addError(string $error)
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * Safe json encode
     *
     * https://stackoverflow.com/questions/10199017/how-to-solve-json-error-utf8-error-in-php-json-decode
     *
     * @param $value
     *
     * @return string
     * @throws \Exception
     */
    private function safeJsonEncode($value)
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $encoded = json_encode($value, JSON_PRETTY_PRINT);
        } else {
            $encoded = json_encode($value);
        }
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                throw new \Exception('Json Maximum stack depth exceeded');
            case JSON_ERROR_STATE_MISMATCH:
                throw new \Exception('Json Underflow or the modes mismatch');
            case JSON_ERROR_CTRL_CHAR:
                throw new \Exception('Json Unexpected control character found');
            case JSON_ERROR_SYNTAX:
                throw new \Exception('Json Syntax error, malformed JSON');
            case JSON_ERROR_UTF8:
                $clean = $this->utf8ize($value);
                return $this->safeJsonEncode($clean);
            default:
                throw new \Exception('Json Unknown error');
        }
    }

    /**
     * Convert string to utf8 format
     *
     * @param array|string $mixed
     *
     * @return array|string
     */
    private function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } else {
            if (is_string($mixed)) {
                return utf8_encode($mixed);
            } else {
                /*Ok*/
            }
        }
        return $mixed;
    }
}
