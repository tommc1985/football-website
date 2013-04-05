<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Utility Helper
 */
class Utility_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * Return a formatted season
     * @param  int $season     Season
     * @return string          The formatted Season
     */
    public static function formattedSeason($season)
    {
        return $season . '/' . ($season + 1);
    }

    /**
     * Return a formatted date based on the parsed value
     * @param  mixed $date    Date value
     * @return mixed          The Converted Date
     */
    protected static function _convertDate($date)
    {
        if (is_numeric($date)) {
            return $date;
        }

        if (empty($date)) {
            return '';
        }

        return strtotime($date);
    }

    /**
     * Return a formatted short date based on the parsed value
     * @param  mixed $date    Date value
     * @return string         The formatted short date
     */
    public static function shortDate($date)
    {
        $timestamp = self::_convertDate($date);

        return $date == '' ? '' : date("d/m/y", $timestamp);
    }

    /**
     * Return a formatted date based on the parsed value
     * @param  mixed $date     Date value
     * @param  string $format  Date format
     * @return string          The formatted short date
     */
    public static function formattedDate($date, $format)
    {
        $timestamp = self::_convertDate($date);

        return $date == '' ? '' : date($format, $timestamp);
    }
}