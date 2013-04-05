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
        $match = self::_convertObject($match);

        return "{$match->h} - {$match->a}";
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
        $match = self::_convertObject($match);

        switch ($match->venue) {
            case 'h':
                return 'H';
                break;
            case 'a':
                return 'A';
                break;
            case 'n':
                return 'N';
                break;
        }

        return '';
    }
}