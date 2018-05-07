<?php declare(strict_types=1);

namespace App\mvc\pages\admin;

use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\controller\url\Sanitize;
use qFW\mvc\controller\url\Url;

require "settings.php";
require "{$_SERVER['DOCUMENT_ROOT']}/vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php";

$san= new Sanitize();

$tmpUser=$san->cleanPostData($_POST['username'] ?? '');
$tmpPwd=$san->cleanPostData($_POST['password'] ?? '');

$url= new Url();
$utStr= new UtString($lang);

if (!$utStr->areEqual($tmpUser, 'demo') || !$utStr->areEqual($tmpPwd, 'demo')) {
    $_SESSION['mex'] = '<div class="alert alert-danger"><strong>Attenzione !
        </strong> Non hai inserito i campi necessari.</div>';
    $_SESSION['mex'] .= "<br>username: {$_POST['username']} - password: {$_POST['password']}";

    $url->redirect('/mvc/pages/admin/demoLogin2/login.php');
} else {
    $_SESSION['isValidAdmin'] = true;

    $url->redirect('/mvc/pages/admin/demoLogin2/index.php');
}
