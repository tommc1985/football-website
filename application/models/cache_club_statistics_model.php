<?php
class Cache_Club_Statistics_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public $tableName;
    public $queueTableName;

    public $methodMap;
    public $hungryMethodMap;

    public $venues;

    /**
     * Constructor
     * @return NULL
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
            'highest_scoring_match'                => 'highestScoringMatch',
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
            'clean_sheets_in_a_season'             => 'cleanSheetsInASeason',
            'fail_to_score_in_a_season'            => 'failToScoreInASeason',
            'highest_attendance'                   => 'highestAttendance',
            'lowest_attendance'                    => 'lowestAttendance',
        );

        $this->hungryMethodMap = array(
            'oldest_appearance_holder'             => 'oldestAppearanceHolder',
            'youngest_appearance_holder'           => 'youngestAppearanceHolder',
            'oldest_debutant'                      => 'oldestDebutant',
            'youngest_debutant'                    => 'youngestDebutant',
            'oldest_scorer'                        => 'oldestScorer',
            'youngest_scorer'                      => 'youngestScorer',
        );

        $this->venues = array(
            NULL,
            'h',
            'a',
            'n'
        );
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int|NULL $season     The stats to insert for a specific season, or NULL to insert all of them
     * @return NULL
     */
    public function insertEntries($season = NULL)
    {
        $this->insertEntry();

        if (is_null($season)) {
            $i = $this->ci->Season_model->fetchEarliestYear();
            while($i <= Season_model::fetchCurrentSeason()){

                $this->insertEntry(NULL, $i);

                $i++;
            }
        } else {
            $this->insertEntry(NULL, $season);
        }

        foreach ($this->hungryMethodMap as $cacheData => $method) {
            $this->insertEntry(1, NULL, $cacheData);

            if (is_null($season)) {
                $i = $this->ci->Season_model->fetchEarliestYear();
                while($i <= Season_model::fetchCurrentSeason()){

                    $this->insertEntry(1, $i, $cacheData);

                    $i++;
                }
            } else {
                $this->insertEntry(1, $season, $cacheData);
            }
        }
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int|NULL $byType         Group by "type" or "overall"
     * @param  int|NULL $season         Season "career"
     * @param  string|NULL $cacheData   What specific data to cache
     * @return boolean                  Whether the row was inserted successfully
     */
    public function insertEntry($byType = NULL, $season = NULL, $cacheData = NULL)
    {
        if (!$this->entryExists($byType, $season, $cacheData)) {
            $data = array(
                'by_type' => $byType,
                'season' => $season,
                'cache_data' => $cacheData,
                'date_added' => time(),
                'date_updated' => time());

            return $this->db->insert($this->queueTableName, $data);
        }

        return false;
    }

    /**
     * Does an entry with the specified parameters already exist in the queue
     * @param  int|NULL $byType         Group by "type" or "overall"
     * @param  int|NULL $season         Season "career"
     * @param  string|NULL $cacheData   What specific data to cache
     * @return boolean                  Does the queue entry already exist?
     */
    public function entryExists($byType = NULL, $season = NULL, $cacheData = NULL)
    {
        $this->db->select('*')
            ->from($this->queueTableName)
            ->where('by_type', $byType)
            ->where('season', $season)
            ->where('cache_data', $cacheData)
            ->where('in_progress', 0)
            ->where('completed', 0)
            ->where('deleted', 0);

        $result = $this->db->get()->result();

        if (count($result) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Update row in process queue table to be processed
     * @param  object $object   Existing row in table
     * @return boolean          Whether the row was updated successfully
     */
    public function updateEntry($object)
    {
        $object->date_updated = time();
        $this->db->where('id', $object->id);
        return $this->db->update($this->queueTableName, $object);
    }

    /**
     * Fetch latest rows to be processed/cached
     * @param  int     $limit   Number of rows to return
     * @return results          Query Object
     */
    public function fetchLatest($limit = 1)
    {
        $this->db->select('*')
            ->from($this->queueTableName)
            ->where('completed', 0)
            ->where('deleted', 0)
            ->order_by('date_added, id', 'asc')
            ->limit($limit, 0);

        return $this->db->get()->result();
    }

    /**
     * Process latest tasks in queue
     * @return int  Number of rows processed
     */
    public function processQueuedRows()
    {
        $rowCount = 0;
        $rows = $this->fetchLatest();

        foreach($rows as $row) {
            if ($row->in_progress == 1) {
                break;
            }

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
            } elseif (isset($this->hungryMethodMap[$row->cache_data])) {
                $method = $this->hungryMethodMap[$row->cache_data];
            } else {
                return false;
            }

            $competitionTypes = $this->ci->Season_model->fetchCompetitionTypes();

            foreach ($this->venues as $venue) {
                if (in_array($method, $this->hungryMethodMap) && !is_null($venue)) {
                    continue;
                }

                $this->$method(false, $row->season, $venue);

                if (!is_null($row->by_type)) { // Generate all statistics

                    foreach ($competitionTypes as $competitionType) {
                        $this->$method($competitionType, $row->season, $venue);
                    }
                }
            }

        } else {
            $this->generateStatisticsBySeason($row->season);
        }

        $row->in_progress = 0;
        $row->completed = 1;

        $finishUnixTime = time();
        $row->process_duration = $finishUnixTime - $startUnixTime;

        $row->peak_memory_usage = number_format(memory_get_peak_usage(true) / 1048576, 2);

        return $this->updateEntry($row);
    }

    /**
     * Insert Statistic into cache table
     * @param  string $statisticGroup   Unique identifier for statistic
     * @param  string|boolean $type     Competition Type - false, "league", "cup", etc
     * @param  int|boolean $season      Season relating to the statistic - false or integer
     * @param  string $statisticKey     Most Likely the most important value related to the statistic
     * @param  string $statisticValue   Most Likely a serialized object of all data related to the statistic
     * @return NULL
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
     * Generate and cache Biggest Win Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function biggestWin($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'biggest_win';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, (m.h - m.a) as difference
FROM view_competitive_matches m
WHERE (m.h - m.a) = (
    SELECT (m.h - m.a) as difference
    FROM view_competitive_matches m
    WHERE !ISNULL(m.h)
        AND m.h > m.a" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    ORDER BY difference DESC, m.h DESC
    LIMIT 1)
    AND m.h > m.a " . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->difference, serialize($row));
        }
    }

    /**
     * Generate and cache Biggest Loss Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function biggestLoss($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'biggest_loss';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, (m.a - m.h) as difference
FROM view_competitive_matches m
WHERE (m.a - m.h) = (
    SELECT (m.a - m.h) as difference
    FROM view_competitive_matches m
    WHERE !ISNULL(m.h)
        AND m.h < m.a" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    ORDER BY difference DESC, m.h DESC
    LIMIT 1)
    AND m.h < m.a " . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->difference, serialize($row));
        }
    }

    /**
     * Generate and cache Highest Scoring Draw Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function highestScoringDraw($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'highest_scoring_draw';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, (m.a + m.h) as total_goals
FROM view_competitive_matches m
WHERE (m.a + m.h) = (
    SELECT (m.a + m.h) as total_goals
    FROM view_competitive_matches m
    WHERE !ISNULL(m.h)
        AND m.h = m.a" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    ORDER BY total_goals DESC, m.h DESC
    LIMIT 1)
    AND !ISNULL(m.h)
    AND m.h = m.a " . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->total_goals, serialize($row));
        }
    }

    /**
     * Generate and cache Highest Scoring Match Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function highestScoringMatch($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'highest_scoring_match';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, (m.a + m.h) as total_goals
FROM view_competitive_matches m
WHERE (m.a + m.h) = (
    SELECT (m.a + m.h) as total_goals
    FROM view_competitive_matches m
    WHERE !ISNULL(m.h)" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    ORDER BY total_goals DESC, m.h DESC
    LIMIT 1)
    AND !ISNULL(m.h)" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->total_goals, serialize($row));
        }
    }

    /**
     * Generate and cache Longest Winning Sequence Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestWinningSequence($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_winning_sequence';

        $this->sequenceBase("\$match->h > \$match->a", $statisticGroup, $type, $season, $venue);
    }

    /**
     * Generate and cache Longest Losing Sequence Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestLosingSequence($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_losing_sequence';

        $this->sequenceBase("\$match->h < \$match->a", $statisticGroup, $type, $season, $venue);
    }

    /**
     * Generate and cache Longest Drawing Sequence Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestDrawingSequence($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_drawing_sequence';

        $this->sequenceBase("\$match->h == \$match->a", $statisticGroup, $type, $season, $venue);
    }

    /**
     * Generate and cache Longest Unbeaten Sequence Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestUnbeatenSequence($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_unbeaten_sequence';

        $this->sequenceBase("\$match->h == \$match->a || \$match->h > \$match->a", $statisticGroup, $type, $season, $venue);
    }

    /**
     * Generate and cache Longest Sequence without Win Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestSequenceWithoutWin($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_sequence_without_win';

        $this->sequenceBase("\$match->h < \$match->a || \$match->h == \$match->a", $statisticGroup, $type, $season, $venue);
    }

    /**
     * Generate and cache Longest Clean Sheet Sequence Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestCleanSheetSequence($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_clean_sheet_sequence';

        $this->sequenceBase("\$match->a == 0", $statisticGroup, $type, $season, $venue);
    }

    /**
     * Generate and cache Longest Sequence Without Clean Sheet Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestSequenceWithoutCleanSheet($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_sequence_without_clean_sheet';

        $this->sequenceBase("\$match->a > 0", $statisticGroup, $type, $season, $venue);
    }

    /**
     * Generate and cache Longest Scoring Sequence Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestScoringSequence($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_scoring_sequence';

        $this->sequenceBase("\$match->h > 0", $statisticGroup, $type, $season, $venue);
    }

    /**
     * Generate and cache Longest Sequence Without Scoring Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestSequenceWithoutScoring($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'longest_sequence_without_scoring';

        $this->sequenceBase("\$match->h == 0", 'longest_sequence_without_scoring', $type, $season, $venue);
    }

    /**
     * Generate and cache Base Method Statistics by competition type, season or venue
     * @param  string $comparisonCode  PHP Code that qualifies the match to be included in the sequence
     * @param  string $statisticGroup   Unique identifier for statistic
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function sequenceBase($comparisonCode, $statisticGroup, $type = false, $season = NULL, $venue = NULL)
    {
        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $matches = $this->ci->Season_model->fetchMatches($type, $season, $venue);

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
                eval("\$comparisonResult = (" . $comparisonCode . ") && !is_null(\$match->h) && !is_null(\$match->a);");

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
     * Generate and cache Quickest Goal Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function quickestGoal($type = false, $season = NULL)
    {
        $statisticGroup = 'quickest_goal';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, g.match_id, g.scorer_id, g.assist_id, g.minute, p.first_name, p.surname
FROM goal g
LEFT JOIN view_competitive_matches m ON g.match_id = m.id
LEFT JOIN player p ON p.id = g.scorer_id
WHERE g.minute = (
    SELECT g.minute as goal_minute
    FROM goal g
    LEFT JOIN matches m ON g.match_id = m.id
    WHERE g.minute > 0 && g.minute <= 120" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    ORDER BY goal_minute ASC, m.date DESC
    LIMIT 1)" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->minute, serialize($row));
        }
    }

    /**
     * Generate and cache Oldest Appearance Holder Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function oldestAppearanceHolder($type = false, $season = NULL)
    {
        $statisticGroup = 'oldest_appearance_holder';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
LEFT JOIN player p ON p.id = m.player_id
WHERE m.age = (
    SELECT m.age
    FROM view_appearances_ages m
    LEFT JOIN competition c ON c.id = m.competition_id
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
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->age, serialize($row));
        }
    }

    /**
     * Generate and cache Youngest Appearance Holder Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function youngestAppearanceHolder($type = false, $season = NULL)
    {
        $statisticGroup = 'youngest_appearance_holder';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
LEFT JOIN player p ON p.id = m.player_id
WHERE m.age = (
    SELECT m.age
    FROM view_appearances_ages m
    LEFT JOIN competition c ON c.id = m.competition_id
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
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->age, serialize($row));
        }
    }

    /**
     * Generate and cache Oldest Debutant Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function oldestDebutant($type = false, $season = NULL)
    {
        $statisticGroup = 'oldest_debutant';

        $whereConditions = array();
        $whereConditions2 = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
            $whereConditions2[] = "(m2.competition_type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation,
    (SELECT COUNT(m2.id)
    FROM view_appearances_ages m2
    LEFT JOIN competition c ON c.id = m2.competition_id
    WHERE !ISNULL(m2.age)
        AND m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND c.deleted = 0
        AND m2.date < m.date
        AND m2.player_id = m.player_id) as game_number
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
WHERE !ISNULL(m.age)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
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
                $this->insertCache($statisticGroup, $type, $season, $row->age, serialize($row));
            }
        }
    }

    /**
     * Generate and cache Youngest Debutant Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function youngestDebutant($type = false, $season = NULL)
    {
        $statisticGroup = 'youngest_debutant';

        $whereConditions = array();
        $whereConditions2 = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
            $whereConditions2[] = "(m2.competition_type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation,
    (SELECT COUNT(m2.id)
    FROM view_appearances_ages m2
    LEFT JOIN competition c ON c.id = m2.competition_id
    WHERE !ISNULL(m2.age)
        AND m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND c.deleted = 0
        AND m2.date < m.date
        AND m2.player_id = m.player_id) as game_number
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
WHERE !ISNULL(m.age)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
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
                $this->insertCache($statisticGroup, $type, $season, $row->age, serialize($row));
            }
        }
    }

    /**
     * Generate and cache Oldest Scorer Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function oldestScorer($type = false, $season = NULL)
    {
        $statisticGroup = 'oldest_scorer';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM view_scorers_ages m
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
LEFT JOIN player p ON p.id = m.scorer_id
WHERE m.age = (
    SELECT m.age
    FROM view_scorers_ages m
    LEFT JOIN competition c ON c.id = m.competition_id
    WHERE !ISNULL(m.age)
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND c.deleted = 0
    ORDER BY m.age DESC, m.date DESC
    LIMIT 1)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
ORDER BY m.date DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->age, serialize($row));
        }
    }

    /**
     * Generate and cache Youngest Scorer Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function youngestScorer($type = false, $season = NULL)
    {
        $statisticGroup = 'youngest_scorer';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation, p.first_name, p.surname
FROM view_scorers_ages m
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
LEFT JOIN player p ON p.id = m.scorer_id
WHERE m.age = (
    SELECT m.age
    FROM view_scorers_ages m
    LEFT JOIN competition c ON c.id = m.competition_id
    WHERE !ISNULL(m.age)
        AND c.competitive = 1" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
        AND c.deleted = 0
    ORDER BY m.age ASC, m.date DESC
    LIMIT 1)
    AND c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
ORDER BY m.date ASC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->age, serialize($row));
        }
    }

    /**
     * Generate and cache Clean Sheets In A Season Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function cleanSheetsInASeason($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'clean_sheets_in_a_season';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT COUNT(m.id) as games
FROM view_competitive_matches m
LEFT JOIN competition c ON c.id = m.competition_id
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.a = 0";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->games, '');
        }
    }

    /**
     * Generate and cache Failed to Score In A Season Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function failToScoreInASeason($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'fail_to_score_in_a_season';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT COUNT(m.id) as games
FROM view_competitive_matches m
LEFT JOIN competition c ON c.id = m.competition_id
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.h = 0";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->games, '');
        }
    }

    /**
     * Generate and cache Highest Attendance Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function highestAttendance($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'highest_attendance';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*
FROM view_competitive_matches m
WHERE (m.attendance) = (
    SELECT (m.attendance) as attendance
    FROM view_competitive_matches m
    WHERE !ISNULL(m.attendance)" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    ORDER BY m.attendance DESC
    LIMIT 1)" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.attendance DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->attendance, serialize($row));
        }
    }

    /**
     * Generate and cache Lowest Attendance Statistics by competition type, season or venue
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function lowestAttendance($type = false, $season = NULL, $venue = NULL)
    {
        $statisticGroup = 'lowest_attendance';

        $whereConditions = array();

        if (is_string($type)) {
            $whereConditions[] = "(m.type = '{$type}')";
        }

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
            $whereConditions[] = "(m.venue = '{$venue}')";
        }

        $this->deleteRows($statisticGroup, $type, $season);

        $sql = "SELECT m.*
FROM view_competitive_matches m
WHERE (m.attendance) = (
    SELECT (m.attendance) as attendance
    FROM view_competitive_matches m
    WHERE !ISNULL(m.attendance)" . (count($whereConditions) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    ORDER BY m.attendance ASC
    LIMIT 1)" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.attendance ASC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache($statisticGroup, $type, $season, $row->attendance, serialize($row));
        }
    }

    /**
     * Generate all statistics for a particular season
     * @param  $season      Season of stats to generate
     * @return boolean      Whether query was executed correctly
     */
    public function generateStatisticsBySeason($season)
    {
        $competitionTypes = $this->ci->Season_model->fetchCompetitionTypes();

        foreach ($this->venues as $venue) {
            foreach ($this->methodMap as $method) {
                $this->$method(false, $season, $venue);

                foreach ($competitionTypes as $competitionType) {
                    $this->$method($competitionType, $season, $venue);
                }
            }
        }

        return true;
    }

    /**
     * Generate all statistics
     * @return boolean      Whether query was executed correctly
     */
    public function generateAllStatistics()
    {
        $competitionTypes = $this->ci->Season_model->fetchCompetitionTypes();

        foreach ($this->venues as $venue) {
            foreach ($this->methodMap as $method) {
                $this->$method(false, NULL, $venue);

                foreach ($competitionTypes as $competitionType) {
                    $this->$method($competitionType, NULL, $venue);
                }
            }

            $season = $this->ci->Season_model->fetchEarliestYear();
            while($season <= Season_model::fetchCurrentSeason()){

                foreach ($this->methodMap as $method) {
                    $this->$method(false, $season, $venue);

                    foreach ($competitionTypes as $competitionType) {
                        $this->$method($competitionType, $season, $venue);
                    }
                }

                $season++;
            }
        }

        return true;
    }

    /**
     * Particular Club Statistics, based on Season and/or Competition Type
     * @param  int $statisticGroup     Statistic Group
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @return boolean                 Whether query was executed correctly
     */
    public function deleteRows($statisticGroup, $type = false, $season = NULL)
    {
        $whereConditions = array();

        $whereConditions['statistic_group'] = $statisticGroup;
        $whereConditions['type']            = $type ? $type : 'overall';
        $whereConditions['season']          = $season ? $season : 'career';

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