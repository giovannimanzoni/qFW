<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\controller\form;

use qFW\mvc\controller\form\UtFormData;
use PHPUnit\Framework\TestCase;

/**
 * Class UtFormDataTest
 *
 * @package qFW\tests\unit\mvc\controller\form
 */
class UtFormDataTest extends TestCase
{
    /**
     * Test are valid CF
     *
     * @throws \Exception
     */
    public function testAreCf()
    {
        $cfArr=array(
            'MNZGNN87D07L388G', // uomo
            'GTTRTI80A41A794Q', // donna
            'FLRMLD87L54Z129N'  // donna
        );

        $this->assertTrue( UtFormData::areCf($cfArr));
    }

    public function testAreCfNotValid()
    {

        // male, wrong control code
        $this->assertFalse( UtFormData::areCf(array('MNZGNN87D07L388H')));

        // female, wrong control code
        $this->assertFalse( UtFormData::areCf(array('GTTRTI80A41A794T')));

        $this->assertFalse( UtFormData::areCf(array('')));
        $this->assertFalse( UtFormData::areCf(array(' ')));
        $this->assertFalse( UtFormData::areCf(array('10/12/2003')));
        $this->assertFalse( UtFormData::areCf(array('192.168.0.10')));
        $this->assertFalse( UtFormData::areCf(array('50')));
        $this->assertFalse( UtFormData::areCf(array('50,30')));
        $this->assertFalse( UtFormData::areCf(array('50.30')));
        $this->assertFalse( UtFormData::areCf(array('/var/www')));

    }


    /**
     * Test for validate non empty strings
     */
    public function testAreNotEmptyValid()
    {
        $arr=array(
            'RFWERWE',
            ' ',
            '4432'
        );

        $this->assertTrue( UtFormData::areNotEmpty($arr));
    }

    /**
     * Test for fail on empty string
     */
    public function testAreNotEmptyNotValid()
    {
        $this->assertFalse( UtFormData::areNotEmpty(array('')));
    }


    /**
     * Test numbers are validated as numbers
     */
    public function testAreNumbersValid()
    {
        $arr=array(
            41,
            44.2
        );

        $this->assertTrue( UtFormData::areNumbers($arr));
    }

    /**
     * Test for fail on validate variable that are not numbers
     */
    public function testAreNumbersNotValid()
    {
        $this->assertFalse( UtFormData::areNumbers(array(true)));
        $this->assertFalse( UtFormData::areNumbers(array(false)));
        $this->assertFalse( UtFormData::areNumbers(array('192.168.0.10')));
        $this->assertFalse( UtFormData::areNumbers(array('')));
        $this->assertFalse( UtFormData::areNumbers(array('10/12/2003')));
        $this->assertFalse( UtFormData::areNumbers(array('MNZGNN87D07L388G')));
    }

    /**
     * Test for valid dates
     */
    public function testAreDate()
    {
        $arr=array(
            '10/12/2003',
            '30/12/2003'
        );

        $this->assertTrue( UtFormData::areDate($arr));
    }

    /**
     * Test for not falid dates
     */
    public function testAreDateNotValid1()
    {

        $this->assertFalse( UtFormData::areDate(array('30/02/2003')));
        $this->assertFalse( UtFormData::areDate(array('30/2/2003')));
        $this->assertFalse( UtFormData::areDate(array('/var/www')));
        $this->assertFalse( UtFormData::areDate(array('30')));
        $this->assertFalse( UtFormData::areDate(array('30.00')));
        $this->assertFalse( UtFormData::areDate(array('30,00')));
        $this->assertFalse( UtFormData::areDate(array('pippo')));
        $this->assertFalse( UtFormData::areDate(array('MNZGNN87D07L388G')));
    }

    /**
     * Ids must be integer number > 0
     */
    public function testAreIds()
    {
        $arr=array(
            1,
            100,
        );

        $this->assertTrue( UtFormData::areIds($arr));
    }

    /**
     * Test for not valid ids
     */
    public function testAreIdsNotValids()
    {
        $this->assertFalse( UtFormData::areIds(array(0)));
        $this->assertFalse( UtFormData::areIds(array(-1)));
    }

    /**
     * Test for string not empty
     */
    public function testAreText()
    {
        $arr=array(
            'pippo',
            '10/10/2000',
            '192.168.0.0'
        );

        $this->assertTrue( UtFormData::areText($arr));
    }

    /**
     * Test for empty string mus be not valid text
     */
    public function testAreTextNotValid()
    {
        $this->assertFalse( UtFormData::areText(array('')));
    }


    /**
     * Test for valid Ip
     */
    public function testAreIps()
    {
        $arr=array(
            '192.168.0.0',
            '10.50.0.53',
            'localhost',
            '127.0.0.1',
            '127.0.0.1|192.168.100.2|192.168.2.30',     //multiple ips
        );

        $this->assertTrue( UtFormData::areIps($arr));
    }

    /**
     * Test for not valid ips
     */
    public function testAreIpsNotValid()
    {

        $this->assertFalse( UtFormData::areIps(array('192.168.0.')));
        $this->assertFalse( UtFormData::areIps(array('10.50.0.530')));
        $this->assertFalse( UtFormData::areIps(array('192.168.0')));
        $this->assertFalse( UtFormData::areIps(array('10/12/2000')));
        $this->assertFalse( UtFormData::areIps(array('50')));
        $this->assertFalse( UtFormData::areIps(array('50.33')));
        $this->assertFalse( UtFormData::areIps(array('pippo')));
        $this->assertFalse( UtFormData::areIps(array('/var/www')));
        $this->assertFalse( UtFormData::areIps(array('10.50.0.53|pippo'))); // valid ip with string
    }


    /**
     * Test are paths
     */
    public function testArePaths()
    {
        $arr=array(
            '/',                                // root
            'images/',                          // one folder with relative path
            '/images/',                         // one folder with absolute path
            '/var/www/',                        // more than one folder
            'www/domain.com/'                   //relative path
        );

        $this->assertTrue( UtFormData::arePaths($arr));
    }

    /**
     * Test are not valid paths
     */
    public function testArePathsNotValid()
    {

        $this->assertFalse( UtFormData::arePaths(array('01/01/2000'))); // date
        $this->assertFalse( UtFormData::arePaths(array('images'))); // missing trailing /
        $this->assertFalse( UtFormData::arePaths(array('localhost'))); // string
        $this->assertFalse( UtFormData::arePaths(array('192.168.0.1'))); // ip
        $this->assertFalse( UtFormData::arePaths(array('4340')));
        $this->assertFalse( UtFormData::arePaths(array('52.00')));
        $this->assertFalse( UtFormData::arePaths(array('http://')));    // douple dot found
        $this->assertFalse( UtFormData::arePaths(array('http://www.google.it/')));
    }


    /**
     *  Test are valid ports (> 0 , < 65000)
     */
    public function testArePorts()
    {
        $arr=array(
            1200,
            34000,
            80
        );

        $this->assertTrue( UtFormData::arePorts($arr));
    }

    /**
     * Test that ports for not valid values
     */
    public function testArePortsNotValid()
    {

        $this->assertFalse( UtFormData::arePorts(array(0)));
        $this->assertFalse( UtFormData::arePorts(array(340000)));
        $this->assertFalse( UtFormData::arePorts(array(80.30)));

    }

    /**
     * Test for valid url
     */
    public function testAreUrls()
    {
        $_SESSION['err']='';

        $arr=array(
            'http://google.it',
            'http://www.google.it',
            'https://google.it',
            'https://www.google.it',
            'https://www.w3resource.com/images/w3resource-logo.png',  // valid image url

        );

        $this->assertTrue( UtFormData::areUrls($arr));
    }

    /**
     * Test for not valid urls
     */
    public function testAreUrlsNotValid()
    {
        $_SESSION['err']='';

        $this->assertFalse( UtFormData::areUrls(array('google.it')));        // missing http
        $this->assertFalse( UtFormData::areUrls(array('http://www.fakefakedomain.it')));
        $this->assertFalse( UtFormData::areUrls(array('http://www.google.it/image/aa.jpg'))); // missing image

    }

}
