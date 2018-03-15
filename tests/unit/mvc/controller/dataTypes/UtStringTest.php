<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\controller\dataTypes;

use qFW\mvc\controller\dataTypes\UtString;
use PHPUnit\Framework\TestCase;
use qFW\mvc\controller\form\FieldLenght;

/**
 * Class UtStringTest
 *
 * @package qFW\tests\unit\mvc\controller\dataTypes
 */
class UtStringTest extends TestCase
{
    /**
     * Setup class
     */
    public static function setUpBeforeClass()
    {
        set_exception_handler(array(UtStringTest::class,'exception_handler'));
        fwrite(STDOUT, __METHOD__ . "\n");
    }

    /**
     * Class exception handler
     *
     * @param \Exception $exception
     */
    private static function exception_handler(\Exception $exception) {
        echo "My uncaught exception: " , $exception->getMessage(), "\n";
    }


    /**
     * Test function of <=> operator
     */
    public function testAreEqual()
    {

        /*********************************************
         *  TRUE
         *********************************************/

        $arrA=array(
            'stringa string string',
            1,
            1,
            true,
            false,
            0.00,
            2,
            '2'
        );

        $arrB=array(
            'stringa string string',
            1,
            '1',
            '1',
            '0',
            '0',
            true,
            true
        );

        foreach($arrA as $key => $arr) {
            $this->assertTrue(UtString::areEqual($arrA[$key], $arrB[$key]));
        }


        /*********************************************
         *  FALSE
         *********************************************/

        $a=false;
        $b='1';
        $this->assertFalse(UtString::areEqual($a,$b));


        $a='jiòofew,jihorj,i';
        $b='ir431oipyopup';
        $this->assertFalse(UtString::areEqual($a,$b));


    }

    /**
     * Test search in string
     */
    public function testStrSearch()
    {
        $a='uno due tre';
        $b='due';

        $this->assertTrue(UtString::strSearch($a,$b));

    }

    /**
     *  Test convert variable into equivalent string
     */
    public function testGetCleanStringValidArg()
    {

        $arrTest=array(
            array('',''),       // empty string
            array(1,'1'),       // integer to string
            array('1','1'),     // string to string
            array(0.0,'0'),
            array(1,'1'),
            array(1,'1'),
            array(1,'1'),

        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[1],UtString::getCleanString($arr[0]));
        }

    }

    /**
     * Test can not convert boolean to string
     *
     * @expectedException        \Exception
     * @expectedExceptionMessage Can not clean boolean as string.
     */
    public function testGetCleanStringInvalidArg()
    {
        $arrTest=array(false,true);

        foreach($arrTest as $val) {
            UtString::getCleanString($val);
        }
    }

    /**
     *  Test format variable to price
     */
    public function testFormatPrice()
    {
        $arrTest=array(
            array(100,'100,00'),        // int to price
            array(100.00000,'100,00'),  // float to price
            array('100','100,00'),      // string to price
            array('100.00','100,00'),   // string with dot to price
            array('100,00','100,00'),   // string with comma to price
        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[1], UtString::formatPrice($arr[0]));
        }
    }

    /**
     * Test substr_count
     */
    public function testStrCount()
    {
        $arrTest=array(
            array('qaz','a',1),
            array('ciao come stai','ciao',1),
            array('ciao come stai comodo','co',2),
            array('ciao come stai comodo',' ',3),
        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[2], UtString::strCount($arr[0],$arr[1]));
        }
    }

    /**
     * Test truncate string and add '...'
     */
    public function testLimitString()
    {
        $arrTest=array(
            array('ciao come stai',5,'ci...'),
            array('ciao come stai',7,'ciao...'),
            array('ciao come stai',14,'ciao come s...'),

        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[2], UtString::limitString($arr[0],$arr[1]));
        }
    }

    /**
     * Test float to string conversion
     */
    public function testFloatToString()
    {
        $arrTest=array(
            array(5,'5'),           // int
            array(5.0,'5.0'),       // float
            array('5','5'),         // string with int
            array('5.0','5.0'),     // string with float with dot separator
            array('5,0','5.0')      // string with  float with comma separator
        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[1], UtString::floatToString($arr[0]));
        }
    }

    /**
     * Test conversion from number to 'Sì' or 'No'
     */
    public function testGetSiNoString()
    {
        $arrTest=array(
            array(0,'--'),
            array(1,'Sì'),
            array(2,'No'),
        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[1], UtString::getSiNoString($arr[0]));
        }
    }

    /**
     * Test sum of numbers and get sum formatted as price
     */
    public function testSommaPrezzi()
    {

        $this->assertEquals('100,00', UtString::sommaPrezzi(50,50));
        $this->assertEquals('100,40', UtString::sommaPrezzi(50.10,50.30));
        $this->assertEquals('50,10', UtString::sommaPrezzi(50.10));

    }

    /**
     * Test for valid password
     */
    public function testValidatePwdMod1()
    {
        $this->assertEquals('', UtString::validatePwdMod1('qazwsxed1Q')); // valid password

        $arrTest=array(
            array('fdsfds','La password deve contenere almeno 8 caratteri.'),
            array(
                'fdsfdsgdhgfdhgfdhfgdhsfghsgfhsgfhgsfhgyhwretqtqwetqwerqwetregfdgfd',
                'La password deve contenere meno di '.FieldLenght::DIM_PWD.' caratteri.'),
            array('sasaDSDSDSDS','La password deve contenere almeno un numero.'),
            array('sasa534543534','La password deve contenere almeno una lettera maiuscola.'),
            array('FFDSFDS534543534','La password deve contenere almeno una lettera minuscola.'),

        );

        foreach($arrTest as $arr) {
            $this->assertEquals($arr[1], UtString::validatePwdMod1($arr[0]));
        }
    }


}
