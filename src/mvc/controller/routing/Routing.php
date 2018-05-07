<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\controller\routing;

use qFW\mvc\controller\url\Url;

/**
 * Class Routing
 *
 * Routing Controller
 *
 * @package qFW\mvc\controller\routing
 */
class Routing
{
    /** @var string Hold url for possible extension of this class */
    protected $scriptName = '';

    /** @var \qFW\mvc\controller\url\Url */
    protected $url;

    /** @var string website folder */
    protected $folder = '';


    /**
     * Routing constructor.
     *
     * @param bool   $isAdmin
     * @param string $folder    default ''
     */
    public function __construct(bool $isAdmin = true, string $folder = '')
    {
        $this->url = new Url();
        $this->scriptName = $this->url->getScriptName();
        $this->folder = $folder;

        if (!$this->isPageIncluded()) { // Specific for page not included (default, for debug)
            $this->initSession($this->scriptName);

            if ($isAdmin) {
                $this->redirectToLoginIfLogout($this->scriptName);

                if ($this->isAdmin()) {
                    $this->redirectToIndexIfLoginPage($this->scriptName);
                } else { // Not admin
                    $this->redirectToLoginIfNotLoginPage($this->scriptName);
                }
            } else {
                /*Ok*/
            }
        } elseif ($isAdmin) { // Specific for page included in Administrator qPanel, for test purpose
            $this->redirectToLoginIfLogout($this->scriptName);
        } else {
            /*Ok*/
        }
    }

    /**
     * @return string
     */
    public function getScriptName(): string
    {
        return $this->scriptName;
    }

    /**
     * @param string $reqPage
     */
    private function redirectToLoginIfLogout(string $reqPage)
    {
        switch ($reqPage) {
            case 'logout.php':
                $_SESSION = array();
                session_unset();
                session_destroy();
                $this->url->redirect("{$this->folder}/login.php");
                break;
            default:
                break;
        }
    }

    /**
     * Redirect to login if this page is not the login page
     *
     * @param string $reqPage
     */
    private function redirectToLoginIfNotLoginPage(string $reqPage)
    {
        switch ($reqPage) {
            case 'login.php':
            case 'trylogin.php':
                break;
            default:
                $this->url->redirect("{$this->folder}/login.php");
                break;
        }
    }

    /**
     * Redirect to index if this page is login page. use when user is logged in
     *
     * @param string $reqPage
     */
    private function redirectToIndexIfLoginPage(string $reqPage)
    {
        switch ($reqPage) {
            case 'login.php':
            case 'trylogin.php':
                $this->url->redirect("{$this->folder}/index.php");
                break;
            default:
                // No redirect, the page will be loaded
                break;
        }
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    private function isAdmin(): bool
    {
        $ret = false;

        // isValidAdmin: True if user is logged into ADMIN AREA
        if (isset($_SESSION['isValidAdmin']) && !empty($_SESSION['isValidAdmin'])) {
            $ret = true;
        } else {
            /*Ok*/
        }
        return $ret;
    }

    /**
     * Check if this page is loaded inside content of another page (for testing without deploy a qPanel)
     *
     * @return bool
     */
    private function isPageIncluded(): bool
    {
        $ret = false;
        if (isset($_SESSION['err']) || isset($_SESSION['mex'])) {
            $ret = true;
        } else {/* Is false*/
        }

        return $ret;
    }

    /**
     * Init Session
     *
     * @param string $reqPage
     */
    private function initSession(string $reqPage)
    {
        ob_start();
        // ob_end_flush() isn't needed in MOST cases because it is called automatically at the end of script execution
        // by PHP itself when output buffering is turned on either in the php.ini or by calling ob_start().

        session_start();
        $now = time();
        if (isset($_SESSION['timeout']) && $now > $_SESSION['timeout']) {
            // avoid recursive redirect if page is logout page
            switch ($reqPage) {
                case 'logout.php':
                    break;
                default:
                    $this->url->redirect("{$this->folder}/logout.php");
                    break;
            }
        } else { /* ok */
        }

        // Either new or old, it should live at most for another hour
        $_SESSION['timeout'] = $now + 3600;

        // If do not exists, create them, otherwise preserv values
        $_SESSION['err'] = $_SESSION['err'] ?? ''; // Error to show to user
        $_SESSION['mex'] = $_SESSION['mex'] ?? ''; // Message for user
        $_SESSION['adminLog'] = $_SESSION['adminLog'] ?? ''; // Message to be logged in database for administrator
    }
}
