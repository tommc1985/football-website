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

        return $date == '' ? '&nbsp;' : date("jS M Y", $timestamp);
    }

    /**
     * Return a formatted long date/time based on the parsed value
     * @param  mixed $date    Date value
     * @return string         The formatted long date/time
     */
    public static function longDateTime($date)
    {
        $timestamp = self::_convertDate($date);

        return $date == '' ? '&nbsp;' : date("l jS F Y, g.ia", $timestamp);
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

        return $date == '' ? '&nbsp;' : date($format, $timestamp);
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

    /**
     * Return number with it's Ordinal Suffix
     * @param  mixed $number   Games
     * @return string         Games Elapsed
     */
    public static function ordinalWithSuffix($number)
    {
        return $number . self::ordinalSuffix($number);
    }

    /**
     * Return the Ordinal Suffix of the passed number
     * @param  mixed $number   Games
     * @return string         Games Elapsed
     */
    public static function ordinalSuffix($number)
    {
        if (!in_array(($number % 100),array(11,12,13))) {
            switch ($number % 10) {
                case 1:
                    return 'st';
                case 2:
                    return 'nd';
                case 3:
                    return 'rd';
            }
        }

        return 'th';
    }

    /**
     * Return if a number is close to a particular milestone
     * @param  mixed $number   Number
     * @param  mixed $withins  How close the number must be to a milestone to be deemed possible
     * @return mixed           What milestone is that number near, if any?
     */
    public static function withinMilestone($number, $withins = array(1, 2, 3))
    {
        $milestones = array(1, 2, 3, 5, 10, 15, 20, 25, 30, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300, 325, 350, 375, 400, 500, 1000);

        foreach ($withins as $within) {
            foreach ($milestones as $milestone) {
                switch (true) {
                    case $milestone == ($number + $within):
                        return array(
                            'milestone' => $milestone,
                            'within' => $within,
                            );
                        break;
                }
            }
        }

        return false;
    }

    /**
     * Return partial string of supplied content
     * @param  string $string  Original Content
     * @param  int $limit      Number of Characters
     * @param  string $break   Character to use to separater
     * @param  string $pad     Characters to pad
     * @return string          Partial String
     */
    public static function partialContent($string, $limit, $break = ".", $pad = "...")
    {
        $string = strip_tags($string);
        // return with no change if string is shorter than $limit
        if(strlen($string) <= $limit)
        {
            return $string;
        }

        // is $break present between $limit and the end of the string?
        if(false !== ($breakpoint = strpos($string, $break, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }

        return $string;
    }
}
