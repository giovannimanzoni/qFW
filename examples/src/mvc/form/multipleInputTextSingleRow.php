<?php declare(strict_types=1);

/****************************************************************************************************************
 *  With this example we see how to setup a form with multiple input text with label and submit button.
 *      There are multiple input on every line
 *
 *
 *
 */

namespace App;

use qFW\log\HtmlName;
use qFW\mvc\controller\lang\LangIt;
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

$actionPage = '';
$isMultiPage = false;


/*******************************
 * Create User form (empty)
 */
// @codingStandardsIgnoreStart
$page = (new FormPage($logger, 'PageName'))
    ->addElement((new FormInput('idName',new InputText(), true))
        ->setLabel('Name')
        ->setPlaceholder('Insert your name')
        ->setMaxLength(30)
        ->setElementRatio(7)
        ->setElementRowClass('col-xs-12 col-sm-4')
    )
    ->addElement((new FormInput('idSurname',new InputText(), true))
        ->setLabel('Surname')
        ->setPlaceholder('Insert your surname')
        ->setMaxLength(30)
        ->setElementRatio(10)
        ->setElementRatio(7)
        ->setElementRowClass('col-xs-12 col-sm-4')
    )
    ->addElement((new FormInput('idTel',new InputText(), true))
        ->setLabel('Pre compiled field')
        ->setPlaceholder('Insert your telephone number')
        ->setValue('029852650')
        ->setMaxLength(20)
        ->setElementRatio(7)
        ->setElementRowClass('col-xs-12 col-sm-4')
    )
    ->addElement((new FormInput('idOptional',new InputText(), false))
        ->setLabel('Optional field and label on top')
        ->setPlaceholder('Optional text input')
        ->setMaxLength(100)
        ->setElementRatio(7)
        ->setElementRowClass('col-xs-12 col-sm-6')
    )
    ->addElement((new FormInput('idSerialNumber',new InputText(), true))
        ->setLabel('Read Only field')
        ->setValue('029-45533-45646-4432DFD-FS')
        ->setReadonly()
        ->setMaxLength(30)
        ->setElementRatio(7)
        ->setElementRowClass('col-xs-12 col-sm-6')
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
    ->addPage($page)
    ->build();


/*******************************
 * Show form
 */

$frmObj = new FormObj( $objBuilder, $actionPage, $logger, $isMultiPage);

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
            <hr>
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
