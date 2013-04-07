<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Match Helper
 */
class Match_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Match_model');
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
        $resultFullTime = '';
        $resultAET = '';
        $resultPens = '';

        if (is_null($match->status) && is_null($match->h) && is_null($match->date)) {
            return $ci->lang->line('match_t_b_c');
        }

        if (is_null($match->status) && is_null($match->h) && !is_null($match->date)) {
            return date('g:i', strtotime($match->date)) . ' ' . $ci->lang->line('match_k_o');
        }

        if (!is_null($match->h)) {
            $resultFullTime = "{$match->h} - {$match->a}";
        }

        if (!is_null($match->h_et)) {
            $resultFullTime .= ' ' . $ci->lang->line('match_a_e_t');
            $resultAET = ", {$match->h_et} - {$match->a_et} " . $ci->lang->line('match_f_t');
        }

        if (!is_null($match->h_pen)) {
            $resultPens = ", {$match->h_pen} - {$match->a_pen} " . $ci->lang->line('match_pens');
        }

        return $resultFullTime . $resultPens . $resultAET;
    }

    public function longScore($match)
    {
        $ci =& get_instance();

        $match = self::_convertObject($match);

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

        $resultFullTime = '';
        $resultAET = '';
        $resultPens = '';

        if (is_null($match->status) && is_null($match->h) && is_null($match->date)) {
            return $ci->lang->line('match_to_be_confirmed');
        }

        if (is_null($match->status) && is_null($match->h) && !is_null($match->date)) {
            return date('g:i', strtotime($match->date)) . ' ' . $ci->lang->line('match_kick_off');
        }

        if (!is_null($match->h)) {
            $resultFullTime = "{$match->h} - {$match->a}";
        }

        if (!is_null($match->h_et)) {
            $resultFullTime .= ' ' . $ci->lang->line('match_after_extra_time');
            $resultAET = ", {$match->h_et} - {$match->a_et} " . $ci->lang->line('match_full_time');
        }

        if (!is_null($match->h_pen)) {
            $resultPens = ", {$match->h_pen} - {$match->a_pen} " . $ci->lang->line('match_on_penalties');
        }

        return $resultFullTime . $resultPens . $resultAET;
    }

    /**
     * Return Competition Name (and Stage if set)
     * @param  mixed $competition  Competition Object/Array
     * @return string              The Competition's Description
     */
    public static function fullCompetitionNameCombined($match)
    {
        $ci =& get_instance();
        $ci->load->helper(array('competition', 'competition_stage'));

        $match = self::_convertObject($match);

        return Competition_helper::name($match->competition_id) . (is_null($match->competition_stage_id) ? '' : ', ' . Competition_Stage_helper::name($match->competition_stage_id));
    }

    /**
     * Return shortened Competition Name (and Stage if set)
     * @param  mixed $competition  Competition Object/Array
     * @return string              The Competition's Description
     */
    public static function shortCompetitionNameCombined($match)
    {
        $ci =& get_instance();
        $ci->load->helper(array('competition', 'competition_stage'));

        $match = self::_convertObject($match);

        return Competition_helper::shortName($match->competition_id) . (is_null($match->competition_stage_id) ? '' : ', ' . Competition_Stage_helper::abbreviation($match->competition_stage_id));
    }

    /**
     * Return a Match's Venue
     * @param  mixed $match        Match Object/Array
     * @return string              The Match's Vnue
     */
    public static function venue($match)
    {
        $ci =& get_instance();
        $ci->lang->load('match');

        $match = self::_convertObject($match);

        switch ($match->venue) {
            case 'h':
                return $ci->lang->line('match_h');
                break;
            case 'a':
                return $ci->lang->line('match_a');
                break;
            case 'n':
                return $ci->lang->line('match_n');
                break;
        }

        return '';
    }

    /**
     * Return a Match's Long Venue
     * @param  mixed $match        Match Object/Array
     * @return string              The Match's Long Venue
     */
    public static function longVenue($match)
    {
        $ci =& get_instance();
        $ci->lang->load('match');

        $match = self::_convertObject($match);

        switch ($match->venue) {
            case 'h':
                return $ci->lang->line('match_home');
                break;
            case 'a':
                return $ci->lang->line('match_away');
                break;
            case 'n':
                return $ci->lang->line('match_neutral');
                break;
        }

        return '';
    }
}