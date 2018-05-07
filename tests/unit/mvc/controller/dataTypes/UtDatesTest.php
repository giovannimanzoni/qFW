<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\controller\dataTypes;

use PHPUnit\Framework\TestCase;
use qFW\mvc\controller\dataTypes\UtDate;
use qFW\mvc\controller\lang\LangEn;
use qFW\mvc\controller\lang\LangIt;
use qFW\mvc\controller\vocabulary\VocEN;

/**
 * Class UtDatesTest
 *
 * @package qFW\tests\unit\mvc\controller\dataTypes
 */
class UtDatesTest extends TestCase
{

    private static $utDate;

    /**
     * Setup class (Fixtures)
     */
    public static function setUpBeforeClass()
    {
        date_default_timezone_set('Europe/Rome');
        fwrite(STDOUT, __METHOD__ . "\n");

        self::$utDate = new UtDate(new LangEn());
    }


    /**
     * Test conversion from mysql datetime to european date
     */
    public function testDateTimeToDatel()
    {
        $dateTime = array(
            '2019-01-01 12:40:00', // data semplice
            '2019-12-30 10:00:59',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            '0000-00-00 00:00:00'   // data vuota
        );

        $dateVer = array(
            '01/01/2019', // data semplice
            '30/12/2019',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            ''
        );

        foreach ($dateTime as $key => $data) {
            $data = self::$utDate->dateTimeToDate($data);
            $this->assertEquals($dateVer[$key], $data);
        }
    }

    /**
     * Test conversion from mysql datetime to european date and time
     */
    public function testDateTimeToDateTime()
    {
        $mySqlDateTimeRecord = array(
            '2019-01-01 12:40:00', // data semplice
            '2019-12-30 10:00:59',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            '0000-00-00 00:00:00'   // data vuota
        );

        $dateVer = array(
            '01/01/2019 12:40:00', // data semplice
            '30/12/2019 10:00:59',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            ''
        );

        foreach ($mySqlDateTimeRecord as $key => $data) {
            $data = self::$utDate->dateTimeToDateTime($data);
            $this->assertEquals($dateVer[$key], $data);
        }
    }

    /**
     * Test conversion from mysql datetime to european date and time with verbose text for today and yesterday
     */
    public function testDateTimeToDateTimeVerbose()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('yesterday'));

        $mySqlDateTimeRecord = array(
            "$today 12:40:00", // data semplice
            "$yesterday 12:40:00",  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            '0000-00-00 00:00:00'   // data vuota

        );

        $dateVer = array(
            'today at 12:40:00', // data semplice
            'yesterday at 12:40:00',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            ''
        );

        foreach ($mySqlDateTimeRecord as $key => $data) {
            $data = self::$utDate->dateTimeToDateTime($data, true);
            $this->assertEquals($dateVer[$key], $data);
        }
    }

    /**
     * Check if range of date are valid
     */
    public function testCheckDateRange()
    {
        $this->assertTrue(self::$utDate->CheckDateRange('01/02/2007', '01/09/2018'));
        $this->assertFalse(self::$utDate->CheckDateRange('01/09/2018', '01/02/2007'));

    }

    /**
     * Test get date in mysql format for current date or given date
     */
    public function testGetDateMax()
    {
        $exp1 = date_create(date('Y-m-d H:i:s'));
        $this->assertEquals($exp1, self::$utDate->getDateMax(null));

        $dateTime = self::$utDate->getDateMax('01/01/2000');
        $this->assertEquals('2000-01-01 00:00:00', $dateTime->format('Y-m-d H:i:s'));

        // test date in format gg/mm/aaaa
        $dateTime = self::$utDate->getDateMax('30/01/2000');
        $this->assertEquals('2000-01-30 00:00:00', $dateTime->format('Y-m-d H:i:s'));
    }

    /**
     * Calculate difference between DateTime in seconds
     */
    public function testDateIntervalToSeconds()
    {
        $reference = new \DateTimeImmutable();

        $this->assertEquals(self::$utDate->DateIntervalToSeconds(
            $reference,
            $reference), 0);


        $endTime = $reference->add(new \DateInterval("PT10M")); // add 10 Minutes

        $this->assertEquals(
            self::$utDate->DateIntervalToSeconds(
                $endTime,
                $reference
            ), 600);
    }

    /**
     * Test get seconds between two dates
     */
    public function testSecondsBetweenDates()
    {
        $arrDateInit = array(
            '01/01/2000 00:00:00',  // simple date
            '30/01/2000 00:00:00',  // date for test european format
            '30/01/2000'            // without seconds in first date
        );

        $arrDateEnd = array(
            '01/01/2000 00:00:10',
            '30/01/2000 00:00:10',
            '30/01/2000 00:00:10'
        );

        $date = new UtDate(new LangEn());
        foreach ($arrDateInit as $key => $data) {
            $this->assertEquals('10', $date->secondsBetweenDates($arrDateInit[$key], $arrDateEnd[$key]));
        }
    }

    /**
     * Test time between 2 dates reported in days and time
     */
    public function testTimeLaps()
    {
        $arrDateInit = array(
            '01/01/2000 00:00:00',  // simple date
            '30/01/2000 00:00:00',  // date for test european format
            '30/01/2000',           // without seconds in first date
            '30/01/2000',           // with trigger, date overflow trigger for less than a day
            '30/01/2000',           // with trigger, date overflow trigger for more than a day
            '30/01/2000'            // with trigger, date overflow trigger between 1 and 2 days

        );

        $arrDateEnd = array(
            '01/01/2000 00:00:10',
            '30/01/2000 00:00:10',
            '30/01/2000 00:00:10',
            '30/01/2000 05:30:25',
            '02/02/2000',
            '31/01/2000'
        );

        $arrResults = array(
            '+ 00:00:10',
            '+ 00:00:10',
            '+ 00:00:10',
            '<span class="timeLapsTrigger">+ 05:30:25</span>',
            '<span class="timeLapsTrigger">+ 3 days and 00:00:00</span>',
            '<span class="timeLapsTrigger">+ 1 day and 00:00:00</span>'
        );

        $date = new UtDate(new LangEn());
        foreach ($arrDateInit as $key => $data) {
            $this->assertEquals($arrResults[$key], $date->timeLaps($arrDateInit[$key], $arrDateEnd[$key], 90));
        }
    }

    /**
     * Convert from data in european format to a timestamp for mysql
     */
    public function testDateToTimestamp()
    {
        $arrDateInit = array(
            '01/01/2000',           // simple date
            '30/01/2000 00:00:00',  // date for test european format
            '30/01/2000 12:05:42'   // ignore seconds in date

        );


        $arrResults = array(
            '2000-01-01 00:00:00',
            '2000-01-30 00:00:00',
            '2000-01-30 00:00:00'
        );

        foreach ($arrDateInit as $key => $data) {
            $this->assertEquals($arrResults[$key], self::$utDate->dateToTimestamp($arrDateInit[$key]));
        }
    }


    /**
     *
     * Convert from data in european format to a timestamp for php
     */
    public function testDatetimeToTimestamp()
    {

        $arrDateInit = array(
            '01/01/2000 00:00:00',  // simple date
            '30/01/2000 12:05:42'   // test for date in european format

        );

        $arrResults = array(
            '946681200',
            '949230342'
        );

        foreach ($arrDateInit as $key => $data) {
            $this->assertEquals($arrResults[$key], self::$utDate->datetimeToTimestamp($arrDateInit[$key]));
        }
    }
}
