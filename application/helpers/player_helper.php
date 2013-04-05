<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Player Helper
 */
class Player_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    protected static function _fetchObject($id)
    {
        $ci =& get_instance();

        $ci->load->database();
        $ci->load->model('Player_model');

        return $ci->Player_model->fetch($id);
    }

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
     * Return a Player's Full Name
     * @param  mixed $player  Player Object/Array
     * @return string         The Player's Full Name
     */
    public static function fullName($player)
    {
        $player = self::_convertObject($player);

        return "{$player->first_name} {$player->surname}";
    }

    /**
     * Return a Player's Full Name (in reverse)
     * @param  mixed $player  Player Object/Array
     * @return string         The Player's Full Name
     */
    public static function fullNameReverse($player)
    {
        $player = self::_convertObject($player);

        return "{$player->surname}, {$player->first_name}";
    }

    /**
     * Return a Player's Initial and Surname
     * @param  mixed $player  Player Object/Array
     * @return string         The Player's Full Name
     */
    public static function initialSurname($player)
    {
        $player = self::_convertObject($player);

        return substr($player->first_name, 0, 1) . '. ' . $player->surname;
    }

    /**
     * Return a Player's Average Rating
     * @param  decimal $rating  Player Object/Array
     * @return float            Formatted Player average rating
     */
    public static function rating($rating)
    {
        return number_format($rating, 2);
    }

    /**
     * Return a Player's Debut
     * @param  object $debut      Debut Object/Array
     * @return string             Formatted Player's Debut Details
     */
    public static function debut($debut)
    {
        $ci =& get_instance();
        $ci->lang->load('match');
        $ci->load->helper(array('competition', 'competition_stage', 'match', 'opposition', 'player', 'utility'));

        return Match_helper::score($debut->match_id) . " " . $ci->lang->line('match_vs') . " " .  Opposition_helper::name($debut->opposition_id) . " - " . Match_helper::fullCompetitionNameCombined($debut) . " (" . Utility_helper::formattedDate($debut->date, "jS M 'y") . ")";
    }

    /**
     * Return a Player's Abbreviated Positions
     * @param  object $debut      Debut Object/Array
     * @return string             Formatted Player's Abbreviated Positions
     */
    public static function positionsAbbreviated($positions)
    {
        $ci =& get_instance();
        $ci->lang->load('global');
        $ci->load->helper(array('position'));

        $formattedPositions = array();

        foreach ($positions as $position) {
            $formattedPositions[] = Position_helper::abbreviation($position->position_id);
        }

        return count($formattedPositions) > 0 ? implode(", ", $formattedPositions) : $ci->lang->line('global_none');
    }

    /**
     * Return a Player's First Goal
     * @param  object $firstGoal    First Goal Object/Array
     * @return string               Formatted Player's First Goal Details
     */
    public static function firstGoal($firstGoal)
    {
        $ci =& get_instance();
        $ci->lang->load('match');
        $ci->load->helper(array('competition', 'competition_stage', 'match', 'opposition', 'player', 'utility'));

        return Match_helper::score($firstGoal->match_id) . " " . $ci->lang->line('match_vs') . " " .  Opposition_helper::name($firstGoal->opposition_id) . " - " . Match_helper::fullCompetitionNameCombined($firstGoal) . " (" . Utility_helper::formattedDate($firstGoal->date, "jS M 'y") . ")";
    }

    /**
     * Return a Player's Time between Debut & First Goal
     * @param  object $debut      Debut Object/Array
     * @return string             Time between Debut & First Goal
     */
    public static function timeBetweenDebutAndFirstGoal($time)
    {
        $ci =& get_instance();
        $ci->lang->load('global');
        $ci->load->helper(array('competition', 'competition_stage', 'match', 'opposition', 'player', 'utility'));

        return Utility_helper::daysElapsed($time->days_elapsed);
    }

    /**
     * Return a Player's Games between Debut & First Goal
     * @param  object $debut      Debut Object/Array
     * @return string             Time between Debut & First Goal
     */
    public static function gamesBetweenDebutAndFirstGoal($game)
    {
        $ci =& get_instance();
        $ci->lang->load('global');
        $ci->load->helper(array('competition', 'competition_stage', 'match', 'opposition', 'player', 'utility'));

        return Utility_helper::gamesElapsed($game->games_elapsed);
    }
}