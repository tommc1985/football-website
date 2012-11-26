<?php
class Cache_Club_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public $tableName;
    public $queueTableName;

    public $methodMap;
    public $hungryMethodMap;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
        $this->ci->load->model('Season_model');

        $this->tableName = 'cache_club_statistics';
        $this->queueTableName = 'cache_queue_club_statistics';

        $this->methodMap = array(
            'biggest_win'                          => 'biggestWin',
            'biggest_loss'                         => 'biggestLoss',
            'highest_scoring_draw'                 => 'highestScoringDraw',
            'longest_winning_sequence'             => 'longestWinningSequence',
            'longest_losing_sequence'              => 'longestLosingSequence',
            'longest_drawing_sequence'             => 'longestDrawingSequence',
            'longest_unbeaten_sequence'            => 'longestUnbeatenSequence',
            'longest_sequence_without_win'         => 'longestSequenceWithoutWin',
            'longest_clean_sheet_sequence'         => 'longestCleanSheetSequence',
            'longest_sequence_without_clean_sheet' => 'longestSequenceWithoutCleanSheet',
            'longest_scoring_sequence'             => 'longestScoringSequence',
            'longest_sequence_without_scoring'     => 'longestSequenceWithoutScoring',
            'quickest_goal'                        => 'quickestGoal',
            'clean_sheets_in_a_season'             => 'cleanSheetsInASeason',/*

            - Most clean sheets in a season
            - Points gained by a players goals
            - Consecutive games a player has scored */
        );

        $this->hungryMethodMap = array(
            'oldest_appearance_holder'             => 'oldestAppearanceHolder',
            'youngest_appearance_holder'           => 'youngestAppearanceHolder',
            'oldest_debutant'                      => 'oldestDebutant',
            'youngest_debutant'                    => 'youngestDebutant',
            'oldest_scorer'                        => 'oldestScorer',
            'youngest_scorer'                      => 'youngestScorer',
        );
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int|NULL $byType     Group by "type" or "overall"
     * @param  int|NULL $season     Season "career"
     * @param  int|NULL $playerId   Single Player
     * @param  int|NULL $cacheData  What specific data to cache
     * @return boolean
     */
    public function insertEntry($byType = NULL, $season = NULL, $cacheData = NULL)
    {
        $data = array('by_type' => $byType,
            'season' => $season,
            'cache_data' => $cacheData,
            'date_added' => time(),
            'date_updated' => time());

        return $this->db->insert($this->queueTableName, $data);
    }

    /**
     * Update row in process queue table to be processed
     * @param  object $object   Existing row in table
     * @return boolean
     */
    public function updateEntry($object)
    {
        $object->date_updated = time();
        $this->db->where('id', $object->id);
        return $this->db->update($this->queueTableName, $object);
    }

    /**
     * Fetch latest rows to be processed/cached
     * @return results Query Object
     */
    public function fetchLatest($limit = 5)
    {
        $this->db->select('*')
            ->from($this->queueTableName)
            ->where('in_progress', 0)
            ->where('completed', 0)
            ->where('deleted', 0)
            ->order_by('date_added', 'asc')
            ->limit($limit, 0);

        return $this->db->get();
    }

    /**
     * Process latest tasks in queue
     * @return int  Number of rows processed
     */
    public function processQueuedRows()
    {
        $rowCount = 0;
        $rows = $this->fetchLatest(2);

        foreach($rows->result() as $row) {
            $this->processQueuedRow($row);
            $rowCount++;
        }

        return $rowCount;
    }

    /**
     * Process latest task in queue
     * @param  object $row  Row from queued table
     * @return boolean      Result of exectuted query
     */
    public function processQueuedRow($row)
    {
        $startUnixTime = time();

        $row->in_progress = 1;
        $this->updateEntry($row);

        if (!empty($row->cache_data)) {
            if (isset($this->methodMap[$row->cache_data])) {
                $method = $this->methodMap[$row->cache_data];
            } else {
                $method = $this->hungryMethodMap[$row->cache_data];
            }

            $this->$method(false, $row->season);

            if (!is_null($row->by_type)) { // Generate all statistics
                $competitionTypes = $this->ci->Season_model->fetchCompetitionTypes();

                foreach ($competitionTypes as $competitionType) {
                    $this->$method($competitionType, $row->season);
                }
            }

        } else {
            $this->generateAllStatistics();
        }

        $row->in_progress = 0;
        $row->completed = 1;

        $finishUnixTime = time();
        $row->process_duration = $finishUnixTime - $startUnixTime;

        $row->peak_memory_usage = number_format(memory_get_peak_usage(true) / 1048576, 2);

        return $this->updateEntry($row);
    }

    /**
     * Replace placeholder with values from array
     * @param  array $data Key/Value pairs of data to replace placeholders
     * @return string       Return SQL with specified values inserted
     */
    public function insertCache($statisticGroup, $type, $season, $statisticKey, $statisticValue)
    {
        $object = new stdClass();

        $object->type = 'overall';
        if (is_string($type)) {
            $object->type = $type;
        }

        $object->season = 'career';
        if (!is_null($season)) {
            $object->season = (int) $season;
        }

        $object->statistic_group = $statisticGroup;
        $object->statistic_key = $statisticKey;
        $object->statistic_value = $statisticValue;

        $this->db->insert($this->tableName, $object);
    }

    /**
     * Generate and cache Biggest Win Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function biggestWin($type = false, $season = NULL)
    {
        self::deleteBiggestWin($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, (m.h - m.a) as difference
FROM matches m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE (m.h - m.a) = (
    SELECT (m.h - m.a) as difference
    FROM matches m
    LEFT JOIN competition c ON c.id = m.competition
    WHERE !ISNULL(m.h)
        AND m.h > m.a
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND m.deleted = 0
        AND c.deleted = 0
    ORDER BY difference DESC, m.h DESC
    LIMIT 1)
    AND c.competitive = 1
    AND m.h > m.a " . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.deleted = 0
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('biggest_win', $type, $season, $row->difference, serialize($row));
        }
    }

    /**
     * Generate and cache Biggest Loss Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function biggestLoss($type = false, $season = NULL)
    {
        self::deleteBiggestLoss($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, (m.a - m.h) as difference
FROM matches m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE (m.a - m.h) = (
    SELECT (m.a - m.h) as difference
    FROM matches m
    LEFT JOIN competition c ON c.id = m.competition
    WHERE !ISNULL(m.h)
        AND m.h < m.a
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND m.deleted = 0
        AND c.deleted = 0
    ORDER BY difference DESC, m.h DESC
    LIMIT 1)
    AND c.competitive = 1
    AND m.h < m.a " . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.deleted = 0
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('biggest_loss', $type, $season, $row->difference, serialize($row));
        }
    }

    /**
     * Generate and cache Highest Scoring Draw Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function highestScoringDraw($type = false, $season = NULL)
    {
        self::deleteHighestScoringDraw($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, (m.a + m.h) as total_goals
FROM matches m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE (m.a + m.h) = (
    SELECT (m.a + m.h) as total_goals
    FROM matches m
    LEFT JOIN competition c ON c.id = m.competition
    WHERE !ISNULL(m.h)
        AND m.h = m.a
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND m.deleted = 0
        AND c.deleted = 0
    ORDER BY total_goals DESC, m.h DESC
    LIMIT 1)
    AND c.competitive = 1
    AND m.h = m.a " . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.deleted = 0
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('highest_scoring_draw', $type, $season, $row->total_goals, serialize($row));
        }
    }

    /**
     * Generate and cache Longest Winning Sequence Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestWinningSequence($type = false, $season = NULL)
    {
        self::deleteLongestWinningSequence($type, $season);

        $this->sequenceBase("\$match->h > \$match->a", 'longest_winning_sequence', $type, $season);
    }

    /**
     * Generate and cache Longest Losing Sequence Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestLosingSequence($type = false, $season = NULL)
    {
        self::deleteLongestLosingSequence($type, $season);

        $this->sequenceBase("\$match->h < \$match->a", 'longest_losing_sequence', $type, $season);
    }

    /**
     * Generate and cache Longest Drawing Sequence Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestDrawingSequence($type = false, $season = NULL)
    {
        self::deleteLongestDrawingSequence($type, $season);

        $this->sequenceBase("\$match->h == \$match->a", 'longest_drawing_sequence', $type, $season);
    }

    /**
     * Generate and cache Longest Unbeaten Sequence Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestUnbeatenSequence($type = false, $season = NULL)
    {
        self::deleteLongestUnbeatenSequence($type, $season);

        $this->sequenceBase("\$match->h == \$match->a || \$match->h > \$match->a", 'longest_unbeaten_sequence', $type, $season);
    }

    /**
     * Generate and cache Longest Sequence without Win Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestSequenceWithoutWin($type = false, $season = NULL)
    {
        self::deleteLongestSequenceWithoutWin($type, $season);

        $this->sequenceBase("\$match->h < \$match->a || \$match->h == \$match->a", 'longest_sequence_without_win', $type, $season);
    }

    /**
     * Generate and cache Longest Clean Sheet Sequence Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestCleanSheetSequence($type = false, $season = NULL)
    {
        self::deleteLongestCleanSheetSequence($type, $season);

        $this->sequenceBase("\$match->a == 0", 'longest_clean_sheet_sequence', $type, $season);
    }

    /**
     * Generate and cache Longest Sequence Without Clean Sheet Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestSequenceWithoutCleanSheet($type = false, $season = NULL)
    {
        self::deleteLongestSequenceWithoutCleanSheet($type, $season);

        $this->sequenceBase("\$match->a > 0", 'longest_sequence_without_clean_sheet', $type, $season);
    }

    /**
     * Generate and cache Longest Scoring Sequence Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestScoringSequence($type = false, $season = NULL)
    {
        self::deleteLongestScoringSequence($type, $season);

        $this->sequenceBase("\$match->h > 0", 'longest_scoring_sequence', $type, $season);
    }

    /**
     * Generate and cache Longest Sequence Without Scoring Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function longestSequenceWithoutScoring($type = false, $season = NULL)
    {
        self::deleteLongestSequenceWithoutScoring($type, $season);

        $this->sequenceBase("\$match->h == 0", 'longest_sequence_without_scoring', $type, $season);
    }

    /**
     * Generate and cache Base Method Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function sequenceBase($comparisonCode, $statisticGroup, $type = false, $season = NULL)
    {
        $this->deleteRows($statisticGroup, $type, $season);

        $matches = $this->ci->Season_model->fetchMatches($type, $season);

        $records = array();

        $record = new stdClass();
        $record->sequence = 0;
        $record->sequenceStart = '';
        $record->sequenceFinish = '';

        if (count($matches) > 0) {
            $highestSequence = 0;
            $currentSequence = 0;
            $currentSequenceStart = '';
            $currentSequenceFinish = '';

            foreach ($matches as $match) {
                eval("\$comparisonResult = " . $comparisonCode . ";");

                if ($comparisonResult) {
                    $currentSequence++;

                    if ($currentSequenceStart == '') {
                        $currentSequenceStart = $match->date;
                    }

                    $currentSequenceFinish = $match->date;
                } else {
                    $record = new stdClass();

                    if ($currentSequence > $highestSequence) {
                        $highestSequence = $currentSequence;

                        $record->sequence = $currentSequence;
                        $record->sequenceStart = $currentSequenceStart;
                        $record->sequenceFinish = $currentSequenceFinish;

                        $records = array();
                        $records[] = $record;
                    } elseif ($currentSequence == $highestSequence && $currentSequence > 0) {
                        $record->sequence = $currentSequence;
                        $record->sequenceStart = $currentSequenceStart;
                        $record->sequenceFinish = $currentSequenceFinish;

                        $records[] = $record;
                    }

                    $currentSequence = 0;
                    $currentSequenceStart = '';
                    $currentSequenceFinish = '';
                }
            }

            $record = new stdClass();
            if ($currentSequence > $highestSequence) {
                $highestSequence = $currentSequence;

                $record->sequence = $currentSequence;
                $record->sequenceStart = $currentSequenceStart;
                $record->sequenceFinish = $currentSequenceFinish;

                $records = array();
                $records[$match->id] = $record;
            } elseif ($currentSequence == $highestSequence && $currentSequence > 0) {
                $record->sequence = $currentSequence;
                $record->sequenceStart = $currentSequenceStart;
                $record->sequenceFinish = $currentSequenceFinish;

                $records[$match->id] = $record;
            }
        }

        foreach ($records as $record) {
            $this->insertCache($statisticGroup, $type, $season, $highestSequence, serialize($record));
        }
    }

    /**
     * Generate and cache Quickest Goal Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function quickestGoal($type = false, $season = NULL)
    {
        self::deleteQuickestGoal($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT m.*, g.match_id, g.scorer_id, g.assist_id, g.minute, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM goal g
LEFT JOIN matches m ON g.match_id = m.id
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
LEFT JOIN player p ON p.id = g.scorer_id
WHERE g.minute = (
    SELECT g.minute as goal_minute
    FROM goal g
    LEFT JOIN matches m ON g.match_id = m.id
    LEFT JOIN competition c ON c.id = m.competition
    WHERE g.minute > 0 && g.minute <= 120
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND m.deleted = 0
        AND c.deleted = 0
        AND m.status != 'unused'
    ORDER BY goal_minute ASC, m.date DESC
    LIMIT 1)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND m.deleted = 0
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('quickest_goal', $type, $season, $row->minute, serialize($row));
        }
    }

    /**
     * Generate and cache Oldest Appearance Holder Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function oldestAppearanceHolder($type = false, $season = NULL)
    {
        self::deleteOldestAppearanceHolder($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
LEFT JOIN player p ON p.id = m.player_id
WHERE m.age = (
    SELECT m.age
    FROM view_appearances_ages m
    LEFT JOIN competition c ON c.id = m.competition
    WHERE !ISNULL(m.age)
        AND m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND c.deleted = 0
    ORDER BY m.age DESC, m.date DESC
    LIMIT 1)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('oldest_appearance_holder', $type, $season, $row->age, serialize($row));
        }
    }

    /**
     * Generate and cache Youngest Appearance Holder Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function youngestAppearanceHolder($type = false, $season = NULL)
    {
        self::deleteYoungestAppearanceHolder($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
LEFT JOIN player p ON p.id = m.player_id
WHERE m.age = (
    SELECT m.age
    FROM view_appearances_ages m
    LEFT JOIN competition c ON c.id = m.competition
    WHERE !ISNULL(m.age)
        AND m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND c.deleted = 0
    ORDER BY m.age ASC, m.date DESC
    LIMIT 1)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('youngest_appearance_holder', $type, $season, $row->age, serialize($row));
        }
    }

    /**
     * Generate and cache Oldest Debutant Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function oldestDebutant($type = false, $season = NULL)
    {
        self::deleteOldestDebutant($type, $season);

        $whereConditions = array();
        $whereConditions2 = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
            $whereConditions2[] = "(m2.competition_type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation,
    (SELECT COUNT(m2.id)
    FROM view_appearances_ages m2
    LEFT JOIN competition c ON c.id = m2.competition
    WHERE !ISNULL(m2.age)
        AND m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND c.deleted = 0
        AND m2.date < m.date
        AND m2.player_id = m.player_id) as game_number
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE !ISNULL(m.age)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
HAVING game_number = 0
ORDER BY m.age DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        $age = false;
        foreach ($rows as $row) {
            if ($age === false) {
                $age = $row->age;
            }

            if ($age == $row->age) {
                $this->insertCache('oldest_debutant', $type, $season, $row->age, serialize($row));
            }
        }
    }

    /**
     * Generate and cache Youngest Debutant Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function youngestDebutant($type = false, $season = NULL)
    {
        self::deleteYoungestDebutant($type, $season);

        $whereConditions = array();
        $whereConditions2 = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
            $whereConditions2[] = "(m2.competition_type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation,
    (SELECT COUNT(m2.id)
    FROM view_appearances_ages m2
    LEFT JOIN competition c ON c.id = m2.competition
    WHERE !ISNULL(m2.age)
        AND m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND c.deleted = 0
        AND m2.date < m.date
        AND m2.player_id = m.player_id) as game_number
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE !ISNULL(m.age)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
HAVING game_number = 0
ORDER BY m.age ASC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        $age = false;
        foreach ($rows as $row) {
            if ($age === false) {
                $age = $row->age;
            }

            if ($age == $row->age) {
                $this->insertCache('youngest_debutant', $type, $season, $row->age, serialize($row));
            }
        }
    }

    /**
     * Generate and cache Oldest Scorer Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function oldestScorer($type = false, $season = NULL)
    {
        self::deleteOldestScorer($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM view_appearances_matches_combined m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
LEFT JOIN player p ON p.id = m.player_id
WHERE m.age = (
    SELECT m.age
    FROM view_appearances_matches_combined m
    LEFT JOIN competition c ON c.id = m.competition
    WHERE !ISNULL(m.age)
        AND m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND c.deleted = 0
        AND m.goals > 0
    ORDER BY m.age DESC, m.date DESC
    LIMIT 1)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('oldest_scorer', $type, $season, $row->age, serialize($row));
        }
    }

    /**
     * Generate and cache Youngest Scorer Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function youngestScorer($type = false, $season = NULL)
    {
        self::deleteYoungestScorer($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM view_appearances_matches_combined m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
LEFT JOIN player p ON p.id = m.player_id
WHERE m.age = (
    SELECT m.age
    FROM view_appearances_matches_combined m
    LEFT JOIN competition c ON c.id = m.competition
    WHERE !ISNULL(m.age)
        AND m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND c.deleted = 0
        AND m.goals > 0
    ORDER BY m.age ASC, m.date DESC
    LIMIT 1)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('youngest_scorer', $type, $season, $row->age, serialize($row));
        }
    }

    /**
     * Generate and cache Clean Sheets In A Season Statistics by season or type
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function cleanSheetsInASeason($type = false, $season = NULL)
    {
        self::deleteCleanSheetsInASeason($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $sql = "SELECT COUNT(m.id) as games
FROM matches m
LEFT JOIN competition c ON c.id = m.competition
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND c.deleted = 0
    AND m.a = 0";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('clean_sheets_in_a_season', $type, $season, $row->games, '');
        }
    }

    /**
     * Delete cached Biggest Win Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteBiggestWin($byType = false, $season = NULL)
    {
        return $this->deleteRows('biggest_win', $byType, $season);
    }

    /**
     * Delete cached Biggest Loss Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteBiggestLoss($byType = false, $season = NULL)
    {
        return $this->deleteRows('biggest_loss', $byType, $season);
    }

    /**
     * Delete cached Highest Scoring Draw Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteHighestScoringDraw($byType = false, $season = NULL)
    {
        return $this->deleteRows('highest_scoring_draw', $byType, $season);
    }

    /**
     * Delete cached Longest Winning Sequence Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestWinningSequence($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_winning_sequence', $byType, $season);
    }

    /**
     * Delete cached Longest Losing Sequence Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestLosingSequence($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_losing_sequence', $byType, $season);
    }

    /**
     * Delete cached Longest Drawing Sequence Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestDrawingSequence($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_drawing_sequence', $byType, $season);
    }

    /**
     * Delete cached Longest Unbeaten Sequence Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestUnbeatenSequence($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_unbeaten_sequence', $byType, $season);
    }

    /**
     * Delete cached Longest Sequence Without a Win Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestSequenceWithoutWin($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_sequence_without_win', $byType, $season);
    }

    /**
     * Delete cached Longest Clean Sheet Sequence Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestCleanSheetSequence($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_clean_sheet_sequence', $byType, $season);
    }

    /**
     * Delete cached Longest Sequence Without a Clean Sheet Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestSequenceWithoutCleanSheet($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_sequence_without_clean_sheet', $byType, $season);
    }

    /**
     * Delete cached Longest Scoring Sequence Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestScoringSequence($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_scoring_sequence', $byType, $season);
    }

    /**
     * Delete cached Longest Sequence without Scoring Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteLongestSequenceWithoutScoring($byType = false, $season = NULL)
    {
        return $this->deleteRows('longest_sequence_without_scoring', $byType, $season);
    }

    /**
     * Delete cached Quickest Goal Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteQuickestGoal($byType = false, $season = NULL)
    {
        return $this->deleteRows('quickest_goal', $byType, $season);
    }

    /**
     * Delete cached Oldest Appearance Holder Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteOldestAppearanceHolder($byType = false, $season = NULL)
    {
        return $this->deleteRows('oldest_appearance_holder', $byType, $season);
    }

    /**
     * Delete cached Youngest Appearance Holder Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteYoungestAppearanceHolder($byType = false, $season = NULL)
    {
        return $this->deleteRows('youngest_appearance_holder', $byType, $season);
    }

    /**
     * Delete cached Oldest Debutant Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteOldestDebutant($byType = false, $season = NULL)
    {
        return $this->deleteRows('oldest_debutant', $byType, $season);
    }

    /**
     * Delete cached Youngest Debutant Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteYoungestDebutant($byType = false, $season = NULL)
    {
        return $this->deleteRows('youngest_debutant', $byType, $season);
    }

    /**
     * Delete cached Oldest Scorer Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteOldestScorer($byType = false, $season = NULL)
    {
        return $this->deleteRows('oldest_scorer', $byType, $season);
    }

    /**
     * Delete cached Youngest Scorer Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteYoungestScorer($byType = false, $season = NULL)
    {
        return $this->deleteRows('youngest_scorer', $byType, $season);
    }

    /**
     * Delete cached Most Clean Sheets in a Season Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteCleanSheetsInASeason($byType = false, $season = NULL)
    {
        return $this->deleteRows('clean_sheets_in_a_season', $byType, $season);
    }


    /**
     * Generate all statistics
     * @return boolean Whether query was executed correctly
     */
    public function generateAllStatistics()
    {
        //$this->emptyCache();

        $competitionTypes = $this->ci->Season_model->fetchCompetitionTypes();

        foreach ($this->methodMap as $method) {
            $this->$method();

            foreach ($competitionTypes as $competitionType) {
                $this->$method($competitionType);
            }
        }

        $season = $this->ci->Season_model->fetchEarliestYear();
        while($season <= Season_model::fetchCurrentSeason()){

            foreach ($this->methodMap as $method) {
                $this->$method(false, $season);

                foreach ($competitionTypes as $competitionType) {
                    $this->$method($competitionType, $season);
                }
            }

            $season++;
        }

        return true;
    }

    /**
     * Particular Club Statistics, based on Season and/or Competition Type
     * @param  int $statisticGroup  Statistic Group
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @return boolean              Whether query was executed correctly
     */
    public function deleteRows($statisticGroup, $type = false, $season = NULL)
    {
        $whereConditions = array();

        $whereConditions['statistic_group'] = $statisticGroup;
        $whereConditions['season']           = $season ? $season : 'career';

        if ($type) {
            $whereConditions['type']       = $type;
        } else {
            $whereConditions['type']       = 'overall';
        }

        return $this->db->delete($this->tableName, $whereConditions);
    }

    /**
     * Empty table of cached
     * @return boolean Whether query was executed correctly
     */
    public function emptyCache()
    {
        return $this->db->truncate($this->tableName);
    }
}