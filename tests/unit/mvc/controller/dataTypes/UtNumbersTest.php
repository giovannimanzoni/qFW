<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\controller\dataTypes;

use PHPUnit\Framework\TestCase;
use qFW\mvc\controller\dataTypes\UtNumbers;

/**
 * Class UtNumbers
 *
 * @package qFW\tests\unit\mvc\controller\dataTypes
 */
class UtNumbersTest extends TestCase
{
    private static $utNum;

    /**
     * Setup class (Fixtures)
     */
    public static function setUpBeforeClass()
    {
        date_default_timezone_set('Europe/Rome');
        fwrite(STDOUT, __METHOD__ . "\n");

        self::$utNum = new UtNumbers();
    }

    /**
     * Test conversion to float
     */
    public function testGetCleanFloat()
    {
        $arrTest = array(
            array(10.00, 10),
            array(10, 10),
            array('10', 10),
            array('10.00', 10),
            array('10,50', 10.50)
        );

        foreach ($arrTest as $arr) {
            $this->assertEquals($arr[1], self::$utNum->getCleanFloat($arr[0]));
        }
    }

    /**
     * Test conversion of true to float
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Impossible convert boolean true to float
     */
    public function testGetCleanFloatTrueNotValid()
    {
        self::$utNum->getCleanFloat(true);
    }

    /**
     * Test conversion of false to float
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Impossible convert boolean false to float
     */
    public function testGetCleanFloatFalseNotValid()
    {
        self::$utNum->getCleanFloat(false);
    }

    /**
     * Test conversion to float
     */
    public function testGetCleanInt()
    {
        $arrTest = array(
            array(10, 10),       // int to int
            array(10.00, 10),    // float to int
            array(10.50, 10),    // truncate float
            array('10', 10),     // string to int
            array('10.00', 10),  // string truncate float
            array('10,50', 10),  // string truncate float
            array(true, 1),      // boolean
            array(false, 0)      // boolean
        );

        foreach ($arrTest as $arr) {
            $this->assertEquals($arr[1], self::$utNum->getCleanInt($arr[0]));
        }
    }

    /**
     * Test conversion to boolean
     */
    public function testGetCleanBool()
    {
        $arrTest = array(
            array(true, true),
            array(false, false),
            array(1, true),
            array('1', true),
            array('1.00', true),
            array(0, false),
            array('0', false),
            array('0.0', false)
        );

        foreach ($arrTest as $arr) {
            $this->assertEquals($arr[1], self::$utNum->getCleanBool($arr[0]));
        }
    }

    /**
     * Test for not valid conversion
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Not boolean argument.
     */
    public function testGetCleanBoolNotValid()
    {
        self::$utNum->getCleanBool(2);
    }
}
