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
     * Return a Player's Full Name
     * @param  mixed $player    Player Object/Array
     * @param  mixed $withLink  Wrap text in a href link
     * @return string           The Player's Full Name
     */
    public static function fullName($player, $withLink = true)
    {
        $player = self::_convertObject($player);

        if ($withLink) {
            return "<a href='" . site_url("player/view/id/{$player->id}") . "'>{$player->first_name} {$player->surname}</a>";
        }

        return "{$player->first_name} {$player->surname}";
    }

    /**
     * Return a Player's Full Name (in reverse)
     * @param  mixed $player    Player Object/Array
     * @param  mixed $withLink  Wrap text in a href link
     * @return string           The Player's Full Name
     */
    public static function fullNameReverse($player, $withLink = true)
    {
        $player = self::_convertObject($player);

        if ($withLink) {
            return "<a href='" . site_url("player/view/id/{$player->id}") . "'>{$player->surname}, {$player->first_name}</a>";
        }

        return "{$player->surname}, {$player->first_name}";
    }

    /**
     * Return a Player's Initial and Surname
     * @param  mixed $player  Player Object/Array
     * @param  mixed $withLink  Wrap text in a href link
     * @return string         The Player's Full Name
     */
    public static function initialSurname($player, $withLink = true)
    {
        $player = self::_convertObject($player);

        if ($withLink) {
            return "<a href='" . site_url("player/view/id/{$player->id}") . "'>" . substr($player->first_name, 0, 1) . '. ' . $player->surname . "</a>";
        }

        return substr($player->first_name, 0, 1) . '. ' . $player->surname;
    }

    /**
     * Return a Player's First Name
     * @param  mixed $player    Player Object/Array
     * @param  mixed $withLink  Wrap text in a href link
     * @return string           The Player's First Name
     */
    public static function firstName($player, $withLink = true)
    {
        $player = self::_convertObject($player);

        if ($withLink) {
            return "<a href='" . site_url("player/view/id/{$player->id}") . "'>{$player->first_name}</a>";
        }

        return "{$player->first_name}";
    }

    /**
     * Return a Player's Gender
     * @param  mixed $player    Player Object/Array
     * @return string           The Player's Gender
     */
    public static function gender($player)
    {
        $player = self::_convertObject($player);

        $ci =& get_instance();
        $ci->lang->load('global');
        $genders = Player_model::fetchGenders();

        if (isset($genders[$player->gender])) {
            return $genders[$player->gender];
        }

        return $ci->lang->line('global_unknown');
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
     * Return a Player's Debut
     * @param  object $debut      Debut Object/Array
     * @return string             Formatted Player's Debut Details
     */
    public static function debut($debut)
    {
        $ci =& get_instance();
        $ci->lang->load('match');
        $ci->load->helper(array('competition', 'competition_stage', 'match', 'opposition', 'player', 'utility'));

        return "<a href='" . site_url("/match/view/id/{$debut->match_id}") . "'>"  . Match_helper::score($debut->match_id) . "</a> " . $ci->lang->line('match_vs') . " " .  Opposition_helper::name($debut->opposition_id) . " - " . Match_helper::fullCompetitionNameCombined($debut) . " (" . Utility_helper::formattedDate($debut->date, "jS M 'y") . ")";
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

        return "<a href='" . site_url("/match/view/id/{$firstGoal->match_id}") . "'>" . Match_helper::score($firstGoal->match_id) . "</a> " . $ci->lang->line('match_vs') . " " .  Opposition_helper::name($firstGoal->opposition_id) . " - " . Match_helper::fullCompetitionNameCombined($firstGoal) . " (" . Utility_helper::formattedDate($firstGoal->date, "jS M 'y") . ")";
    }

    /**
     * Return a Player's Time between Debut & First Goal
     * @param  object $time      Debut Object/Array
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
     * @param  object $game       Game Object/Array
     * @return string             Games between Debut & First Goal
     */
    public static function gamesBetweenDebutAndFirstGoal($game)
    {
        $ci =& get_instance();
        $ci->lang->load('global');
        $ci->load->helper(array('competition', 'competition_stage', 'match', 'opposition', 'player', 'utility'));

        return Utility_helper::gamesElapsed($game->games_elapsed);
    }

    /**
     * Return a Player's Awards
     * @param  object $awards     Awards Array
     * @return string             Awards
     */
    public static function awards($awards)
    {
        $ci =& get_instance();
        $ci->lang->load('global');
        $ci->lang->load('award');
        $ci->load->helper(array('award', 'utility'));

        $formattedAwardStrings = array();

        foreach ($awards as $award) {
            switch ($award->placing) {
                case '1':
                    $placing = $ci->lang->line('award_winner');
                    break;
                case '2':
                    $placing = $ci->lang->line('award_runner_up');
                    break;
                default:
                    $placing = sprintf($ci->lang->line('award_nth_place'), Utility_helper::ordinalWithSuffix($award->placing));
            }

            $formattedAwardStrings[] = Award_helper::longName($award->id) . ' ' . Utility_helper::formattedSeason($award->season) . ' - ' . $placing;
        }

        return implode("<br />", $formattedAwardStrings);
    }

    /**
     * Return a Player's Appearance Status
     * @param  object $awards     Awards Array
     * @return string             Awards
     */
    public static function appearanceStatus($status)
    {
        $ci =& get_instance();

        switch ($status) {
            case 'starter':
                return $ci->lang->line('player_start');
                break;
            case 'substitute':
                return $ci->lang->line('player_sub');
                break;
        }

        return $ci->lang->line('player_unused');
    }

    /**
     * Return an OrderBy link for the specified data
     * @param  object $awards     Awards Array
     * @return string             Awards
     */
    public static function orderByLink($baseURL, $field, $orderBy, $order)
    {
        $ci =& get_instance();

        if ($field != $orderBy) {
            if (in_array($field, array('name'))) {
                return "<a href='{$baseURL}/order-by/{$field}'><img src='" . site_url('assets/themes/default/img/icons/sort-none.png') . "' alt='" . $ci->lang->line('sort_none') . "' /></a>";
            }

            return "<a href='{$baseURL}/order-by/{$field}/order/desc'><img src='" . site_url('assets/themes/default/img/icons/sort-none.png') . "' alt='" . $ci->lang->line('sort_none') . "' /></a>";
        }

        if ($order == 'desc') {
            return "<a href='{$baseURL}/order-by/{$field}'><img src='" . site_url('assets/themes/default/img/icons/sort-desc.png') . "' alt='" . $ci->lang->line('sort_desc') . "' /></a>";
        }

        return "<a href='{$baseURL}/order-by/{$field}/order/desc'><img src='" . site_url('assets/themes/default/img/icons/sort-asc.png') . "' alt='" . $ci->lang->line('sort_asc') . "' /></a>";
    }
}