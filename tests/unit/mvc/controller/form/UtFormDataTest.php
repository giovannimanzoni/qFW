<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\controller\form;

use qFW\mvc\controller\dataTypes\UtString;
use qFW\mvc\controller\form\UtFormData;
use PHPUnit\Framework\TestCase;
use qFW\mvc\controller\lang\LangEn;

/**
 * Class UtFormDataTest
 *
 * @package qFW\tests\unit\mvc\controller\form
 */
class UtFormDataTest extends TestCase
{
    private static $lang;
    private static $utFormData;

    /**
     * Setup class
     */
    public static function setUpBeforeClass()
    {
        fwrite(STDOUT, __METHOD__ . "\n");

        self::$lang = new LangEn();
        self::$utFormData = new UtFormData(self::$lang);
    }

    /**
     * Test are valid CF
     *
     * @throws \Exception
     */
    public function testAreCf()
    {
        $cfArr = array(
            'MNZGNN87D07L388G', // uomo
            'GTTRTI80A41A794Q', // donna
            'FLRMLD87L54Z129N'  // donna
        );

        $this->assertTrue(self::$utFormData->areCf($cfArr));
    }

    public function testAreCfNotValid()
    {

        // male, wrong control code
        $this->assertFalse(self::$utFormData->areCf(array('MNZGNN87D07L388H')));

        // female, wrong control code
        $this->assertFalse(self::$utFormData->areCf(array('GTTRTI80A41A794T')));

        $this->assertFalse(self::$utFormData->areCf(array('')));
        $this->assertFalse(self::$utFormData->areCf(array(' ')));
        $this->assertFalse(self::$utFormData->areCf(array('10/12/2003')));
        $this->assertFalse(self::$utFormData->areCf(array('192.168.0.10')));
        $this->assertFalse(self::$utFormData->areCf(array('50')));
        $this->assertFalse(self::$utFormData->areCf(array('50,30')));
        $this->assertFalse(self::$utFormData->areCf(array('50.30')));
        $this->assertFalse(self::$utFormData->areCf(array('/var/www')));

    }


    /**
     * Test for validate non empty strings
     */
    public function testAreNotEmptyValid()
    {
        $arr = array(
            'RFWERWE',
            ' ',
            '4432'
        );

        $this->assertTrue(self::$utFormData->areNotEmpty($arr));
    }

    /**
     * Test for fail on empty string
     */
    public function testAreNotEmptyNotValid()
    {
        $this->assertFalse(self::$utFormData->areNotEmpty(array('')));
    }


    /**
     * Test numbers are validated as numbers
     */
    public function testAreNumbersValid()
    {
        $arr = array(
            41,
            44.2
        );

        $this->assertTrue(self::$utFormData->areNumbers($arr));
    }

    /**
     * Test for fail on validate variable that are not numbers
     */
    public function testAreNumbersNotValid()
    {
        $this->assertFalse(self::$utFormData->areNumbers(array(true)));
        $this->assertFalse(self::$utFormData->areNumbers(array(false)));
        $this->assertFalse(self::$utFormData->areNumbers(array('192.168.0.10')));
        $this->assertFalse(self::$utFormData->areNumbers(array('')));
        $this->assertFalse(self::$utFormData->areNumbers(array('10/12/2003')));
        $this->assertFalse(self::$utFormData->areNumbers(array('MNZGNN87D07L388G')));
    }

    /**
     * Test for valid dates
     */
    public function testAreDate()
    {
        $arr = array(
            '10/12/2003',
            '30/12/2003'
        );

        $this->assertTrue(self::$utFormData->areDate($arr));
    }

    /**
     * Test for not falid dates
     */
    public function testAreDateNotValid1()
    {

        $this->assertFalse(self::$utFormData->areDate(array('30/02/2003')));
        $this->assertFalse(self::$utFormData->areDate(array('30/2/2003')));
        $this->assertFalse(self::$utFormData->areDate(array('/var/www')));
        $this->assertFalse(self::$utFormData->areDate(array('30')));
        $this->assertFalse(self::$utFormData->areDate(array('30.00')));
        $this->assertFalse(self::$utFormData->areDate(array('30,00')));
        $this->assertFalse(self::$utFormData->areDate(array('pippo')));
        $this->assertFalse(self::$utFormData->areDate(array('MNZGNN87D07L388G')));
    }

    /**
     * Ids must be integer number > 0
     */
    public function testAreIds()
    {
        $arr = array(
            1,
            100,
        );

        $this->assertTrue(self::$utFormData->areIds($arr));
    }

    /**
     * Test for not valid ids
     */
    public function testAreIdsNotValids()
    {
        $this->assertFalse(self::$utFormData->areIds(array(0)));
        $this->assertFalse(self::$utFormData->areIds(array(-1)));
    }

    /**
     * Test for string not empty
     */
    public function testAreText()
    {
        $arr = array(
            'pippo',
            '10/10/2000',
            '192.168.0.0'
        );

        $this->assertTrue(self::$utFormData->areText($arr));
    }

    /**
     * Test for empty string mus be not valid text
     */
    public function testAreTextNotValid()
    {
        $this->assertFalse(self::$utFormData->areText(array('')));
    }


    /**
     * Test for valid Ip
     */
    public function testAreIps()
    {
        $arr = array(
            '192.168.0.0',
            '10.50.0.53',
            'localhost',
            '127.0.0.1',
            '127.0.0.1|192.168.100.2|192.168.2.30',     //multiple ips
        );

        $this->assertTrue(self::$utFormData->areIps($arr));
    }

    /**
     * Test for not valid ips
     */
    public function testAreIpsNotValid()
    {

        $this->assertFalse(self::$utFormData->areIps(array('192.168.0.')));
        $this->assertFalse(self::$utFormData->areIps(array('10.50.0.530')));
        $this->assertFalse(self::$utFormData->areIps(array('192.168.0')));
        $this->assertFalse(self::$utFormData->areIps(array('10/12/2000')));
        $this->assertFalse(self::$utFormData->areIps(array('50')));
        $this->assertFalse(self::$utFormData->areIps(array('50.33')));
        $this->assertFalse(self::$utFormData->areIps(array('pippo')));
        $this->assertFalse(self::$utFormData->areIps(array('/var/www')));
        $this->assertFalse(self::$utFormData->areIps(array('10.50.0.53|pippo'))); // valid ip with string
    }


    /**
     * Test are paths
     */
    public function testArePaths()
    {
        $arr = array(
            '/',                                // root
            'images/',                          // one folder with relative path
            '/images/',                         // one folder with absolute path
            '/var/www/',                        // more than one folder
            'www/domain.com/'                   //relative path
        );

        $this->assertTrue(self::$utFormData->arePaths($arr));
    }

    /**
     * Test are not valid paths
     */
    public function testArePathsNotValid()
    {

        $this->assertFalse(self::$utFormData->arePaths(array('01/01/2000'))); // date
        $this->assertFalse(self::$utFormData->arePaths(array('images'))); // missing trailing /
        $this->assertFalse(self::$utFormData->arePaths(array('localhost'))); // string
        $this->assertFalse(self::$utFormData->arePaths(array('192.168.0.1'))); // ip
        $this->assertFalse(self::$utFormData->arePaths(array('4340')));
        $this->assertFalse(self::$utFormData->arePaths(array('52.00')));
        $this->assertFalse(self::$utFormData->arePaths(array('http://')));    // douple dot found
        $this->assertFalse(self::$utFormData->arePaths(array('http://www.google.it/')));
    }


    /**
     *  Test are valid ports (> 0 , < 65000)
     */
    public function testArePorts()
    {
        $arr = array(
            1200,
            34000,
            80
        );

        $this->assertTrue(self::$utFormData->arePorts($arr));
    }

    /**
     * Test that ports for not valid values
     */
    public function testArePortsNotValid()
    {

        $this->assertFalse(self::$utFormData->arePorts(array(0)));
        $this->assertFalse(self::$utFormData->arePorts(array(340000)));
        $this->assertFalse(self::$utFormData->arePorts(array(80.30)));

    }

    /**
     * Test for valid url
     */
    public function testAreUrls()
    {
        $_SESSION['err'] = '';

        $arr = array(
            'http://google.it',
            'http://www.google.it',
            'https://google.it',
            'https://www.google.it',
            'https://www.w3resource.com/images/w3resource-logo.png',  // valid image url
            'http://giovannimanzoni.com',
            'https://giovannimanzoni.com',
        );

        $this->assertTrue(self::$utFormData->areUrls($arr));
    }

    /**
     *
     */
    public function testIsUrl()
    {
        $this->assertTrue(self::$utFormData->isUrl('http://giovannimanzoni.com'));
    }


    /**
     * Test for not valid urls
     */
    public function testAreUrlsNotValid()
    {
        $_SESSION['err'] = '';

        $this->assertFalse(self::$utFormData->areUrls(array('google.it')));        // missing http
        $this->assertFalse(self::$utFormData->areUrls(array('http://www.fakefakedomain.it')));
        $this->assertFalse(self::$utFormData->areUrls(array('http://www.google.it/image/aa.jpg'))); // missing image

    }
}
