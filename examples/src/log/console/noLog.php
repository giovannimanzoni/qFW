<?php declare(strict_types=1);

/****************************************************************************************************************
 * With this example we see how to setup browser consolle logging engine but without log anything.
 *
 *
 *
 */

namespace App;

use qFW\log\ConsoleName;
use qFW\log\LogProxy;
use qFW\mvc\controller\lang\LangEn;

require "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";


/*******************************
 * Init language for log and framework message
 */

$lang = new LangEn();


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
}




/*******************************
 * Show if log
 */


echo $loggerEngine->getLogs();
