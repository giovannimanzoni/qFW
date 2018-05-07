<?php declare(strict_types=1);

/****************************************************************************************************************
 * With this example we see how to setup a form with multiple pages.
 *
 *
 *
 */

namespace App;

use qFW\log\HtmlName;
use qFW\mvc\controller\lang\LangIt;
use qFW\mvc\controller\url\Url;
use qFW\mvc\view\form\elements\FormInput;
use qFW\mvc\view\form\elements\input\InputSubmit;
use qFW\mvc\view\form\elements\input\InputText;
use qFW\mvc\view\form\FormObj;
use qFW\mvc\view\form\FormObjBuilder;
use qFW\mvc\view\form\FormPage;

require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

/*******************************
 * Init log engine
 */

$userId='1';
$lang = new LangIt();
$logger= new HtmlName($userId, $lang);

/*******************************
 * Init form engine
 */

$actionPage='';
$isMultiPage=true;


/*******************************
 * Create User form (empty)
 */
// @codingStandardsIgnoreStart

$page1 = (new FormPage($logger,'Page 1'))
    ->addElement((new FormInput('idName',new InputText(), true))
                     ->setLabel('Name')
                     ->setPlaceholder('Insert your name')
                     ->setMaxLength(30)
    );
$page2 = (new FormPage($logger,'Page 2'))
    ->addElement((new FormInput('idSurame',new InputText(), true))
                     ->setLabel('Surname')
                     ->setPlaceholder('Insert your surname')
                     ->setMaxLength(30)
    );
$page3 = (new FormPage($logger,'Page 3'))
    ->addElement((new FormInput('idTel',new InputText(), true))
                     ->setLabel('Telephone')
                     ->setPlaceholder('Insert your telephone')
                     ->setMaxLength(30)
    )
    ->addElement((new FormInput('btnSend',new InputSubmit(), false))
                     ->setValue('Submit data')
    )
;
// @codingStandardsIgnoreStop


/*******************************
 * Build user form
 */

$objBuilder = new FormObjBuilder($logger);
$objBuilder
    ->addPage($page1)
    ->addPage($page2)
    ->addPage($page3)
    ->build();


/*******************************
 * Show form
 */
$url= new Url();
$endJs='<script src="'.$url->makeUrl('/mvc/form/multiplePage.js').'"></script>';

$frmObj = new FormObj( $objBuilder, $actionPage, $logger, $isMultiPage);

?>
<!DOCTYPE html>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="multiplePage.css">
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

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?= $endJs; ?>

</body>
</html>
