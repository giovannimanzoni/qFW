<?php declare(strict_types=1);

namespace qFW\tests\unit\log;

use qFW\log\LogMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class LogMessageTest
 *
 * @package tests\unit\log
 */
class LogMessageTest extends TestCase
{
    /**
     *
     */
    public function test__construct()
    {
        $LogMessage= new LogMessage('type','string');

        $this->assertEquals('string',$LogMessage->getText());
        $this->assertEquals('type',$LogMessage->getType());
        $this->assertEquals(gmdate('Y-m-d H:i:s'),$LogMessage->getDate());
    }
}
