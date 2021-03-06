<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * League Statistics Helper
 */
class League_Statistics_helper
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

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("league_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("league_statistics_no_{$statisticGroup}"); ?></p>
    <?php
    }

    /**
     * Display statistics involving single matches
     * @param  array $matches          Matches linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @param  mixed $season           The selected season
     * @return NULL
     */
    protected static function _displayMatches($matches, $statisticGroup, $season)
    {
        $ci =& get_instance();

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("league_statistics_{$statisticGroup}") . '</h3>';
        echo '<h4>' . League_Match_helper::score($matches[0]) . '</h4>';
        foreach ($matches as $match) { ?>
            <span itemscope itemtype="http://schema.org/SportsEvent"><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($match->date, "c"); ?>"><?php echo Utility_helper::shortDate($match->date); ?></time>, <span itemprop="name"><?php echo Opposition_helper::name($match->h_opposition_id) . ' ' . $ci->lang->line("match_vs") . ' ' . Opposition_helper::name($match->a_opposition_id); ?></span></span><br />
        <?php
        }
    }

    /**
     * Display statistics involving sequences
     * @param  array $sequences        Sequences linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @param  mixed $season           The selected season
     * @return NULL
     */
    protected static function _displaySequences($sequences, $statisticGroup, $season)
    {
        $ci =& get_instance();

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("league_statistics_{$statisticGroup}") . '</h3>';
        echo '<h4>' . $sequences[0]->sequence; ?> <?php echo $sequences[0]->sequence == 1 ? $ci->lang->line("league_statistics_match") : $ci->lang->line("league_statistics_matches"); ?><?php echo '</h4>'; ?><?php
        foreach ($sequences as $sequence) {
            $ongoing = ($season == Season_model::fetchCurrentSeason() && $sequence->ongoing ? '*' : ''); ?>
            <span itemscope itemtype="http://schema.org/SportsTeam"><span itemprop="name"><?php echo Opposition_helper::name($sequence->clubId); ?></span></span>,
            <?php
            if ($sequence->sequence == 1) {
                echo Utility_helper::shortDate($sequence->sequenceStart) . $ongoing; ?><br />
            <?php
            } else {
                echo Utility_helper::shortDate($sequence->sequenceStart); ?> - <?php echo Utility_helper::shortDate($sequence->sequenceFinish) . $ongoing; ?><br />
            <?php
            }
        }

        if ($season == Season_model::fetchCurrentSeason()) { ?>
            <p class="muted"><?php echo $ci->lang->line("league_statistics_ongoing_denotes"); ?></p>
        <?php
        }
    }

    /**
     * Display statistics involving single numbers
     * @param  array $numbers          Numbers linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @param  mixed $season           The selected season
     * @return NULL
     */
    protected static function _displaySingleNumbers($numbers, $statisticGroup, $season)
    {
        $ci =& get_instance();

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("league_statistics_{$statisticGroup}") . '</h3>';
        echo '<h4>' . $numbers[0]->number; ?> <?php echo $numbers[0]->number == 1 ? $ci->lang->line("league_statistics_match") : $ci->lang->line("league_statistics_matches"); ?><?php echo '</h4>'; ?><?php
        foreach ($numbers as $number) { ?>
            <span itemscope itemtype="http://schema.org/SportsTeam"><span itemprop="name"><?php echo Opposition_helper::name($number->opposition_id); ?></span></span><br /><?php
        }
    }

    /**
     * Show Biggest Wins
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function biggestWin($statistics, $season, $venue = '')
    {
        $statisticGroup = "biggest_win" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Highest Scoring Draws
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function highestScoringDraw($statistics, $season, $venue = '')
    {
        $statisticGroup = "highest_scoring_draw" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Highest Scoring Matches
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function highestScoringMatch($statistics, $season, $venue = '')
    {
        $statisticGroup = "highest_scoring_match" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Winning Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestWinningSequence($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_winning_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Losing Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestLosingSequence($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_losing_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Drawing Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestDrawingSequence($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_drawing_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Unbeaten Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestUnbeatenSequence($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_unbeaten_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Sequences without a win
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestSequenceWithoutWin($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_sequence_without_win" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Sequences of Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestCleanSheetSequence($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_clean_sheet_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Sequences without Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestSequenceWithoutCleanSheet($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_sequence_without_clean_sheet" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Scoring Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestScoringSequence($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_scoring_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Longest Sequences without Scoring
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestSequenceWithoutScoring($statistics, $season, $venue = '')
    {
        $statisticGroup = "longest_sequence_without_scoring" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Highest Number of Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function mostCleanSheetsInASeason($statistics, $season, $venue = '')
    {
        $statisticGroup = "most_clean_sheets_in_a_season" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySingleNumbers($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Lowest Number of Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function leastCleanSheetsInASeason($statistics, $season, $venue = '')
    {
        $statisticGroup = "least_clean_sheets_in_a_season" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySingleNumbers($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Highest Number of Failed to Scores
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function mostFailToScoreInASeason($statistics, $season, $venue = '')
    {
        $statisticGroup = "most_fail_to_score_in_a_season" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySingleNumbers($statistics[$statisticGroup], $statisticGroup, $season);
    }

    /**
     * Show Lowest Number of Failed to Scores
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function leastFailToScoreInASeason($statistics, $season, $venue = '')
    {
        $statisticGroup = "least_fail_to_score_in_a_season" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySingleNumbers($statistics[$statisticGroup], $statisticGroup, $season);
    }
}