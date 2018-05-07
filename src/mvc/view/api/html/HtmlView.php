<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\view\api\html;

/**
 * Class HtmlView
 *
 * Generate an html response to an api request
 *
 * @package qFW\mvc\view\api\html
 */
class HtmlView
{
    /** @var int http status code */
    private $status=0;

    /** @var array  array for possible warning */
    private $warnings = array();

    /** @var array array for possible errors */
    private $errors = array();

    /**
     * Render html page
     *
     * @param array $data
     *
     * @return bool
     */
    public function render(array $data = array()): bool
    {
        if ($this->status) {
            http_response_code($this->status);
        } else {
            /*Ok*/
        }
        header('Content-Type: text/html');

        $output = "<!DOCTYPE html><html><head><title>Status: {$this->status}</title></head><body>";
        if ($data) {
            $output .= '<p>';
            foreach ($data as $arr) {
                $output .= implode(',', $arr);
            }
            $output .= '</p>';
        }
        $output .= $this->craftArray('Warnings:', $this->warnings);
        $output .= $this->craftArray('Errors:', $this->errors);

        echo "$output</body></html>";
        return true;
    }

    /**
     * Show an array in an html list with title
     *
     * @param string $title Title
     * @param array  $data  Array to show in an html list
     *
     * @return string
     */
    private function craftArray(string $title, array $data): string
    {
        $ret = '';
        if ($this->warnings) {
            $ret .= "$title:<br><ul>";
            foreach ($data as $text) {
                $ret .= "<li>$text</li>";
            }
            $ret .= '</ul>';
        }
        return $ret;
    }

    /**
     * Set status code
     *
     * @param int $status   Status code
     *
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status code
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
}
