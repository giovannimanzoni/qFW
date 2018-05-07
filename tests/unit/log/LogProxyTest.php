<?php declare(strict_types=1);

namespace qFW\tests\unit\log;

use qFW\log\ConsoleName;
use qFW\log\HtmlName;
use qFW\log\LogMessage;
use qFW\log\LogProxy;
use PHPUnit\Framework\TestCase;
use qFW\log\NoneName;
use qFW\mvc\controller\lang\LangEn;


/**
 * Class LogProxyTest
 *
 * @package qFW\tests\unit\log
 */
class LogProxyTest extends TestCase
{

    /**
     * Test Html __construct
     */
    public function testHtmlConstruct()
    {
        $lang = new LangEn();
        $logger = new HtmlName(1, $lang);
        $_SESSION['ALOGGER']=array();
        $loggerEngine = new LogProxy($logger);

        // test ILogMessage
        $type = 'warning';
        $text = 'messaggio di warning';


        /****************************
         * test ALogger.php - no logs
         ****************************/

        // getLogsQty()
        $this->assertEquals(0, $loggerEngine->getLogsQty());

        // getLogs()
        $this->assertEquals('No errors to show.', $loggerEngine->getLogs());


        /****************************
         * test ALogger.php - 1 log
         ****************************/

        // Construct
        $logMessage = new LogMessage($type, $text);
        $date = $logMessage->getDate();

        //log
        $loggerEngine->log($logMessage);

        // getLogsQty()
        $this->assertEquals(1, $loggerEngine->getLogsQty());

        // getLogs()
        $this->assertEquals("<ul><li>$date : $type | $text</li></ul>", $loggerEngine->getLogs());


        /****************************
         * test ALogger.php - 2 (multiple) log
         ****************************/

/*      PHPUnit lose $_SESSION[]
        // Construct
        $logMessage2 = new LogMessage($type, $text);
        $date2 = $logMessage2->getDate();

        //log
        $loggerEngine->log($logMessage2);

        // getLogsQty()
        $this->assertEquals(2, $loggerEngine->getLogsQty());

        // getLogs()
        $this->assertEquals("<ul><li>$date : $type | $text</li><li>$date2 : $type | $text</li></ul>", $loggerEngine->getLogs());
*/
    }

    /**
     * Test None __construct
     */
    public function testNoneConstruct()
    {
        $lang = new LangEn();
        $logger = new NoneName(1, $lang);
        $_SESSION['ALOGGER']=array();
        $loggerEngine = new LogProxy($logger);

        // test ILogMessage
        $type = 'warning';
        $text = 'messaggio di warning';


        /****************************
         * test ALogger.php - nessun log
         ****************************/

        // getLogsQty()
        $qty= $loggerEngine->getLogsQty();
        $this->assertEquals(0, $qty);

        // getLogs()
        $this->assertEquals('', $loggerEngine->getLogs());


        /****************************
         * test ALogger.php - 1 log
         ****************************/

        // Construct
        $logMessage = new LogMessage($type, $text);

        //log
        $loggerEngine->log($logMessage);

        // getLogsQty()
        $this->assertEquals(1, $loggerEngine->getLogsQty());

        // getLogs()
        $this->assertEquals('', $loggerEngine->getLogs());


        /****************************
         * test ALogger.php - 2 (multiple) log
         ****************************/
/*      PHPUnit lose $_SESSION[]
        // Construct
        $logMessage2 = new LogMessage($type, $text);

        //log
        $loggerEngine->log($logMessage2);

        // getLogsQty()
        $this->assertEquals(2, $loggerEngine->getLogsQty());

        // getLogs()
        $this->assertEquals('', $loggerEngine->getLogs());
*/
    }

    /**
     * Test Console __construct
     */
    public function testConsoleConstruct()
    {
        $lang = new LangEn();
        $logger = new ConsoleName(1, $lang);
        $_SESSION['ALOGGER']=array();
        $loggerEngine = new LogProxy($logger);

        // test ILogMessage
        $type = 'warning';
        $text = 'messaggio di warning';

        /****************************
         * test ALogger.php - nessun log
         ****************************/

        // getLogsQty()
        $this->assertEquals(0, $loggerEngine->getLogsQty());

        // getLogs()
        $this->assertEquals('<script type="text/javascript">console.log("No errors to show.");</script>', $loggerEngine->getLogs());


        /****************************
         * test ALogger.php - 1 log
         ****************************/

        // Construct
        $logMessage = new LogMessage($type, $text);
        $date = $logMessage->getDate();

        //log
        $loggerEngine->log($logMessage);

        // getLogsQty()
        $this->assertEquals(1, $loggerEngine->getLogsQty());

        // getLogs()
        $this->assertEquals("<script type=\"text/javascript\">console.log('$date : $type | $text');</script>",
                            $loggerEngine->getLogs());

        /****************************
         * test ALogger.php - 2 (multiple) log
         ****************************/

/*      PHPUnit lose $_SESSION[]
        // Construct
        $logMessage2 = new LogMessage($type, $text);
        $date2 = $logMessage2->getDate();

        //log
        $loggerEngine->log($logMessage2);

        // getLogsQty()
        $this->assertEquals(2, $loggerEngine->getLogsQty());

        // getLogs()
        $this->assertEquals("<script type=\"text/javascript\">console.log('$date : $type | $text');console.log('$date2 : $type | $text');</script>", $loggerEngine->getLogs());
        */
    }

}
