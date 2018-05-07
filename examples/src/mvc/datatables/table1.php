<?php declare(strict_types=1);

/****************************************************************************************************************
 *  With this example we see how to setup a page with a datatable
 *
 *
 *
 *
 */

namespace App\mvc\datatables;

require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

$tblRows = array();

/****************************
 * Generate table data
 */
for ($i=0; $i<100; $i++) {
    $tblRows[$i]['row']=$i;
    $tblRows[$i]['name']='Angela';
    $tblRows[$i]['user']='Mike';
    $tblRows[$i]['qty']=$i * 1.2;
    $tblRows[$i]['desc']='Lorem ipsum . . .';
    $tblRows[$i]['sku']="ADAXW202-8$i-77";
    $tblRows[$i]['user']='Mike';
}

/****************************
 * Setup table
 */

$tbl=new TableDemo();
$endJs=$tbl->getTableJquery();
$tbl->setDatatableBody($tblRows);

// @codingStandardsIgnoreStart
// Data tables
$cssDatatables = "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/autofill/2.2.0/css/autoFill.dataTables.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.bootstrap.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.bootstrap.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/keytable/2.2.1/css/keyTable.bootstrap.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/rowgroup/1.0.0/css/rowGroup.bootstrap.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.bootstrap.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/scroller/1.4.2/css/scroller.bootstrap.min.css\" /> "
    . "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/select/1.2.2/css/select.bootstrap.min.css\" /> ";


$jsDatatables =  "<script src=\"https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/autofill/2.2.0/js/dataTables.autoFill.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/autofill/2.2.0/js/autoFill.bootstrap.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/keytable/2.2.1/js/dataTables.keyTable.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/rowgroup/1.0.0/js/dataTables.rowGroup.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/rowreorder/1.2.0/js/dataTables.rowReorder.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/scroller/1.4.2/js/dataTables.scroller.min.js\"></script>"
    . "<script src=\"https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js\"></script>"
// @codingStandardsIgnoreStop
?>


<!DOCTYPE html>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <?= $cssDatatables; ?>
</head>
<body>


<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?= $tbl->show(); ?>
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

<?= "$endJs $jsDatatables;" ?>
</body>
</html>

