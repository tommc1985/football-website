<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Player Goal Statistics Helper
 */
class Player_Goal_Statistics_helper
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
    protected static function _displayNoData($statisticGroup, $statisticKey)
    {
        $ci =& get_instance(); ?>

        <h3 id="<?php echo "{$statisticGroup}_{$statisticKey}"; ?>"><?php echo $ci->lang->line("player_goal_statistics_{$statisticGroup}_{$statisticKey}"); ?></h3>
        <p><?php echo $ci->lang->line("player_goal_statistics_no_{$statisticGroup}_{$statisticKey}"); ?></p>
    <?php
    }

    /**
     * Display statistics involving a Goalscorer
     * @param  array $players          Players linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @param  string $statisticKey    Statistics Key
     * @return NULL
     */
    protected static function _displayScorerTable($statistics, $statisticGroup, $statisticKey)
    {
        $ci =& get_instance(); ?>

        <h3 id="<?php echo "{$statisticGroup}_{$statisticKey}"; ?>"><?php echo $ci->lang->line("player_goal_statistics_{$statisticGroup}_{$statisticKey}"); ?></h3>
        <p><?php echo $ci->lang->line("player_goal_statistics_{$statisticGroup}_{$statisticKey}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line("player_goal_statistics_goals"); ?></td>
                    <td class="width-85-percent"><?php echo $ci->lang->line("player_goal_statistics_scorer"); ?></td>
                </tr>
            </thead>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup][$statisticKey] as $scorerId => $goals) { ?>
                <tr itemscope itemtype="http://schema.org/Person">
                    <td class="width-15-percent text-align-center"><?php echo $goals; ?></td>
                    <td><span itemprop="name"><?php echo $scorerId ? Player_helper::fullName($scorerId) : $ci->lang->line("player_goal_statistics_own_goal"); ?></span></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Display statistics involving an Assister
     * @param  array $players          Players linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @param  string $statisticKey    Statistics Key
     * @return NULL
     */
    protected static function _displayAssisterTable($statistics, $statisticGroup, $statisticKey)
    {
        $ci =& get_instance(); ?>

        <h3 id="<?php echo "{$statisticGroup}_{$statisticKey}"; ?>"><?php echo $ci->lang->line("player_goal_statistics_{$statisticGroup}_{$statisticKey}"); ?></h3>
        <p><?php echo $ci->lang->line("player_goal_statistics_{$statisticGroup}_{$statisticKey}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line("player_goal_statistics_goals"); ?></td>
                    <td class="width-85-percent"><?php echo $ci->lang->line("player_goal_statistics_assister"); ?></td>
                </tr>
            </thead>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup][$statisticKey] as $scorerId => $goals) { ?>
                <tr itemscope itemtype="http://schema.org/Person">
                    <td class="width-15-percent text-align-center"><?php echo $goals; ?></td>
                    <td><span itemprop="name"><?php echo $scorerId ? Player_helper::fullName($scorerId) : $ci->lang->line("player_goal_statistics_no_assist"); ?></span></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
    <?php
    }

    /**
     * Show Most Common Strike Partners
     * @param  array $statistics  Full set of Statistics
     * @return NULL
     */
    public static function scoringCombination($statistics)
    {
        $statisticGroup = "by_scorer";
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        $ci =& get_instance(); ?>

        <h3 id="<?php echo $statisticGroup;?>"><?php echo $ci->lang->line("player_goal_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("player_goal_statistics_{$statisticGroup}_explanation"); ?></p>
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-15-percent text-align-center"><?php echo $ci->lang->line("player_goal_statistics_goals"); ?></td>
                    <td class="width-45-percent"><?php echo $ci->lang->line("player_goal_statistics_scorer"); ?></td>
                    <td class="width-40-percent"><?php echo $ci->lang->line("player_goal_statistics_assister"); ?></td>
                </tr>
            </thead>
            <tbody>
        <?php
        foreach ($statistics[$statisticGroup] as $combination) { ?>
                <tr>
                    <td class="text-align-center"><?php echo $combination->value; ?></td>
                    <td itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo $combination->scorerId ? Player_helper::fullName($combination->scorerId) : $ci->lang->line("player_goal_statistics_own_goal"); ?></span></td>
                    <td itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo $combination->assisterId ? Player_helper::fullName($combination->assisterId) : $ci->lang->line("player_goal_statistics_no_assist"); ?></span></td>
                </tr>
        <?php
        } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Show Scorers By Goal Type
     * @param  array $statistics     Full set of Statistics
     * @param  string $statisticKey  Statistics Key
     * @return NULL
     */
    public static function scorerByGoalType($statistics, $statisticKey)
    {
        $statisticGroup = "by_goal_type";
        if (!isset($statistics[$statisticGroup][$statisticKey])) {
            self::_displayNoData($statisticGroup, $statisticKey);
            return false;
        }

        self::_displayScorerTable($statistics, $statisticGroup, $statisticKey);
    }

    /**
     * Show Scorers By Body Part
     * @param  array $statistics     Full set of Statistics
     * @param  string $statisticKey  Statistics Key
     * @return NULL
     */
    public static function scorerByBodyPart($statistics, $statisticKey)
    {
        $statisticGroup = "by_body_part";
        if (!isset($statistics[$statisticGroup][$statisticKey])) {
            self::_displayNoData($statisticGroup, $statisticKey);
            return false;
        }

        self::_displayScorerTable($statistics, $statisticGroup, $statisticKey);
    }

    /**
     * Show Scorers By Distance
     * @param  array $statistics     Full set of Statistics
     * @param  string $statisticKey  Statistics Key
     * @return NULL
     */
    public static function scorerByDistance($statistics, $statisticKey)
    {
        $statisticGroup = "by_distance";
        if (!isset($statistics[$statisticGroup][$statisticKey])) {
            self::_displayNoData($statisticGroup, $statisticKey);
            return false;
        }

        self::_displayScorerTable($statistics, $statisticGroup, $statisticKey);
    }

    /**
     * Show Scorers By Minute Interval
     * @param  array $statistics     Full set of Statistics
     * @param  string $statisticKey  Statistics Key
     * @return NULL
     */
    public static function scorerByMinuteInterval($statistics, $statisticKey)
    {
        $statisticGroup = "by_minute_interval";
        if (!isset($statistics[$statisticGroup][$statisticKey])) {
            self::_displayNoData($statisticGroup, $statisticKey);
            return false;
        }

        self::_displayScorerTable($statistics, $statisticGroup, $statisticKey);
    }

    /**
     * Show Assisters By Goal Type
     * @param  array $statistics     Full set of Statistics
     * @param  string $statisticKey  Statistics Key
     * @return NULL
     */
    public static function assistByGoalType($statistics, $statisticKey)
    {
        $statisticGroup = "assist_by_goal_type";
        if (!isset($statistics[$statisticGroup][$statisticKey])) {
            self::_displayNoData($statisticGroup, $statisticKey);
            return false;
        }

        self::_displayAssisterTable($statistics, $statisticGroup, $statisticKey);
    }
}