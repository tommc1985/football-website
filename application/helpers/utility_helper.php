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
        if ($season == 'all-time') {
            return 'All Time';
        }

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

    /**
     * Return the days elapsed from days passed
     * @param  mixed $days    Days
     * @return string         Days Elapsed
     */
    public static function daysElapsed($days)
    {
        $ci =& get_instance();
        $ci->lang->load('global');

        $days = (int) $days;

        $years = 0;

        if ($days >= 365) {
            $years = floor($days / 365);
        }

        $remainderDays = $days % 365;

        $string = "";

        if ($years > 0) {
            $string .= "{$years} ";
            if ($years == 1) {
                $string .= $ci->lang->line('global_year');
            } else {
                $string .= $ci->lang->line('global_years');
            }
            $string .= ", ";
        }

        $string .= "{$remainderDays} ";
        if ($remainderDays == 1) {
            $string .= $ci->lang->line('global_day');
        } else {
            $string .= $ci->lang->line('global_days');
        }

        return $string;
    }

    /**
     * Return the games elapsed
     * @param  mixed $games   Games
     * @return string         Games Elapsed
     */
    public static function gamesElapsed($games)
    {
        $ci =& get_instance();
        $ci->lang->load('global');

        $games = (int) $games;

        $string = "{$games} ";
        if ($games == 1) {
            $string .= $ci->lang->line('global_game');
        } else {
            $string .= $ci->lang->line('global_games');
        }

        return $string;
    }
}