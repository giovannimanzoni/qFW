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

    /**
     * Routing constructor.
     */
    public function __construct()
    {
        $reqPage = Url::getScriptName();
        if (!$this->isPageIncluded()) { // specific for page not included (default)
            $this->initSession($reqPage);

            $this->redirectToLoginIfLogout($reqPage);

            if ($this->isAdmin()) {
                $this->redirectToIndexIfLoginPage($reqPage);
            } else { // not admin
                $this->redirectToLoginIfNotLoginPage($reqPage);
            }
        } else { // specific for page included in Administrator qPanel, for test purpose
            $this->redirectToLoginIfLogout($reqPage);
        }

        // common
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
                Url::redirect('/login.php');
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
                Url::redirect('/login.php');
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
                Url::redirect('/index.php');
                break;
            default:
                // no redirect, the page will be loaded
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

        // isValidAdmin: true if user is logged into qPanel
        if (isset($_SESSION['isValidAdmin']) && !empty($_SESSION['isValidAdmin'])) {
            $ret = true;
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
        } else {/* is false*/
        }

        return $ret;
    }


    /**
     * Init Session
     */
    private function initSession(string $reqPage)
    {
        ob_start();
        session_start();
        $now = time();
        if (isset($_SESSION['timeout']) && $now > $_SESSION['timeout']) {
            //evito redirect ricorsivo se pagina corrispone a logout
            switch ($reqPage) {
                case 'logout.php':
                    break;
                default:
                    Url::redirect('/logout.php');
                    break;
            }
        } else { /* ok */
        }

        // either new or old, it should live at most for another hour
        $_SESSION['timeout'] = $now + 3600;

        // se non esistono crea le variabili, altrimenti preserva i messaggi passati da una pagina all'altra
        $_SESSION['err'] = $_SESSION['err'] ?? '';
        $_SESSION['mex'] = $_SESSION['mex'] ?? '';
    }
}
