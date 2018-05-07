<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\controller\dataTypes;

use qFW\mvc\controller\lang\ILang;
use qFW\mvc\controller\vocabulary\Voc;

/**
 * Class UtDate
 *
 * @package qFW\mvc\controller\dataTypes
 */
class UtDate
{
    /** @var \qFW\mvc\controller\lang\ILang */
    private $lang;

    /** @var \qFW\mvc\controller\dataTypes\UtString */
    private $utStr;

    /** @var \qFW\mvc\controller\vocabulary\Voc */
    private $voc;

    /**
     * UtDate constructor.
     *
     * @param \qFW\mvc\controller\lang\ILang $lang
     */
    public function __construct(ILang $lang)
    {
        $this->lang = $lang;
        $this->utStr = new UtString($lang);

        $vocLang = 'qFW\mvc\controller\vocabulary\Voc' . $this->lang->getLang();
        $this->voc = new $vocLang();
    }

    /**
     * Convert from 'data' type to 'datetime'
     *
     * -> https://stackoverflow.com/questions/2215354/php-date-format-when-inserting-into-datetime-in-mysql
     *
     * @param string $data
     *
     * @return string
     */
    public function dateToDatetime(string $data): string
    {
        if ($data != '') {
            $a = strptime($data, '%d/%m/%Y');
            $timestamp = mktime(0, 0, 0, $a['tm_mon'] + 1, $a['tm_mday'], $a['tm_year'] + 1900);
            $mysqlDateTime = date('Y-m-d H:i:s', $timestamp);
        } else {
            $mysqlDateTime = '';
        }

        return $mysqlDateTime;
    }

    /**
     * Convert in european date (gg/mm/aaaa) the mysql datetime
     *
     * @param string $mysqlDatetime
     *
     * @return string
     */
    public function dateTimeToDate(string $mysqlDatetime): string
    {
        $ret = '';
        if (($mysqlDatetime != '') && (!$this->utStr->areEqual($mysqlDatetime, '0000-00-00 00:00:00'))) {
            $euDate = str_replace('/', '-', $mysqlDatetime);
            $ret = date('d/m/Y', strtotime($euDate));
        } else {
            /* Ok, Can not convert*/
        }
        return $ret;
    }

    /**
     * Convert from mysql datetime to european date and time
     *      with verbose text for today and yesterday
     *
     * @param      $mysqlDatetime
     * @param bool $verbose
     *
     * @return string
     * @throws \Exception
     */
    public function dateTimeToDateTime($mysqlDatetime, bool $verbose = false): string
    {
        $ret = '';
        if (is_string($mysqlDatetime)) {
            if ($mysqlDatetime == '') {
                throw new \Exception('Empty date');
            }


            if (!$this->utStr->areEqual($mysqlDatetime, '0000-00-00 00:00:00')) {
                $euDate = str_replace('/', '-', $mysqlDatetime);
                $time = strtotime($euDate);

                $ret = date('d/m/Y H:i:s', $time);
                if ($verbose) {
                    // Today
                    if (date('Y-m-d') == date('Y-m-d', $time)) {
                        $ret = $this->voc->todayAt() . date('H:i:s', $time);
                    } else {
                        if (date('Y-m-d', strtotime('yesterday')) == date('Y-m-d', $time)) {
                            // Yesterday
                            $ret = $this->voc->yesterdayAt() . date('H:i:s', $time);
                        } else {/*Ok, show full date*/
                        }
                    }
                } else {
                    /*Ok, return non verbose*/
                }
            } else {
                /*Ok, return ''*/
            }
        } elseif (is_null($mysqlDatetime)) {
            /* Ok, could be null */
        } else {
            throw new \Exception('Variable not string and not null');
        }
        return $ret;
    }

    /**
     * Check if range is valid, error if end date comes befor start date
     *   date in gg/mm/aaaa format
     *
     * @param string $initialDate
     * @param string $finalDate
     *
     * @return bool
     */
    public function checkDateRange(string $initialDate, string $finalDate): bool
    {
        $res = false;

        $dateStart = strtotime(str_replace('/', '-', $initialDate));
        $dateEnd = strtotime(str_replace('/', '-', $finalDate));
        if ($dateEnd > $dateStart) {
            $res = true;
        } else {
            /*Ok, return false*/
        }

        return $res;
    }

    /**
     * Return current or given date in mysql format
     *
     * @param $end
     *
     * @return \DateTime|false
     * @throws \Exception
     */
    public function getDateMax($end)
    {
        // If undefined end time, calculate diff from now
        if (is_null($end)) {
            $dateMax = date_create(date('Y-m-d H:i:s'));
        } elseif (is_string($end)) {
            $data = str_replace('/', '-', $end);
            $dateMax = date_create($data);
        } else {
            throw new \Exception('TimeLaps not null and not string' . var_dump($end));
        }
        return $dateMax;
    }

    /**
     * Date interval to seconds
     *
     * -> https://stackoverflow.com/questions/3176609/calculate-total-seconds-in-php-dateinterval
     *
     * @param $dataMax
     * @param $dataMin
     *
     * @return int
     */
    public function dateIntervalToSeconds($dataMax, $dataMin): int
    {
        return $dataMax->getTimestamp() - $dataMin->getTimestamp();
    }

    /**
     * Return seconds between two dates
     *
     * @param string $init
     * @param        $end
     *
     * @return string
     * @throws \Exception
     */
    public function secondsBetweenDates(string $init, $end): string
    {
        $dateMin = self::getDateMax($init);
        $dateMax = self::getDateMax($end);

        return $this->utStr->getCleanString($this->dateIntervalToSeconds($dateMax, $dateMin));
    }

    /**
     * Get time difference
     *
     * @param string $init
     * @param        $end
     * @param int    $secTrigger
     *
     * @return string
     * @throws \Exception
     */
    public function timeLaps(string $init, $end, int $secTrigger): string
    {
        $dateMin = $this->getDateMax($init);
        $dateMax = $this->getDateMax($end);

        $timeDiff = date_diff($dateMax, $dateMin);
        $days = $timeDiff->format('%d');

        if ($days > 1) {
            $time = "$days {$this->voc->daysAnd()}";
        } elseif ($days == 1) {
            $time = "1 {$this->voc->dayAnd()}";
        } else {
            $time = '';
        }

        $time .= $timeDiff->format('%H:%I:%S');

        /***************
         * Bold ?
         ***************/

        $seconds = self::dateIntervalToSeconds($dateMax, $dateMin);
        if ($seconds > $secTrigger) {
            $tagOpen = '<span class="timeLapsTrigger">';
            $tagClose = '</span>';
        } else {
            $tagOpen = '';
            $tagClose = '';
        }

        return "$tagOpen+ $time$tagClose";
    }

    /**
     * Convert from data in european format to a timestamp for mysql
     *
     * -> https://stackoverflow.com/questions/113829/how-to-convert-date-to-timestamp-in-php
     *
     * @param string $data
     *
     * @return string
     */
    public function dateToTimestamp(string $data): string
    {
        $time = strptime($data, '%d/%m/%Y');
        $timestamp = mktime(0, 0, 0, $time['tm_mon'] + 1, $time['tm_mday'], $time['tm_year'] + 1900);
        $mysqlTimestamp = date('Y-m-d H:i:s', $timestamp);
        return $mysqlTimestamp;
    }

    /**
     * Convert date to php timestamp
     *
     * @param string $data
     *
     * @return int
     */
    public function datetimeToTimestamp(string $data): int
    {
        $time = strptime($data, "%d/%m/%Y %H:%M:%S");
        $timestamp = mktime(
            $time['tm_hour'],
            $time['tm_min'],
            $time['tm_sec'],
            $time['tm_mon'] + 1,
            $time['tm_mday'],
            $time['tm_year'] + 1900
        );
        return $timestamp;
    }

    /**
     * Return datetime for sql DATETIME
     *
     * @return string
     */
    public function getCurrentDateTimeForDb(): string
    {
        $date = new \DateTime();
        $timestamp = $date->getTimestamp();

        return date('Y-m-d H:i:s', $timestamp);
    }
}
