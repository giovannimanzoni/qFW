<?php declare(strict_types=1);

namespace qFW\tests\unit\mvc\controller\dataTypes;

use PHPUnit\Framework\TestCase;
use qFW\mvc\controller\dataTypes\UtDate;

/**
 * Class UtDatesTest
 *
 * @package qFW\tests\unit\mvc\controller\dataTypes
 */
class UtDatesTest extends TestCase
{
    /**
     * Setup class (Fixtures)
     */
    public static function setUpBeforeClass()
    {
        date_default_timezone_set('Europe/Rome');
        fwrite(STDOUT, __METHOD__ . "\n");
    }


    /**
     * Test conversion from mysql datetime to european date
     */
    public function testDateTimeToDatel()
    {
        $dateTime=array(
            '2019-01-01 12:40:00', // data semplice
            '2019-12-30 10:00:59',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            '0000-00-00 00:00:00'   // data vuota
        );

        $date=array(
            '01/01/2019', // data semplice
            '30/12/2019',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            ''
        );

        foreach($dateTime as $key => $data) {
            $data = UtDate::dateTimeToDate($data);
            $this->assertEquals($date[$key], $data);
        }
    }

    /**
     * Test conversion from mysql datetime to european date and time
     */
    public function testDateTimeToDateTime()
    {
        $mySqlDateTimeRecord=array(
            '2019-01-01 12:40:00', // data semplice
            '2019-12-30 10:00:59',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            '0000-00-00 00:00:00'   // data vuota
        );

        $date=array(
            '01/01/2019 12:40:00', // data semplice
            '30/12/2019 10:00:59',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            ''
        );


        foreach($mySqlDateTimeRecord as $key => $data) {
            $data = UtDate::dateTimeToDateTime($data);
            $this->assertEquals($date[$key], $data);
        }
    }

    /**
     * Test conversion from mysql datetime to european date and time with verbose text for today and yesterday
     */
    public function testDateTimeToDateTimeVerbose()
    {
        $today=date('Y-m-d');
        $yesterday=date('Y-m-d',strtotime('yesterday'));

        $mySqlDateTimeRecord=array(
            "$today 12:40:00", // data semplice
            "$yesterday 12:40:00",  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            '0000-00-00 00:00:00'   // data vuota

        );

        $date=array(
            'Oggi alle 12:40:00', // data semplice
            'Ieri alle 12:40:00',  // data x testare formattazione gg/mm/aaaa e non mm/gg/aaaa
            ''
        );

        foreach($mySqlDateTimeRecord as $key => $data) {
            $data = UtDate::dateTimeToDateTime($data,true);
            $this->assertEquals($date[$key], $data);
        }
    }

    /**
     * Check if range of date are valid
     */
    public function testCheckDateRange()
    {

        $this->assertTrue(UtDate::CheckDateRange('01/02/2007','01/09/2018'));
        $this->assertFalse(UtDate::CheckDateRange('01/09/2018','01/02/2007'));

    }

    /**
     * Test get date in mysql format for current date or given date
     */
    public function  testGetDateMax()
    {
        $exp1=date_create(date('Y-m-d H:i:s'));
        $this->assertEquals($exp1,UtDate::getDateMax(null));

        $dateTime=UtDate::getDateMax('01/01/2000');
        $this->assertEquals('2000-01-01 00:00:00',$dateTime->format('Y-m-d H:i:s'));

        // test date in format gg/mm/aaaa
        $dateTime=UtDate::getDateMax('30/01/2000');
        $this->assertEquals('2000-01-30 00:00:00',$dateTime->format('Y-m-d H:i:s'));

    }

    /**
     * Calculate difference between DateTime in seconds
     */
    public function testDateIntervalToSeconds()
    {
        $reference = new \DateTimeImmutable();

        $this->assertEquals(UtDate::DateIntervalToSeconds(
            $reference,
            $reference),0);


        $endTime = $reference->add(new \DateInterval("PT10M")); // add 10 Minutes

        $this->assertEquals(
            UtDate::DateIntervalToSeconds(
                $endTime,
                $reference
            ),600);

    }

    /**
     * Test get seconds between two dates
     */
    public function testSecondsBetweenDates()
    {
        $arrDateInit=array(
            '01/01/2000 00:00:00',  // simple date
            '30/01/2000 00:00:00',  // date for test european format
            '30/01/2000'            // without seconds in first date
        );

        $arrDateEnd=array(
            '01/01/2000 00:00:10',
            '30/01/2000 00:00:10',
            '30/01/2000 00:00:10'
        );


        foreach($arrDateInit as $key=> $data) {
            $this->assertEquals('10', UtDate::secondsBetweenDates($arrDateInit[$key], $arrDateEnd[$key]));
        }

    }

    /**
     * Test time between 2 dates reported in days and time
     */
    public function testTimeLaps()
    {
        $arrDateInit=array(
            '01/01/2000 00:00:00',  // simple date
            '30/01/2000 00:00:00',  // date for test european format
            '30/01/2000',           // without seconds in first date
            '30/01/2000',           // with trigger, date overflow trigger for less than a day
            '30/01/2000',           // with trigger, date overflow trigger for more than a day
            '30/01/2000'            // with trigger, date overflow trigger between 1 and 2 days

        );

        $arrDateEnd=array(
            '01/01/2000 00:00:10',
            '30/01/2000 00:00:10',
            '30/01/2000 00:00:10',
            '30/01/2000 05:30:25',
            '02/02/2000',
            '31/01/2000'
        );

        $arrResults=array(
            '+ 00:00:10',
            '+ 00:00:10',
            '+ 00:00:10',
            '<span class="timeLapsTrigger">+ 05:30:25</span>',
            '<span class="timeLapsTrigger">+ 3 giorni e 00:00:00</span>',
            '<span class="timeLapsTrigger">+ 1 giorno e 00:00:00</span>'
        );

        foreach($arrDateInit as $key=> $data) {
            $this->assertEquals($arrResults[$key], UtDate::timeLaps($arrDateInit[$key], $arrDateEnd[$key],90));
        }
    }

    /**
     * Convert from data in european format to a timestamp for mysql
     */
    public function testDateToTimestamp()
    {
        $arrDateInit=array(
            '01/01/2000',           // simple date
            '30/01/2000 00:00:00',  // date for test european format
            '30/01/2000 12:05:42'   // ignore seconds in date

        );


        $arrResults=array(
            '2000-01-01 00:00:00',
            '2000-01-30 00:00:00',
            '2000-01-30 00:00:00'
        );

        foreach($arrDateInit as $key=> $data) {
            $this->assertEquals($arrResults[$key], UtDate::dateToTimestamp($arrDateInit[$key]));
        }
    }


    /**
     *
     * Convert from data in european format to a timestamp for php
     */
    public function testDatetimeToTimestamp()
    {

        $arrDateInit=array(
            '01/01/2000 00:00:00',  // simple date
            '30/01/2000 12:05:42'   // test for date in european format

        );

        $arrResults=array(
            '946681200',
            '949230342'
        );

        foreach($arrDateInit as $key=> $data) {
            $this->assertEquals($arrResults[$key], UtDate::datetimeToTimestamp($arrDateInit[$key]));
        }
    }


}
