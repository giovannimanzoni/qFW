<?php declare(strict_types=1);

/****************************************************************************************************************
 *  With this example we see how to setup a login form
 *
 *
 *
 *
 */

namespace App\mvc\pages\admin;

use qFW\mvc\controller\lang\LangEn;
use qFW\mvc\view\pages\admin\LoginPage;
use qFW\mvc\view\template\content\LoginTpl;

require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

/***********************
 * Setup lang
 */

$lang= new LangEn();

/***********************
 * Setup form
 */

$lostPwd='<h3>Did you forget the password?</h3><p>No problem, call +39 02/12.34.56 for request new password.</p>';

$page=(new LoginPage('Login page', '../skinLoginAdmin.css'))
    ->setStaticOrigin('https://staticdm.it/demoqFW')
    ->setContent(new LoginTpl($lostPwd, $lang, ''));


/***********************
 * View
 */

$page->render();
