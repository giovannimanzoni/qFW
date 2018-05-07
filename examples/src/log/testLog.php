<?php declare(strict_types=1);


/**
 * Log function
 *
 * I create this function for best quality code for this example but user will be put this function inside a class of
 * his/her project
 *
 * @param     $loggerEngine
 * @param int $i
 */
function testLog($loggerEngine, int $i)
{
    // costruct
    $type="info";
    $text="Loop $i";

    $logMessage = new \qFW\log\LogMessage($type, $text, '');

    // log
    $loggerEngine->log($logMessage);
}
