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

/**
 * Class UtDate
 *
 * @package qFW\mvc\controller\dataTypes
 */
class UtDate
{

    /**
     * Conversione da tipo data a datetime
     *
     * -> https://stackoverflow.com/questions/2215354/php-date-format-when-inserting-into-datetime-in-mysql
     *
     * @param string $data
     *
     * @return string
     */
    public static function dateToDatetime(string $data): string
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
    public static function dateTimeToDate(string $mysqlDatetime): string
    {
        $ret = '';
        if (($mysqlDatetime != '') && (!UtString::areEqual($mysqlDatetime, '0000-00-00 00:00:00'))) {
            $euDate = str_replace('/', '-', $mysqlDatetime);
            $ret = date('d/m/Y', strtotime($euDate));
        }
        return $ret;
    }

    /**
     * Conversion from mysql datetime to european date and time
     *      with verbose text for today and yesterday
     *
     * @param      $mysqlDatetime
     * @param bool $verbose if true, output will be verbose
     *
     * @return string
     */
    public static function dateTimeToDateTime($mysqlDatetime, bool $verbose = false): string
    {
        $ret = '';
        if (is_string($mysqlDatetime)) {
            if (($mysqlDatetime != '') && (!UtString::areEqual($mysqlDatetime, '0000-00-00 00:00:00'))) {
                $euDate = str_replace('/', '-', $mysqlDatetime);
                $time = strtotime($euDate);

                $ret = date('d/m/Y H:i:s', $time);
                if ($verbose) {
                    //oggi
                    if (date('Y-m-d') == date('Y-m-d', $time)) {
                        $ret = 'Oggi alle ' . date('H:i:s', $time);
                    } //ieri
                    else {
                        if (date('Y-m-d', strtotime('yesterday')) == date('Y-m-d', $time)) {
                            $ret = 'Ieri alle ' . date('H:i:s', $time);
                        } else {/*ok, mostra data completa*/
                        }
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Check if range is valid, error if end date comes befor start date
     *   data in formato gg/mm/aaaa
     *
     * @param string $dataIniziale
     * @param string $dataFinale
     *
     * @return bool
     */
    public static function checkDateRange(string $dataIniziale, string $dataFinale): bool
    {
        $esito = false;

        $dateStart = strtotime(str_replace('/', '-', $dataIniziale));
        $dateEnd = strtotime(str_replace('/', '-', $dataFinale));
        if ($dateEnd > $dateStart) {
            $esito = true;
        }

        return $esito;
    }

    /**
     * Return current or given date in mysql format
     *
     * @param $end
     *
     * @return \DateTime|false
     */
    public static function getDateMax($end)
    {
        // se tempo di fine non definito, calcola diff da adesso
        if (is_null($end)) {
            $dateMax = date_create(date('Y-m-d H:i:s'));
        } elseif (is_string($end)) {
            $data = str_replace('/', '-', $end);
            $dateMax = date_create($data);
        } else {
            die('timeLaps $end non nulla ne stringa'); /*@fixme throw error */
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
    public static function dateIntervalToSeconds($dataMax, $dataMin): int
    {
        //$reference = new DateTimeImmutable;
        //$endTime = $reference->add($dateInterval);

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
    public static function secondsBetweenDates(string $init, $end): string
    {
        $dateMin = self::getDateMax($init);
        $dateMax = self::getDateMax($end);

        return UtString::getCleanString(self::dateIntervalToSeconds($dateMax, $dateMin));
    }

    /**
     * Get time difference
     *
     * @param string $init
     * @param        $end
     * @param int    $secTrigger
     *
     * @return string
     */
    public static function timeLaps(string $init, $end, int $secTrigger): string
    {


        $dateMin = self::getDateMax($init);
        $dateMax = self::getDateMax($end);

        $timeDiff = date_diff($dateMax, $dateMin);
        $giorni = $timeDiff->format('%d');
        if ($giorni > 1) {
            $tempo = "$giorni giorni e ";
        } elseif ($giorni == 1) {
            $tempo = '1 giorno e ';
        } else {
            $tempo = '';
        }

        $tempo .= $timeDiff->format('%H:%I:%S');

        /***************
         * bold ?
         ***************/

        $seconds = self::dateIntervalToSeconds($dateMax, $dateMin);
        if ($seconds > $secTrigger) {
            $tagOpen = '<span class="timeLapsTrigger">';
            $tagClose = '</span>';
        } else {
            $tagOpen = '';
            $tagClose = '';
        }

        return "$tagOpen+ $tempo$tagClose";
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
    public static function dateToTimestamp(string $data): string
    {
        $a = strptime($data, '%d/%m/%Y');
        $timestamp = mktime(0, 0, 0, $a['tm_mon'] + 1, $a['tm_mday'], $a['tm_year'] + 1900);
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
    public static function datetimeToTimestamp(string $data): int
    {
        $a = strptime($data, "%d/%m/%Y %H:%M:%S");
        $timestamp = mktime(
            $a['tm_hour'],
            $a['tm_min'],
            $a['tm_sec'],
            $a['tm_mon'] + 1,
            $a['tm_mday'],
            $a['tm_year'] + 1900
        );
        return $timestamp;
    }
}
