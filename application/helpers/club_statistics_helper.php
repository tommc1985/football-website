<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Club Statistics Helper
 */
class Club_Statistics_helper
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

        <h3><?php echo $ci->lang->line("club_statistics_{$statisticGroup}"); ?></h3>
        <p><?php echo $ci->lang->line("club_statistics_no_{$statisticGroup}"); ?></p>
    <?php
    }

    /**
     * Display statistics involving single matches
     * @param  array $matches          Matches linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @return NULL
     */
    protected static function _displayMatches($matches, $statisticGroup)
    {
        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>';
        foreach ($matches as $match) { ?>
            <?php echo Match_helper::score($match); ?><br />
            <?php echo Utility_helper::shortDate($match->date); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($match->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($match); ?><br />
        <?php
        }
    }

    /**
     * Display statistics involving sequences
     * @param  array $sequences        Sequences linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @return NULL
     */
    protected static function _displaySequences($sequences, $statisticGroup)
    {
        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>';
        echo $sequences[0]->sequence; ?> <?php echo $sequences[0]->sequence == 1 ? $ci->lang->line("club_statistics_match") : $ci->lang->line("club_statistics_matches"); ?><br /><?php
        foreach ($sequences as $sequence) {
            echo Utility_helper::shortDate($sequence->sequenceStart); ?> - <?php echo Utility_helper::shortDate($sequence->sequenceFinish); ?><br />
        <?php
        }
    }

    /**
     * Show Biggest Wins
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function biggestWin($statistics, $venue = '')
    {
        $statisticGroup = "biggest_win" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Biggest Losses
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function biggestLoss($statistics, $venue = '')
    {
        $statisticGroup = "biggest_loss" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Highest Scoring Draws
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function highestScoringDraw($statistics, $venue = '')
    {
        $statisticGroup = "highest_scoring_draw" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Highest Scoring Matches
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function highestScoringMatch($statistics, $venue = '')
    {
        $statisticGroup = "highest_scoring_match" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Winning Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestWinningSequence($statistics, $venue = '')
    {
        $statisticGroup = "longest_winning_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Losing Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestLosingSequence($statistics, $venue = '')
    {
        $statisticGroup = "longest_losing_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Drawing Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestDrawingSequence($statistics, $venue = '')
    {
        $statisticGroup = "longest_drawing_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Unbeaten Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestUnbeatenSequence($statistics, $venue = '')
    {
        $statisticGroup = "longest_unbeaten_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Sequences without a win
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestSequenceWithoutWin($statistics, $venue = '')
    {
        $statisticGroup = "longest_sequence_without_win" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Sequences of Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestCleanSheetSequence($statistics, $venue = '')
    {
        $statisticGroup = "longest_clean_sheet_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Sequences without Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestSequenceWithoutCleanSheet($statistics, $venue = '')
    {
        $statisticGroup = "longest_sequence_without_clean_sheet" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Scoring Sequences
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestScoringSequence($statistics, $venue = '')
    {
        $statisticGroup = "longest_scoring_sequence" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Longest Sequences without Scoring
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function longestSequenceWithoutScoring($statistics, $venue = '')
    {
        $statisticGroup = "longest_sequence_without_scoring" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displaySequences($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Quickest Goals
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function quickestGoal($statistics, $venue = '')
    {
        $statisticGroup = "quickest_goal" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        '<?php echo $statistics[$statisticGroup][0]->minute; ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $goal) { ?>
            <?php echo Player_helper::fullName($goal->scorer_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($goal->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($goal); ?>, <?php echo Utility_helper::shortDate($goal->date); ?><br />
        <?php
        }
    }

    /**
     * Show Number of Clean Sheets
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function cleanSheetsInASeason($statistics, $venue = '')
    {
        $statisticGroup = "clean_sheets_in_a_season" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>';
        foreach ($statistics[$statisticGroup] as $cleanSheets) { ?>
            <?php echo $cleanSheets; ?>
        <?php
        }
    }

    /**
     * Show Number of Failed to Scores
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function failToScoreInASeason($statistics, $venue = '')
    {
        $statisticGroup = "fail_to_score_in_a_season" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>';
        foreach ($statistics[$statisticGroup] as $failedToScores) { ?>
            <?php echo $failedToScores; ?>
        <?php
        }
    }

    /**
     * Show Oldest Appearance Holder
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function oldestAppearanceHolder($statistics)
    {
        $statisticGroup = "oldest_appearance_holder";
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <?php echo Player_helper::fullName($appearance->player_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?><br />
        <?php
        }
    }

    /**
     * Show Youngest Appearance Holder
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function youngestAppearanceHolder($statistics)
    {
        $statisticGroup = "youngest_appearance_holder";
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <?php echo Player_helper::fullName($appearance->player_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?><br />
        <?php
        }
    }

    /**
     * Show Oldest Debutant
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function oldestDebutant($statistics)
    {
        $statisticGroup = "oldest_debutant";
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <?php echo Player_helper::fullName($appearance->player_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?><br />
        <?php
        }
    }

    /**
     * Show Youngest Debutant
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function youngestDebutant($statistics)
    {
        $statisticGroup = "youngest_debutant";
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <?php echo Player_helper::fullName($appearance->player_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?><br />
        <?php
        }
    }

    /**
     * Show Oldest Scorer
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function oldestScorer($statistics)
    {
        $statisticGroup = "oldest_scorer";
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <?php echo Player_helper::fullName($appearance->scorer_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?><br />
        <?php
        }
    }

    /**
     * Show Youngest Scorer
     * @param  array $statistics  Full set of Statistics
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function youngestScorer($statistics)
    {
        $statisticGroup = "youngest_scorer";
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo '<h3>' . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <?php echo Player_helper::fullName($appearance->scorer_id); ?> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?><br />
        <?php
        }
    }
}