<?php declare(strict_types=1);

/****************************************************************************************************************
 *  With this example we see how to setup qFW\mvc\view\widget\buttons\Buttons1
 *
 *
 *
 *
 */

namespace App\mvc\datatables;

use qFW\mvc\view\widget\buttons\Buttons1;

require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

/********************
 * Setup buttons obj
 */

$btns = (new Buttons1())
    ->addButton('/page1.php', 'fa-spinner', 'NOME 1')
    ->addButton('/page2.php', 'fa-spinner', 'NAME 2')
    ->addButton('/page3.php', 'fa-paint-brush ', 'EXAMPLE 3')
    ->addButton('#', 'fa-file-alt', 'NAME BUTTON 4');


?>

<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- jQuery -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>

<style>

    .margintop{
        margin-top: 50px;
    }
    .quick-button {
        background-color: #fafafa;
        background-image: linear-gradient(to bottom, #fafafa, #efefef);
        border: 1px solid #ddd;
        border-radius: 2px;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
        color: #646464;
        cursor: pointer;
        display: block;
        font-size: 14px;
        margin-bottom: 20px;
        margin-left: 0.2em;
        margin-right: 0.2em;
        padding: 30px 0 10px;
        position: relative;
        text-align: center;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.6);
        transition: all 0.3s ease 0s;
    }
</style>

<div class="container">
    <div class="margintop row">
        <div class="col-xs-12">
            <?= $btns->getHtml() ?>
        </div>
    </div>
</div>


<!-- Bootstrap -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<!-- Font Awesome -->
<script defer src='https://use.fontawesome.com/releases/v5.0.9/js/all.js'
        integrity='sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl'
        crossorigin='anonymous'></script>"

</body>
</html>

