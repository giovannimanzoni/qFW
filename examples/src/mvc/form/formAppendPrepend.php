<?php declare(strict_types=1);

/****************************************************************************************************************
 *  With this example we see how to setup a form with input text with appended and prepended icons and text
 *
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
$logger = new HtmlName($userId, $lang);

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
        ->setAppend('You')
    )
    ->addElement((new FormInput('idSurname',new InputText(), true))
        ->setLabel('Surname')
        ->setPlaceholder('Insert your surname')
        ->setMaxLength(30)
        ->setElementRatio(10)
        ->setPrepend('You')
    )
    ->addElement((new FormInput('idTel',new InputText(), true))
        ->setLabel('Pre compiled field')
        ->setPlaceholder('fontawesome icon used')
        ->setMaxLength(20)
        ->setAppend('fa fa-bolt')
    )
    ->addElement((new FormInput('idOptional',new InputText(), false))
        ->setLabel('Optional field and label on top')
        ->setPlaceholder('fontawesome icon used')
        ->setMaxLength(100)
        ->setPrepend('fa fa-user')

    )
    ->addElement((new FormInput('idSerialNumber2',new InputText(), true))
        ->setLabel('Read Only field')
        ->setValue('glyphicon icon used')
        ->setReadonly()
        ->setMaxLength(30)
        ->setPrepend('glyphicon glyphicon-envelope')
    )
    ->addElement((new FormInput('idSerialNumber3',new InputText(), true))
        ->setLabel('custom field')
        ->setValue('glyphicon icon used')
        ->setMaxLength(30)
        ->setPrepend('glyphicon glyphicon-envelope')
    )
    ->addElement((new FormInput('idSerialNumber',new InputText(), true))
        ->setLabel('Read Only field')
        ->setValue('546as-6asdfg6-5a54-da64')
        ->setReadonly()
        ->setMaxLength(30)
        ->setElementRowClass('specificCssClass') // @fixme bug
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

$frmObj = new FormObj( $objBuilder, $actionPage, $logger,  $isMultiPage);




?>
<!DOCTYPE html>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>

<style>
    .glyphicon {
        font-size: 19px;
    }
</style>


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

<script defer src='https://use.fontawesome.com/releases/v5.0.9/js/all.js'
        integrity='sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl'
        crossorigin='anonymous'></script>"
</body>
</html>
