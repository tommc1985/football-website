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

        <h3 id="<?php echo $statisticGroup;?>" name="<?php echo $statisticGroup; ?>"><?php echo $ci->lang->line("club_statistics_{$statisticGroup}"); ?></h3>
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

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>';
        foreach ($matches as $match) { ?>
            <dl itemscope itemtype="http://schema.org/SportsEvent">
                <dt><a href="<?php echo site_url("match/view/id/{$match->id}"); ?>"><?php echo Match_helper::score($match); ?></a></dt>
                <dd><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($match->date, "c"); ?>"><?php echo Utility_helper::shortDate($match->date); ?></time> <span itemprop="name" itemscope itemtype="http://schema.org/SportsTeam"><?php echo $ci->lang->line("match_vs"); ?> <span itemprop="name"><?php echo Opposition_helper::name($match->opposition_id); ?></span> - <?php echo Match_helper::shortCompetitionNameCombined($match); ?></span></dd>
            </dl>
        <?php
        }
    }

    /**
     * Display statistics involving sequences
     * @param  array $sequences        Sequences linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @return NULL
     */
    protected static function _displaySequences($sequences, $statisticGroup, $season)
    {
        $ci =& get_instance();

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <dl>
            <dt>
        <?php
        echo $sequences[0]->sequence; ?> <?php echo $sequences[0]->sequence == 1 ? $ci->lang->line("club_statistics_match") : $ci->lang->line("club_statistics_matches"); ?></dt><dd><?php
        foreach ($sequences as $sequence) {
            $ongoing = ($season == 'all-time' || $season == Season_model::fetchCurrentSeason()) && $sequence->ongoing ? '*' : '';

            if ($sequence->sequence == 1) {
                echo Utility_helper::shortDate($sequence->sequenceStart) . $ongoing; ?><br />
            <?php
            } else {
                echo Utility_helper::shortDate($sequence->sequenceStart); ?> - <?php echo Utility_helper::shortDate($sequence->sequenceFinish) . $ongoing; ?><br />
            <?php
            }
        } ?>
            </dd>
        </dl>

        <p class="muted"><?php echo $ci->lang->line("club_statistics_ongoing_denotes"); ?></p>
        <?php
    }

    /**
     * Display statistics involving attendances
     * @param  array $matches          Matches linked to Statistic
     * @param  string $statisticGroup  The Statistic Group string
     * @return NULL
     */
    protected static function _displayAttendances($matches, $statisticGroup)
    {
        $ci =& get_instance();

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>';
        foreach ($matches as $match) { ?>
        <dl itemscope itemtype="http://schema.org/SportsEvent">
            <dt>
            <a href="<?php echo site_url("match/view/id/{$match->id}"); ?>"><?php echo Match_helper::attendance($match); ?></a></dt>
            <dd><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($match->date, "c"); ?>"><?php echo Utility_helper::shortDate($match->date); ?></time> <span itemprop="name" itemscope itemtype="http://schema.org/SportsTeam"><?php echo $ci->lang->line("match_vs"); ?> <span itemprop="name"><?php echo Opposition_helper::name($match->opposition_id); ?></span> - <?php echo Match_helper::shortCompetitionNameCombined($match); ?></span></dd>
        </dl>
        <?php
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

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Biggest Losses
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function biggestLoss($statistics, $season, $venue = '')
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

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup);
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

        self::_displayMatches($statistics[$statisticGroup], $statisticGroup);
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
     * Show Quickest Goals
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function quickestGoal($statistics, $season, $venue = '')
    {
        $statisticGroup = "quickest_goal" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
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
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function cleanSheetsInASeason($statistics, $season, $venue = '')
    {
        $statisticGroup = "clean_sheets_in_a_season" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>';
        foreach ($statistics[$statisticGroup] as $cleanSheets) { ?>
            <?php echo $cleanSheets; ?>
        <?php
        }
    }

    /**
     * Show Number of Failed to Scores
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function failToScoreInASeason($statistics, $season, $venue = '')
    {
        $statisticGroup = "fail_to_score_in_a_season" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            return false;
        }

        $ci =& get_instance();

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>';
        foreach ($statistics[$statisticGroup] as $failedToScores) { ?>
            <?php echo $failedToScores; ?>
        <?php
        }
    }

    /**
     * Show Oldest Appearance Holder
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
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

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <span itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($appearance->player_id); ?></span> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?></span><br />
        <?php
        }
    }

    /**
     * Show Youngest Appearance Holder
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
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

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <span itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($appearance->player_id); ?></span> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?></span><br />
        <?php
        }
    }

    /**
     * Show Oldest Debutant
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
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

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <span itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($appearance->player_id); ?></span> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?></span><br />
        <?php
        }
    }

    /**
     * Show Youngest Debutant
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
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

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <span itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($appearance->player_id); ?></span> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?></span><br />
        <?php
        }
    }

    /**
     * Show Oldest Scorer
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
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

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <span itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($appearance->scorer_id); ?></span> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?></span><br />
        <?php
        }
    }

    /**
     * Show Youngest Scorer
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
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

        echo "<h3 id='{$statisticGroup}' name='{$statisticGroup}'>" . $ci->lang->line("club_statistics_{$statisticGroup}") . '</h3>'; ?>
        <?php echo Utility_helper::daysElapsed($statistics[$statisticGroup][0]->age); ?><br />
        <?php
        foreach ($statistics[$statisticGroup] as $appearance) { ?>
            <span itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo Player_helper::fullName($appearance->scorer_id); ?></span> <?php echo $ci->lang->line("match_vs"); ?> <?php echo Opposition_helper::name($appearance->opposition_id); ?> - <?php echo Match_helper::shortCompetitionNameCombined($appearance); ?>, <?php echo Utility_helper::shortDate($appearance->date); ?></span><br />
        <?php
        }
    }

    /**
     * Show Highest Attendances
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function highestAttendance($statistics, $season, $venue = '')
    {
        $statisticGroup = "highest_attendance" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayAttendances($statistics[$statisticGroup], $statisticGroup);
    }

    /**
     * Show Lowest Attendances
     * @param  array $statistics  Full set of Statistics
     * @param  mixed $season      The selected season
     * @param  string $venue      Venue Variant of Statistic
     * @return NULL
     */
    public static function lowestAttendance($statistics, $season, $venue = '')
    {
        $statisticGroup = "lowest_attendance" . (strlen($venue) > 0 ? "_{$venue}" : '');
        if (!isset($statistics[$statisticGroup])) {
            self::_displayNoData($statisticGroup);
            return false;
        }

        self::_displayAttendances($statistics[$statisticGroup], $statisticGroup);
    }
}