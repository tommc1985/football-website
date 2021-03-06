<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Milestone Helper
 */
class Milestone_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * Return a Milestone's Text
     * @param  mixed $milestone    Milestone Object/Array
     * @return string              The Milestone Text
     */
    public static function player($milestone, $historial = true)
    {
        $ci =& get_instance();
        $ci->load->helper(array('competition', 'player', 'utility'));
        $ci->lang->load('milestone');

        if ($historial) {
            $tense = 'past';
        } else {
            $tense = 'future';
            $milestone->statistic_key++;
        }

        if ($milestone->type == 'overall' && $milestone->season == 'career' && $milestone->statistic_key == '1' && $milestone->statistic_group == 'nth_appearance') {
            return sprintf($ci->lang->line("milestone_{$tense}_debut"), Player_helper::fullName($milestone->player_id));
        }

        $type = 'overall';
        $competition = '';
        if ($milestone->type != 'overall') {
            $type = 'competition';
            $competition = Competition_helper::type($milestone->type);
        }

        $season = 'career';
        if ($milestone->season != 'career') {
            $season = 'season';
        }

        $playerName = $milestone->player_id == 0 ? 'Own Goal' : Player_helper::fullName($milestone->player_id);

        $languageVariable = "milestone_{$tense}_{$milestone->statistic_group}_{$type}_{$season}";

        // Don't show certain millestones on preview page
        if (!$historial) {

            switch (true) {
                case $milestone->statistic_key == '1' && $milestone->statistic_group != 'nth_appearance' :
                case in_array($milestone->statistic_key, array(2,3,4,6,7,8,9)) :
                case in_array($milestone->statistic_key, array(5)) && $season == 'career':
                    return '';
            }
        }

        return sprintf($ci->lang->line($languageVariable), $playerName, Utility_helper::ordinalWithSuffix($milestone->statistic_key), $competition);
    }
}