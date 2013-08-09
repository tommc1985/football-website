<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * League Match Helper
 */
class League_Match_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('League_Match_model');
        $ci->lang->load('league_match');
        $ci->lang->load('match');

        return $ci->Match_model->fetch($id);
    }

    /**
     * Convert Object
     * @param  object $object Passed Object
     * @return object         Returned Object
     */
    protected static function _convertObject($object)
    {
        if (!is_object($object)) {
            if (is_array($object)) {
                $object = (object) $object;
            } else {
                $object = self::_fetchObject($object);
            }
        }

        return $object;
    }

    /**
     * Return a Match's Score
     * @param  mixed $match        Match Object/Array
     * @return string              The Match's Score
     */
    public static function score($match)
    {
        $ci =& get_instance();

        $match = self::_convertObject($match);

        if (is_null($match->status) && is_null($match->h_score)) {
            return Utility_helper::formattedDate($match->date, "h:i a");
        } else {
            switch($match->status) {
                case 'hw': // Home walkeover
                    return $ci->lang->line('match_h_w');
                    break;
                case 'aw': // Away Walkover
                    return $ci->lang->line('match_a_w');
                    break;
                case 'p': // Postponed
                    return $ci->lang->line('match_p_p');
                    break;
                case 'a': // Abandoned
                    return $ci->lang->line('match_a_a');
                    break;
            }
        }

        return "{$match->h_score} - {$match->a_score}";
    }

    public function longScore($match)
    {
        $ci =& get_instance();

        $match = self::_convertObject($match);

        if (is_null($match->status) && is_null($match->h_score)) {
            return Utility_helper::formattedDate($match->date, "h:i a");
        } else {
            switch($match->status) {
                case 'hw': // Home walkeover
                    return $ci->lang->line('match_home_walkover');
                    break;
                case 'aw': // Away Walkover
                    return $ci->lang->line('match_away_walkover');
                    break;
                case 'p': // Postponed
                    return $ci->lang->line('match_postponed');
                    break;
                case 'a': // Abandoned
                    return $ci->lang->line('match_abandoned');
                    break;
            }
        }

        return "{$match->h_score} - {$match->a_score}";
    }
}