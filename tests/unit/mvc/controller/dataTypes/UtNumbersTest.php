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

    /**
     * Test conversion to float
     */
    public function testGetCleanFloat()
    {
        $arrTest=array(
            array(10.00,10),
            array(10,10),
            array('10',10),
            array('10.00',10),
            array('10,50',10.50)
        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[1], UtNumbers::getCleanFloat($arr[0]));
        }
    }

    /**
     * Test conversion of true to float
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Impossibile trasformare boolean true in float
     */
    public function testGetCleanFloatTrueNotValid()
    {
        UtNumbers::getCleanFloat(true);
    }

    /**
     * Test conversion of false to float
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Impossibile trasformare boolean false in float
     */
    public function testGetCleanFloatFalseNotValid()
    {
        UtNumbers::getCleanFloat(false);
    }


    /**
     * Test conversion to float
     */
    public function testGetCleanInt()
    {
        $arrTest=array(
            array(10,10),       // int to int
            array(10.00,10),    // float to int
            array(10.50,10),    // truncate float
            array('10',10),     // string to int
            array('10.00',10),  // string truncate float
            array('10,50',10),  // string truncate float
            array(true,1),      // boolean
            array(false,0)      // boolean
        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[1], UtNumbers::getCleanInt($arr[0]));
        }
    }

    /**
     * Test conversion to boolean
     */
    public function testGetCleanBool()
    {
        $arrTest=array(
            array(true,true),
            array(false, false),
            array(1,true),
            array('1',true),
            array('1.00',true),
            array(0,false),
            array('0',false),
            array('0.0',false)
        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[1], UtNumbers::getCleanBool($arr[0]));
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
      UtNumbers::getCleanBool(2);
    }


}
