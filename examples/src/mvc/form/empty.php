<?php declare(strict_types=1);

/****************************************************************************************************************
 * With this example we see how to setup an empty form
 *
 *
 *
 */

namespace App;

use qFW\log\HtmlName;
use qFW\mvc\controller\lang\LangEn;
use qFW\mvc\view\form\FormObj;
use qFW\mvc\view\form\FormObjBuilder;
use qFW\mvc\view\form\FormPage;

require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

/*******************************
 * Init log engine
 */

$userId='1';
$lang = new LangEn();
$logger= new HtmlName($userId, $lang);

/*******************************
 * Init form engine
 */

$actionPage='';
$makeBlock=false;


/*******************************
 * Create User form (empty)
 */

$page = new FormPage($logger, 'PageName');


/*******************************
 * Build user form
 */

$objBuilder = new FormObjBuilder($logger);
$objBuilder
    ->addPage($page)
    ->build();


/*******************************
 * Show form
 */

$frmObj = new FormObj($objBuilder, $actionPage, $logger, $makeBlock);

?>
<!DOCTYPE html>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">

    <div class="row">
        <div class="col-xs-12">
            <p>Simple form example.</p>
            <?= $frmObj->show(); ?>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-xs-12">
            <?= $frmObj->getLogs(); ?>
        </div>
    </div>

</div>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
</body>
</html>
