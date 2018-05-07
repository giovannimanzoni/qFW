<?php declare(strict_types=1);

/****************************************************************************************************************
 * With this example we see how to log use message in browser consolle and show them where user like.
 *
 * If it not work in Firefox, try Google Chrome
 *
 */

namespace App;

use qFW\log\ConsoleName;
use qFW\log\LogMessage;
use qFW\log\LogProxy;
use qFW\mvc\controller\lang\LangEn;

require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

/*******************************
 * User functions
 */

require '../testLog.php';


/*******************************
 * Init language for log and framework message
 */

$lang = new LangEn();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Log with web console</title>
</head>
<body>
<?php

/*******************************
 * Init log engine
 */

$userId=1;
$logger = new ConsoleName($userId, $lang);
$loggerEngine = new LogProxy($logger);





/*******************************
 * User App
 */

// Let me write some lines of code..

for ($i=0; $i<10; $i++) {
    echo "$i<br>";

    // Log all lines
    if (true) {
        testLog($loggerEngine, $i);
    }
}




/*******************************
 * Show if log
 */


$logQty=$loggerEngine->getLogsQty();

if ($logQty) {
    echo $loggerEngine->getLogs();
} else {
    echo '<br>----------------<br>No log to show';
}


?>

</body>
</html>
