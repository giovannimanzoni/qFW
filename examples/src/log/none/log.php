<?php declare(strict_types=1);

/****************************************************************************************************************
 * With this example we see how to suppress the log function simply by creating a log engine of type NoneName
 *
 *
 *
 */

namespace App;

use qFW\log\LogMessage;
use qFW\log\LogProxy;
use qFW\log\NoneName;
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

/*******************************
 * Init log engine
 */

$userId=1;
$logger= new NoneName($userId, $lang);
$loggerEngine=new LogProxy($logger);





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
