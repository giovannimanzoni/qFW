<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\controller\datatypes;

use qFW\mvc\controller\dataTypes\UtArray;
use PHPUnit\Framework\TestCase;

/**
 * Class UtArrayTest
 *
 * @package qFW\tests\unit\mvc\controller\datatypes
 */
class UtArrayTest extends TestCase
{

    private static $utArr;

    /**
     * Setup class
     */
    public static function setUpBeforeClass()
    {
        self::$utArr = new UtArray();
    }

    /**
     * Test found duplicate numeric value in array
     */
    public function testCheckDuplicateValuesNumeric()
    {
        $arr[] = false;
        $arr[] = 'b';
        $arr[] = 1;
        $arr[] = 'stringa';
        $arr[] = 1;

        $this->assertTrue(self::$utArr->checkDuplicateValues($arr));
    }

    /**
     * Test found duplicate string value in array
     */
    public function testCheckDuplicateValuesNumericString()
    {
        $arr[] = false;
        $arr[] = 'b';
        $arr[] = '1';
        $arr[] = 'stringa';
        $arr[] = 'b';

        $this->assertTrue(self::$utArr->checkDuplicateValues($arr));
    }

    /**
     * Test for return false on same string in upper and lower case
     */
    public function testCheckDuplicateValueSimilarString()
    {

        $arr[] = 'barca';
        $arr[] = 'BARCA';

        $this->assertFalse(self::$utArr->checkDuplicateValues($arr));
    }

    /**
     * test false for 0 and boolean false
     */
    public function testCheckDuplicateValues2()
    {
        $arr[] = false;
        $arr[] = 0;

        $this->assertFalse(self::$utArr->checkDuplicateValues($arr));
    }

    /**
     * Test return duplicate values in array, space separated
     */
    public function testGetArrayDuplicateValues()
    {
        $arr[] = 1;
        $arr[] = 'barca bianca';
        $arr[] = 'barca';
        $arr[] = 2;
        $arr[] = 1;
        $arr[] = 'barca bianca';
        $arr[] = true;
        $arr[] = 'barca';
        $arr[] = 'cipolla';
        $arr[] = 'gatto';


        $this->assertEquals('1 barca bianca barca', self::$utArr->getArrayDuplicateValues($arr));
    }


    /**
     * Test search values in array
     */
    public function testSearchValuesInArray()
    {
        $arr = array(45, 40, 30, 80, 90, 'casa', 'casa', 30);

        $this->assertFalse(self::$utArr->searchValuesInArray($arr, 70));
        $this->assertTrue(self::$utArr->searchValuesInArray($arr, 40));

        // if found al least one -> return true
        $this->assertTrue(self::$utArr->searchValuesInArray($arr, 'casa', 70, 45));

    }
}
