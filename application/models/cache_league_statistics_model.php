<?php
class Cache_League_Statistics_model extends CI_Model {

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
        $this->ci->load->model('League_model');
        $this->ci->load->model('Season_model');

        $this->tableName = 'cache_league_statistics';
        $this->queueTableName = 'cache_queue_league_statistics';

        $this->methodMap = array(
            'biggest_win'                          => 'biggestWin',
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
            'clean_sheets_in_a_season'             => 'cleanSheetsInASeason',
            'fail_to_score_in_a_season'            => 'failToScoreInASeason',
        );

        $this->hungryMethodMap = array(
        );

        $this->venues = array(
            NULL,
            'h',
            'a',
        );
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int $leagueId     Unique League ID
     * @return boolean
     */
    public function insertEntries($leagueId = NULL)
    {
        if (is_null($leagueId)) {
            foreach($this->ci->League_model->fetchAllLeagues() as $league) {
                $this->insertEntry($league->id);
            }
        } else {
            $this->insertEntry($leagueId);
        }

        foreach ($this->hungryMethodMap as $cacheData => $method) {
            if (is_null($leagueId)) {
                foreach($this->ci->League_model->fetchAllLeagues() as $league) {
                    $this->insertEntry($league->id, $cacheData);
                }
            } else {
                $this->insertEntry($leagueId, $cacheData);
            }
        }
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int $leagueId            Unique Leadue ID
     * @param  string|NULL $cacheData   What specific data to cache
     * @return boolean
     */
    public function insertEntry($leagueId, $cacheData = NULL)
    {
        if (!$this->entryExists($leagueId)) {
            $data = array(
                'league_id' => $leagueId,
                'cache_data' => $cacheData,
                'date_added' => time(),
                'date_updated' => time());

            return $this->db->insert($this->queueTableName, $data);
        }

        return false;
    }

    /**
     * Does an entry with the specified parameters already exist in the queue
     * @param  int $leagueId            League ID
     * @param  string|NULL $cacheData   What specific data to cache
     * @return boolean                  Does the queue entry already exist?
     */
    public function entryExists($leagueId, $cacheData = NULL)
    {
        $this->db->select('*')
            ->from($this->queueTableName)
            ->where('league_id', $leagueId)
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
     * @param  int     $limit  Number of rows to return
     * @return results Query   Object
     */
    public function fetchLatest($limit = 1)
    {
        $this->db->select('*')
            ->from($this->queueTableName)
            ->where('in_progress', 0)
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

            foreach ($this->venues as $venue) {
                $this->$method($row->league_id, $venue);
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
     * @param  string $statisticGroup   Unique identifier for statistic
     * @param  int $leagueId            League ID
     * @param  string $statisticKey     Most Likely the most important value related to the statistic
     * @param  string $statisticValue   Most Likely a serialized object of all data related to the statistic
     * @return NULL
     */
    public function insertCache($statisticGroup, $leagueId, $statisticKey, $statisticValue)
    {
        $object = new stdClass();

        $object->league_id = $leagueId;
        $object->statistic_group = $statisticGroup;
        $object->statistic_key = $statisticKey;
        $object->statistic_value = $statisticValue;

        $this->db->insert($this->tableName, $object);
    }

    /**
     * Generate and cache Biggest Win Statistics by season or type
     * @return boolean              Whether query was executed correctly
     */
    public function matchData($match, $clubId)
    {
        if ($match->h_opposition_id == $clubId) { // Specified Club is at home
            $match->clubId = $match->h_opposition_id;
            $match->clubScore = $match->h_score;
            $match->oppositionId = $match->a_opposition_id;
            $match->oppositionScore = $match->a_score;
        } elseif ($match->a_opposition_id == $clubId) { // Specified Club is away
            $match->clubId = $match->a_opposition_id;
            $match->clubScore = $match->a_score;
            $match->oppositionId = $match->h_opposition_id;
            $match->oppositionScore = $match->h_score;
        }

        return $match;
    }

    /**
     * Generate and cache Biggest Win Statistics by season or type
     * @return boolean              Whether query was executed correctly
     */
    public function matchesByClub($leagueId, $venue = NULL)
    {
        $clubs = $this->ci->League_model->fetchClubRegistrations($leagueId);

        $relatedMatches = array();

        foreach ($clubs as $club) {
            $matches = $this->ci->League_model->fetchMatches($leagueId, $club->opposition_id);

            foreach ($matches as $match) {
                if (!is_null($match->h_score) && !is_null($match->h_score) && is_null($match->status)) {
                    switch($venue) {
                        case 'h':
                            if ($club->opposition_id == $match->h_opposition_id) {
                                $relatedMatches[$club->opposition_id][] = $this->matchData($match, $club->opposition_id);
                            }
                            break;
                        case 'a':
                            if ($club->opposition_id == $match->a_opposition_id) {
                                $relatedMatches[$club->opposition_id][] = $this->matchData($match, $club->opposition_id);
                            }
                            break;
                        default:
                            $relatedMatches[$club->opposition_id][] = $this->matchData($match, $club->opposition_id);
                    }
                }
            }
        }

        return $relatedMatches;
    }

    /**
     * Generate and cache Base Method Statistics by competition type, season or venue
     * @param  array $matches          List of matches to compare
     * @param  string $comparisonCode  PHP Code that qualifies the match to be included in the sequence
     * @return array                   List of records
     */
    public function sequenceBase($matches, $comparisonCode)
    {
        $records = array();

        $record = new stdClass();
        $record->clubId = NULL;
        $record->sequence = 0;
        $record->sequenceStart = '';
        $record->sequenceFinish = '';

        if (count($matches) > 0) {
            $highestSequence = 0;
            $currentSequence = 0;
            $currentSequenceStart = '';
            $currentSequenceFinish = '';

            foreach ($matches as $match) {
                eval("\$comparisonResult = (" . $comparisonCode . ");");

                if ($comparisonResult) {
                    $currentSequence++;

                    if ($currentSequenceStart == '') {
                        $currentSequenceStart = $match->date;
                    }

                    $currentSequenceFinish = $match->date;
                } else {
                    $record = new stdClass();
                    $record->clubId = $match->clubId;

                    if ($currentSequence > $highestSequence) {
                        $highestSequence = $currentSequence;

                        $record->sequence = $currentSequence;
                        $record->sequenceStart = $currentSequenceStart;
                        $record->sequenceFinish = $currentSequenceFinish;

                        $records = array();
                        $records[uniqid()] = $record;
                    } elseif ($currentSequence == $highestSequence && $currentSequence > 0) {
                        $record->sequence = $currentSequence;
                        $record->sequenceStart = $currentSequenceStart;
                        $record->sequenceFinish = $currentSequenceFinish;

                        $records[uniqid()] = $record;
                    }

                    $currentSequence = 0;
                    $currentSequenceStart = '';
                    $currentSequenceFinish = '';
                }
            }

            $record = new stdClass();
            $record->clubId = $match->clubId;
            if ($currentSequence > $highestSequence) {
                $highestSequence = $currentSequence;

                $record->sequence = $currentSequence;
                $record->sequenceStart = $currentSequenceStart;
                $record->sequenceFinish = $currentSequenceFinish;

                $records = array();
                $records[uniqid()] = $record;
            } elseif ($currentSequence == $highestSequence && $currentSequence > 0) {
                $record->sequence = $currentSequence;
                $record->sequenceStart = $currentSequenceStart;
                $record->sequenceFinish = $currentSequenceFinish;

                $records[uniqid()] = $record;
            }
        }

        return $records;
    }

    /**
     * Generate and cache Sequence Statistics for the specified league
     * @param  int $statisticGroup     Statistic Group
     * @param  int $leagueId           Unique League Id
     * @param  string $comparisonCode  PHP Code that qualifies the match to be included in the sequence
     * @param  string|NULL $venue      Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function generateSequence($statisticGroup, $leagueId, $comparisonCode, $venue = NULL)
    {
        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
        }

        $this->deleteRows($statisticGroup, $leagueId);

        $clubMatches = $this->matchesByClub($leagueId, $venue);

        $records = array();

        foreach ($clubMatches as $clubId => $matches) {
            $records = array_merge($records, $this->sequenceBase($matches, $comparisonCode));
        }

        usort($records, array(
            'Cache_League_Statistics_model',
            'sortSequenceRecords'));

        $recordSequence = 0;
        if (count($records) > 0) {
            $firstRecord = reset($records);
            $recordSequence = $firstRecord->sequence;
        }

        if (count($records) > 0) {
            foreach ($records as $record) {
                if ($recordSequence == $record->sequence) {
                    $this->insertCache($statisticGroup, $leagueId, $recordSequence, serialize($record));
                }
            }
        }
    }

    /**
     * Generate and cache Biggest Win Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return boolean                Whether query was executed correctly
     */
    public function biggestWin($leagueId, $venue = NULL)
    {
        $statisticGroup = 'biggest_win';

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
        }

        $this->deleteRows($statisticGroup, $leagueId);

        $clubMatches = $this->matchesByClub($leagueId, $venue);

        $records = array();

        foreach ($clubMatches as $clubId => $matches) {
            foreach ($matches as $match) {
                if ($match->clubScore > $match->oppositionScore) {
                    $scoreDifference = $match->clubScore - $match->oppositionScore;

                    $records[$scoreDifference][$match->id] = $match;
                }
            }
        }

        krsort($records);

        $difference = key($records);

        if (count($records) > 0) {
            foreach (reset($records) as $record) {
                $this->insertCache($statisticGroup, $leagueId, $difference, serialize($record));
            }
        }
    }

    /**
     * Generate and cache Highest Scoring Draw Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return boolean                Whether query was executed correctly
     */
    public function highestScoringDraw($leagueId, $venue = NULL)
    {
        $statisticGroup = 'highest_scoring_draw';

        if (!is_null($venue)) {
            return false;
        }

        $this->deleteRows($statisticGroup, $leagueId);

        $clubMatches = $this->matchesByClub($leagueId, $venue);

        $records = array();

        foreach ($clubMatches as $clubId => $matches) {
            foreach ($matches as $match) {
                if ($match->clubScore == $match->oppositionScore) {
                    $aggregateScore = $match->clubScore + $match->oppositionScore;

                    $records[$aggregateScore][$match->id] = $match;
                }
            }
        }

        krsort($records);

        $aggregateScore = key($records);

        if (count($records) > 0) {
            foreach (reset($records) as $record) {
                $this->insertCache($statisticGroup, $leagueId, $aggregateScore, serialize($record));
            }
        }
    }

    /**
     * Generate and cache Highest Scoring Match Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return boolean                Whether query was executed correctly
     */
    public function highestScoringMatch($leagueId, $venue = NULL)
    {
        $statisticGroup = 'highest_scoring_match';

        if (!is_null($venue)) {
            return false;
        }

        $this->deleteRows($statisticGroup, $leagueId);

        $clubMatches = $this->matchesByClub($leagueId, $venue);

        $records = array();

        foreach ($clubMatches as $clubId => $matches) {
            foreach ($matches as $match) {
                $aggregateScore = $match->clubScore + $match->oppositionScore;

                $records[$aggregateScore][$match->id] = $match;
            }
        }

        krsort($records);

        $aggregateScore = key($records);

        if (count($records) > 0) {
            foreach (reset($records) as $record) {
                $this->insertCache($statisticGroup, $leagueId, $aggregateScore, serialize($record));
            }
        }
    }

    /**
     * Generate and cache Longest Winning Sequence Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestWinningSequence($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_winning_sequence';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->clubScore > \$match->oppositionScore", $venue);
    }

    /**
     * Generate and cache Longest Losing Sequence Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestLosingSequence($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_losing_sequence';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->clubScore < \$match->oppositionScore", $venue);
    }

    /**
     * Generate and cache Longest Unbeaten Sequence Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestDrawingSequence($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_drawing_sequence';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->clubScore == \$match->oppositionScore", $venue);
    }

    /**
     * Generate and cache Longest Drawing Sequence Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestUnbeatenSequence($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_unbeaten_sequence';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->clubScore > \$match->oppositionScore || \$match->clubScore == \$match->oppositionScore", $venue);
    }

    /**
     * Generate and cache Longest Sequence Without Win Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestSequenceWithoutWin($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_sequence_without_win';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->clubScore < \$match->oppositionScore || \$match->clubScore == \$match->oppositionScore", $venue);
    }

    /**
     * Generate and cache Longest Sequence of Clean Sheets Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestCleanSheetSequence($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_clean_sheet_sequence';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->oppositionScore == 0", $venue);
    }

    /**
     * Generate and cache Longest Sequence Without Clean Sheet Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestSequenceWithoutCleanSheet($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_sequence_without_clean_sheet';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->oppositionScore > 0", $venue);
    }

    /**
     * Generate and cache Longest Scoring Sequence Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestScoringSequence($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_scoring_sequence';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->clubScore > 0", $venue);
    }

    /**
     * Generate and cache Longest Sequence Without Scoring Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return NULL
     */
    public function longestSequenceWithoutScoring($leagueId, $venue = NULL)
    {
        $statisticGroup = 'longest_sequence_without_scoring';

        $this->generateSequence($statisticGroup, $leagueId, "\$match->clubScore == 0", $venue);
    }

    /**
     * Generate and cache Clean Sheets in a Season Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return boolean                Whether query was executed correctly
     */
    public function cleanSheetsInASeason($leagueId, $venue = NULL)
    {
        $statisticGroup = 'clean_sheets_in_a_season';

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
        }

        $this->deleteRows($statisticGroup, $leagueId);

        $clubMatches = $this->matchesByClub($leagueId, $venue);

        $records = array();

        foreach ($clubMatches as $clubId => $matches) {
            $cleanSheets = 0;
            foreach ($matches as $match) {
                if ($match->oppositionScore == 0) {
                    $cleanSheets++;
                }
            }

            $records[$cleanSheets][$clubId] = $clubId;
        }

        krsort($records);

        $cleanSheets = key($records);

        if (count($records) > 0) {
            foreach (reset($records) as $record) {
                $this->insertCache($statisticGroup, $leagueId, $cleanSheets, serialize($record));
            }
        }
    }

    /**
     * Generate and cache Failed to Score in a Season Statistics for the specified league
     * @param  int $leagueId          League ID
     * @param  string|NULL $venue     Whether to include all, home, away or neutral venues
     * @return boolean                Whether query was executed correctly
     */
    public function failToScoreInASeason($leagueId, $venue = NULL)
    {
        $statisticGroup = 'fail_to_score_in_a_season';

        if (!is_null($venue)) {
            $statisticGroup .= "_{$venue}";
        }

        $this->deleteRows($statisticGroup, $leagueId);

        $clubMatches = $this->matchesByClub($leagueId, $venue);

        $records = array();

        foreach ($clubMatches as $clubId => $matches) {
            $failedToScore = 0;
            foreach ($matches as $match) {
                if ($match->clubScore == 0) {
                    $failedToScore++;
                }
            }

            $records[$failedToScore][$clubId] = $clubId;
        }

        krsort($records);

        $failedToScore = key($records);

        if (count($records) > 0) {
            foreach (reset($records) as $record) {
                $this->insertCache($statisticGroup, $leagueId, $failedToScore, serialize($record));
            }
        }
    }

    /**
     * Comparison method to sort records into correct order
     * @param  object $a              First Comparison Object
     * @param  object $b              Second Comparison Object
     * @return int                    Result of comparison
     */
    public static function sortSequenceRecords($a, $b)
    {
        if ($a->sequence == $b->sequence) {
            return 0;
        }
        return ($a->sequence > $b->sequence) ? -1 : 1;
    }

    /**
     * Generate all statistics
     * @return boolean      Whether query was executed correctly
     */
    public function generateAllStatistics()
    {
        $leagues = $this->ci->League_model->fetchAllLeagues();

        foreach ($leagues as $league) {
            foreach ($this->venues as $venue) {
                foreach ($this->methodMap as $method) {
                    $this->$method($league->id, $venue);
                }
            }
        }

        return true;
    }

    /**
     * Particular League Statistics, based on Season and/or Competition Type
     * @param  int $statisticGroup  Statistic Group
     * @param  int $leagueId        Unique League Id
     * @return boolean              Whether query was executed correctly
     */
    public function deleteRows($statisticGroup, $leagueId)
    {
        $whereConditions = array();

        $whereConditions['statistic_group'] = $statisticGroup;
        $whereConditions['league_id']       = $leagueId;

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