<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Player Statistics Helper
 */
class Player_Statistics_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * What to display if no data exists
     * @param  string $statisticGroup  Statistic Group
     * @return NULL
     */
    protected static function _displayNoData($statisticGroup)
    {
        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_no_{$statisticGroup}"); ?></p>
    <?php
    }

    /**
     * Display statistics involving a table
     * @param  array $players          Players linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @return NULL
     */
    protected static function _displayTable($players, $statisticGroup, $fieldValue)
    {
        $ci =& get_instance();?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <table>
            <tbody>
        <?php
        foreach ($players as $player) { ?>
                <tr>
                    <td><?php echo $player->$fieldValue; ?></td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($player->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($player); ?>, <?php echo Utility_helper::shortDate($player->date); ?></td>


                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Display statistics involving a table
     * @param  array $players          Players linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @return NULL
     */
    protected static function _displayCombinationTable($players, $statisticGroup, $fieldValue)
    {
        $ci =& get_instance();
        $combinations = array(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($players as $player) {
            if (!in_array("{$player->player_1_id}_{$player->player_2_id}", $combinations)) { ?>
                <tr>
                    <td><?php echo $player->$fieldValue; ?></td>
                    <td><?php echo Player_helper::fullName($player->player_1_id); ?></td>
                    <td><?php echo Player_helper::fullName($player->player_2_id); ?></td>
                </tr>
        <?php
                $combinations[] = "{$player->player_1_id}_{$player->player_2_id}";
                $combinations[] = "{$player->player_2_id}_{$player->player_1_id}";
            }
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Hattricks
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function hattricks($statistics)
    {
        $statisticGroup = "hattricks";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->goals; ?></td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($player->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($player); ?>, <?php echo Utility_helper::shortDate($player->date); ?></td>


                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Real Points Gained
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function realPointsGained($statistics)
    {
        $statisticGroup = "real_points_gained";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->points_gained; ?> (<?php echo $player->matches_played; ?> <?php echo $ci->lang->line($player->matches_played == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Points Gained
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function averagePointsGained($statistics)
    {
        $statisticGroup = "average_points_gained";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->points_gained; ?> (<?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Real Points
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function realPoints($statistics)
    {
        $statisticGroup = "real_points";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->points; ?> (<?php echo $player->matches_played; ?> <?php echo $ci->lang->line($player->matches_played == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Points
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function averagePoints($statistics)
    {
        $statisticGroup = "average_points";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->points; ?> (<?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Real Goals Gained
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function realGoalsGained($statistics)
    {
        $statisticGroup = "real_goals_gained";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->goals_gained; ?> (<?php echo $player->matches_played; ?> <?php echo $ci->lang->line($player->matches_played == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Goals Gained
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function averageGoalsGained($statistics)
    {
        $statisticGroup = "average_goals_gained";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->goals_gained; ?> (<?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Real Goals
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function realGoals($statistics)
    {
        $statisticGroup = "real_goals";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->goals; ?> (<?php echo $player->matches_played; ?> <?php echo $ci->lang->line($player->matches_played == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Goals For
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function averageGoalsFor($statistics)
    {
        $statisticGroup = "average_goals_for";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->goals; ?> (<?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Goals Against
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function averageGoalsAgainst($statistics)
    {
        $statisticGroup = "average_goals_against";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        $players = array_reverse($statistics[$statisticGroup]);
        foreach ($players as $player) { ?>
                <tr>
                    <td><?php echo $player->goals; ?> (<?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Total Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function totalCleanSheets($statistics)
    {
        $statisticGroup = "total_clean_sheets";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->clean_sheets; ?> (<?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function averageCleanSheets($statistics)
    {
        $statisticGroup = "average_clean_sheets";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->clean_sheets; ?> (<?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?>)</td>
                    <td><?php echo Player_helper::fullName($player->player_id); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Consecutive Games Scored
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function consecutiveGamesScored($statistics)
    {
        $statisticGroup = "consecutive_games_scored";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->sequence; ?> <?php echo $ci->lang->line($player->sequence == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                    <td><?php echo Player_helper::fullName($player->playerId); ?></td>
                    <td><?php echo Utility_helper::shortDate($player->sequenceStart); ?> - <?php echo Utility_helper::shortDate($player->sequenceFinish); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Consecutive Games Assisted
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function consecutiveGamesAssisted($statistics)
    {
        $statisticGroup = "consecutive_games_assisted";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td><?php echo $player->sequence; ?> <?php echo $ci->lang->line($player->sequence == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                    <td><?php echo Player_helper::fullName($player->playerId); ?></td>
                    <td><?php echo Utility_helper::shortDate($player->sequenceStart); ?> - <?php echo Utility_helper::shortDate($player->sequenceFinish); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Most Common Two Players Playing Together
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function mostCommonTwoPlayerCombination($statistics)
    {
        $statisticGroup = "most_common_two_player_combination";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayCombinationTable($statistics[$statisticGroup], $statisticGroup, 'matches');
    }

    /**
     * Show Most Common Centre Back Pairing
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function mostCommonCentreBackPairing($statistics)
    {
        $statisticGroup = "most_common_centre_back_pairing";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayCombinationTable($statistics[$statisticGroup], $statisticGroup, 'matches');
    }

    /**
     * Show Most Common Centre Midfield Pairing
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function mostCommonCentreMidfieldPairing($statistics)
    {
        $statisticGroup = "most_common_centre_midfield_pairing";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayCombinationTable($statistics[$statisticGroup], $statisticGroup, 'matches');
    }

    /**
     * Show Most Common Right Hand Side Pairing
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function mostCommonRightHandSidePairing($statistics)
    {
        $statisticGroup = "most_common_right_hand_side_pairing";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayCombinationTable($statistics[$statisticGroup], $statisticGroup, 'matches');
    }

    /**
     * Show Most Common Left Hand Side Pairing
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function mostCommonLeftHandSidePairing($statistics)
    {
        $statisticGroup = "most_common_left_hand_side_pairing";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayCombinationTable($statistics[$statisticGroup], $statisticGroup, 'matches');
    }

    /**
     * Show Most Common Strike Partners
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function mostCommonStrikePartner($statistics)
    {
        $statisticGroup = "most_common_strike_partner";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayCombinationTable($statistics[$statisticGroup], $statisticGroup, 'matches');
    }
}