<?php declare(strict_types=1);

namespace App\mvc\pages\admin;

use qFW\mvc\controller\frontController\FrontController;
use qFW\mvc\controller\lang\LangEn;

require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

$lang = new LangEn();

$fc = (new FrontController($lang))
    ->setDomainFolder('/mvc/pages/admin/demoLogin2')
    ->run();
