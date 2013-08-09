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

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
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

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        foreach ($players as $player) { ?>
                <tr itemscope itemtype="http://schema.org/Person">
                    <td class="width-5-percent text-align-center"><?php echo $player->$fieldValue; ?></td>
                    <td class="width-95-percent"><span itemprop="name"><?php echo Player_helper::fullName($player->player_id); ?></span> <?php echo $ci->lang->line("match_vs"); ?> <span itemscope itemtype="http://schema.org/SportsTeam"><span itemprop="name" itemprop="legalName"><?php echo Opposition_helper::name($player->opposition_id); ?></span></span> - <?php echo Match_helper::shortCompetitionNameCombined($player); ?>, <?php echo Utility_helper::shortDate($player->date); ?></td>
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
        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        foreach ($players as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo $player->$fieldValue; ?></td>
                    <td class="width-45-percent" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($player->player_1_id); ?></span></td>
                    <td class="width-45-percent" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($player->player_2_id); ?></span></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Hattricks
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @return NULL
     */
    public static function hattricks($statistics, $season)
    {
        $statisticGroup = "hattricks";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-5-percent text-align-center"><?php echo $player->goals; ?></td>
                    <td class="width-95-percent" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($player->player_id); ?></span> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($player->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($player); ?>, <?php echo Utility_helper::shortDate($player->date); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Real Points Gained
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function realPointsGained($statistics, $season)
    {
        $statisticGroup = "real_points_gained";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo $player->points_gained; ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-center"><?php echo $player->matches_played; ?> <?php echo $ci->lang->line($player->matches_played == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Points Gained
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function averagePointsGained($statistics, $season)
    {
        $statisticGroup = "average_points_gained";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo number_format($player->points_gained, 2); ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Real Points
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function realPoints($statistics, $season)
    {
        $statisticGroup = "real_points";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo $player->points; ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches_played; ?> <?php echo $ci->lang->line($player->matches_played == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Points
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function averagePoints($statistics, $season)
    {
        $statisticGroup = "average_points";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo number_format($player->points, 2); ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Real Goals Gained
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function realGoalsGained($statistics, $season)
    {
        $statisticGroup = "real_goals_gained";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo $player->goals_gained; ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches_played; ?> <?php echo $ci->lang->line($player->matches_played == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Goals Gained
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function averageGoalsGained($statistics, $season)
    {
        $statisticGroup = "average_goals_gained";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo number_format($player->goals_gained, 2); ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Real Goals
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function realGoals($statistics, $season)
    {
        $statisticGroup = "real_goals";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo $player->goals; ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches_played; ?> <?php echo $ci->lang->line($player->matches_played == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Goals For
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function averageGoalsFor($statistics, $season)
    {
        $statisticGroup = "average_goals_for";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo number_format($player->goals, 2); ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Goals Against
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function averageGoalsAgainst($statistics, $season)
    {
        $statisticGroup = "average_goals_against";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        $players = array_reverse($statistics[$statisticGroup]);
        foreach ($players as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo number_format($player->goals, 2); ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
            <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Total Clean Sheets
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function totalCleanSheets($statistics, $season)
    {
        $statisticGroup = "total_clean_sheets";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo $player->clean_sheets; ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Average Clean Sheets
     * @param  array $statistics     Full set of Statistics
     * @param  mixed $season         The selected season
     * @return NULL
     */
    public static function averageCleanSheets($statistics, $season)
    {
        $statisticGroup = "average_clean_sheets";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        $count = 0;
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-10-percent text-align-center"><?php echo number_format($player->clean_sheets, 2); ?></td>
                    <td class="width-65-percent"><?php echo Player_helper::fullName($player->player_id); ?></td>
                    <td class="width-25-percent text-align-left"><?php echo $player->matches; ?> <?php echo $ci->lang->line($player->matches == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                </tr>
        <?php
            $count++;
        }

        if ($count == 0) { ?>
                <tr>
                    <td><?php echo $ci->lang->line("player_statistics_no_players_within_threshold"); ?></td>
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
     * @param  mixed $season      The selected season
     * @return NULL
     */
    public static function consecutiveGamesScored($statistics, $season)
    {
        $statisticGroup = "consecutive_games_scored";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) {
            $ongoing = ($season == 'all-time' || $season == Season_model::fetchCurrentSeason()) && $player->ongoing ? '*' : ''; ?>
                <tr>
                    <td class="width-20-percent text-align-center"><?php echo $player->sequence; ?> <?php echo $ci->lang->line($player->sequence == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                    <td class="width-40-percent"><?php echo Player_helper::fullName($player->playerId); ?></td>
                    <td class="width-40-percent"><?php echo Utility_helper::shortDate($player->sequenceStart); ?> - <?php echo Utility_helper::shortDate($player->sequenceFinish) . $ongoing; ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>

        <p class="muted"><?php echo $ci->lang->line("player_statistics_ongoing_denotes"); ?></p>
    <?php
    }

    /**
     * Show Consecutive Games Assisted
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @return NULL
     */
    public static function consecutiveGamesAssisted($statistics, $season)
    {
        $statisticGroup = "consecutive_games_assisted";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) {
            $ongoing = ($season == 'all-time' || $season == Season_model::fetchCurrentSeason()) && $player->ongoing ? '*' : ''; ?>
                <tr>
                    <td class="width-20-percent text-align-center"><?php echo $player->sequence; ?> <?php echo $ci->lang->line($player->sequence == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                    <td class="width-40-percent"><?php echo Player_helper::fullName($player->playerId); ?></td>
                    <td class="width-40-percent"><?php echo Utility_helper::shortDate($player->sequenceStart); ?> - <?php echo Utility_helper::shortDate($player->sequenceFinish) . $ongoing; ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>

        <p class="muted"><?php echo $ci->lang->line("player_statistics_ongoing_denotes"); ?></p>
    <?php
    }

    /**
     * Show Consecutive Appearances
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @return NULL
     */
    public static function consecutiveAppearances($statistics, $season)
    {
        $statisticGroup = "consecutive_appearances";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) {
            $ongoing = ($season == 'all-time' || $season == Season_model::fetchCurrentSeason()) && $player->ongoing ? '*' : ''; ?>
                <tr>
                    <td class="width-20-percent text-align-center"><?php echo $player->sequence; ?> <?php echo $ci->lang->line($player->sequence == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                    <td class="width-40-percent"><?php echo Player_helper::fullName($player->playerId); ?></td>
                    <td class="width-40-percent"><?php echo Utility_helper::shortDate($player->sequenceStart); ?> - <?php echo Utility_helper::shortDate($player->sequenceFinish) . $ongoing; ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>

        <p class="muted"><?php echo $ci->lang->line("player_statistics_ongoing_denotes"); ?></p>
    <?php
    }

    /**
     * Show Consecutive Starting Appearances
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @return NULL
     */
    public static function consecutiveStartingAppearances($statistics, $season)
    {
        $statisticGroup = "consecutive_starting_appearances";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) {
            $ongoing = ($season == 'all-time' || $season == Season_model::fetchCurrentSeason()) && $player->ongoing ? '*' : ''; ?>
                <tr>
                    <td class="width-20-percent text-align-center"><?php echo $player->sequence; ?> <?php echo $ci->lang->line($player->sequence == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                    <td class="width-40-percent"><?php echo Player_helper::fullName($player->playerId); ?></td>
                    <td class="width-40-percent"><?php echo Utility_helper::shortDate($player->sequenceStart); ?> - <?php echo Utility_helper::shortDate($player->sequenceFinish) . $ongoing; ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>

        <p class="muted"><?php echo $ci->lang->line("player_statistics_ongoing_denotes"); ?></p>
    <?php
    }

    /**
     * Show Consecutive Substitute Appearances
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @return NULL
     */
    public static function consecutiveSubstituteAppearances($statistics, $season)
    {
        $statisticGroup = "consecutive_substitute_appearances";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $player) {
            $ongoing = ($season == 'all-time' || $season == Season_model::fetchCurrentSeason()) && $player->ongoing ? '*' : ''; ?>
                <tr>
                    <td class="width-20-percent text-align-center"><?php echo $player->sequence; ?> <?php echo $ci->lang->line($player->sequence == 1 ? "player_statistics_match" : "player_statistics_matches"); ?></td>
                    <td class="width-40-percent"><?php echo Player_helper::fullName($player->playerId); ?></td>
                    <td class="width-40-percent"><?php echo Utility_helper::shortDate($player->sequenceStart); ?> - <?php echo Utility_helper::shortDate($player->sequenceFinish) . $ongoing; ?></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>

        <p class="muted"><?php echo $ci->lang->line("player_statistics_ongoing_denotes"); ?></p>
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

    /**
     * Show Debut And First Goal Time Difference Statistics
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @return NULL
     */
    public static function debutAndFirstGoalTimeDifference($statistics, $season)
    {
        $statisticGroup = "debut_and_first_goal_time_difference";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        krsort($statistics[$statisticGroup]);
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-20-percent text-align-left"><?php echo sprintf($ci->lang->line("player_statistics_x_" . ($player->days_elapsed == 1 ? 'day' : 'days')), $player->days_elapsed); ?></td>
                    <td class="width-80-percent" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($player->player_id); ?></span></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Debut And First Goal Games Difference Statistics
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @return NULL
     */
    public static function debutAndFirstGoalGameDifference($statistics, $season)
    {
        $statisticGroup = "debut_and_first_goal_game_difference";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("player_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <tbody>
        <?php
        krsort($statistics[$statisticGroup]);
        foreach ($statistics[$statisticGroup] as $player) { ?>
                <tr>
                    <td class="width-20-percent text-align-left"><?php echo sprintf($ci->lang->line("player_statistics_x_" . ($player->games_elapsed == 1 ? 'game' : 'games')), $player->games_elapsed); ?></td>
                    <td class="width-80-percent" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($player->player_id); ?></span></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }
}