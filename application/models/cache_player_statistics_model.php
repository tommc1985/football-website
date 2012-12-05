<?php
class Cache_Player_Statistics_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public $tableName;
    public $queueTableName;

    public $methodMap;
    public $hungryMethodMap;
    public $otherMethodMap;

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

        $this->tableName = 'cache_player_statistics';
        $this->queueTableName = 'cache_queue_player_statistics';

        $this->methodMap = array(
        );

        $this->hungryMethodMap = array(
            'hattricks'                            => 'hattricks',
            'real_points_gained'                   => 'realPointsGained',
            'average_points_gained'                => 'averagePointsGained',
            'real_points'                          => 'realPoints',
            'average_points'                       => 'averagePoints',
            'real_goals_gained'                    => 'realGoalsGained',
            'average_goals_gained'                 => 'averageGoalsGained',
            'real_goals'                           => 'realGoals',
            'average_goals_for'                    => 'averageGoalsFor',
            'average_goals_against'                => 'averageGoalsAgainst',
            'total_clean_sheets'                   => 'totalCleanSheets',
            'average_clean_sheets'                 => 'averageCleanSheets',
            'consecutive_games_scored'             => 'consecutiveGamesScored',
            'consecutive_games_assisted'           => 'consecutiveGamesAssisted',
            'most_common_two_player_combination'   => 'mostCommonTwoPlayerCombination',
            'most_common_centre_back_pairing'      => 'mostCommonCentreBackPairing',
            'most_common_centre_midfield_pairing'  => 'mostCommonCentreMidfieldPairing',
            'most_common_right_hand_side_pairing'  => 'mostCommonRightHandSidePairing',
            'most_common_left_hand_side_pairing'   => 'mostCommonLeftHandSidePairing',
            'most_common_strike_partner'           => 'mostCommonStrikePartner',
        );

        $this->otherMethodMap = array(
            'debut'                                => 'debut',
            'first_goal'                           => 'firstGoal',
            'scored_on_debut'                      => 'scoredOnDebut',
            'debut_and_first_goal_time_difference' => 'debutAndFirstGoalTimeDifference',
            'debut_and_first_goal_game_difference' => 'debutAndFirstGoalGameDifference',
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
        $this->insertEntry(NULL, NULL, 'debut');
        $this->insertEntry(NULL, NULL, 'first_goal');
        $this->insertEntry(NULL, NULL, 'scored_on_debut');
        $this->insertEntry(NULL, NULL, 'debut_and_first_goal_time_difference');
        $this->insertEntry(NULL, NULL, 'debut_and_first_goal_game_difference');

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
        $data = array(
            'by_type' => $byType,
            'season' => $season,
            'cache_data' => $cacheData,
            'date_added' => time(),
            'date_updated' => time());

        return $this->db->insert($this->queueTableName, $data);
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
        $rows = $this->fetchLatest();

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
            } elseif (isset($this->hungryMethodMap[$row->cache_data])) {
                $method = $this->hungryMethodMap[$row->cache_data];
            } elseif (isset($this->otherMethodMap[$row->cache_data])) {
                $method = $this->otherMethodMap[$row->cache_data];
            } else {
                return false;
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
     * Insert Statistic into cache table
     * Insert Statistic into cache table
     * @param  string $statisticGroup   Unique identifier for statistic
     * @param  string|boolean $type     Competition Type - false, "league", "cup", etc
     * @param  int|boolean $season      Season relating to the statistic - false or integer
     * @param  int $playerId            Player ID
     * @param  string $statisticKey     Most Likely the most important value related to the statistic
     * @param  string $statisticValue   Most Likely a serialized object of all data related to the statistic
     * @return NULL
     */
    public function insertCache($statisticGroup, $type, $season, $playerId, $statisticKey, $statisticValue)
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

        $object->player_id = $playerId;
        $object->statistic_group = $statisticGroup;
        $object->statistic_key = $statisticKey;
        $object->statistic_value = $statisticValue;

        $this->db->insert($this->tableName, $object);
    }

    /**
     * Fetch distinct player ids
     * @return results          Query Object
     */
    public function fetchDistinctPlayerIds()
    {
        $this->db->select('DISTINCT(id) as id')
            ->from('player')
            ->where('deleted', 0)
            ->order_by('id', 'asc');

        return $this->db->get()->result();
    }

    /**
     * Generate and cache Debut by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function debut($type = false, $season = NULL)
    {
        self::deleteDebut($type, $season);

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
    WHERE m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND c.deleted = 0
        AND m2.date < m.date
        AND m2.player_id = m.player_id) as game_number
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
HAVING game_number = 0
ORDER BY m.date ASC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('debut', $type, $season, $row->player_id, $row->match_id, serialize($row));
        }
    }

    /**
     * Generate and cache First Goal by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function firstGoal($type = false, $season = NULL)
    {
        self::deleteFirstGoal($type, $season);

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
    FROM view_match_goals m2
    LEFT JOIN competition c ON c.id = m2.competition
    WHERE m.status != 'unused'
        AND m2.goals > 0
        AND c.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND c.deleted = 0
        AND m2.date < m.date
        AND m2.player_id = m.player_id) as game_number
FROM view_match_goals m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.goals > 0
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
HAVING game_number = 0
ORDER BY m.date ASC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('first_goal', $type, $season, $row->player_id, $row->match_id, serialize($row));
        }
    }

    /**
     * Generate and cache Scored on Debut by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function scoredOnDebut($type = false, $season = NULL)
    {
        self::deleteScoredOnDebut($type, $season);

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
    FROM view_appearances_matches_combined m2
    LEFT JOIN competition c ON c.id = m2.competition
    WHERE m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND m.goals > 0
        AND c.deleted = 0
        AND m2.date < m.date
        AND m2.player_id = m.player_id) as game_number
FROM view_appearances_matches_combined m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.goals > 0
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
HAVING game_number = 0
ORDER BY m.date ASC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('scored_on_debut', $type, $season, $row->player_id, $row->match_id, serialize($row));
        }
    }

    /**
     * Generate and cache Hattricks by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function hattricks($type = false, $season = NULL)
    {
        self::deleteHattricks($type, $season);

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

        $sql = "SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation
FROM view_match_goals m
LEFT JOIN opposition o ON o.id = m.opposition
LEFT JOIN competition c ON c.id = m.competition
LEFT JOIN competition_stage cs ON cs.id = m.stage
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.goals >= 3
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.stage))
ORDER BY m.goals DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('hattricks', $type, $season, $row->player_id, $row->goals, serialize($row));
        }
    }

    /**
     * Generate and cache Debut & First Goal Time Difference Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function debutAndFirstGoalTimeDifference($type = false, $season = NULL)
    {
        self::deleteDebutAndFirstGoalTimeDifference($type, $season);

        $whereConditions = array();

        $sql = "SELECT cps1.player_id, cps1.statistic_key, cps2.statistic_key, m1.date, m2.date, to_days( m2.date) - to_days( m1.date) as days_elapsed, p.first_name, p.surname
FROM cache_player_statistics cps1
LEFT OUTER JOIN cache_player_statistics cps2 ON `cps1`.`player_id` = `cps2`.`player_id`
LEFT JOIN view_competitive_matches m1 ON m1.id = cps1.statistic_key
LEFT JOIN view_competitive_matches m2 ON m2.id = cps2.statistic_key
LEFT JOIN player p ON p.id = cps1.player_id
WHERE (cps1.statistic_group = 'debut'
    AND cps2.statistic_group = 'first_goal')" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND cps1.type = cps2.type
    AND cps1.season = cps2.season
    AND p.deleted = 0
ORDER BY days_elapsed DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('debut_and_first_goal_time_difference', $type, $season, $row->player_id, $row->days_elapsed, serialize($row));
        }
    }

    /**
     * Generate and cache Debut & First Goal Game Difference Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function debutAndFirstGoalGameDifference($type = false, $season = NULL)
    {
        self::deleteDebutAndFirstGoalGameDifference($type, $season);

        $sql = "SELECT * FROM (
    SELECT m.*, o.name as opposition_name, c.name as competition_name, c.short_name as competition_short_name, c.abbreviation as competition_abbreviation, cs.name as competition_stage_name, cs.abbreviation as competition_stage_abbreviation,
        (SELECT COUNT(m2.id)
        FROM view_match_goals m2
        LEFT JOIN competition c ON c.id = m2.competition
        WHERE m.status != 'unused'
            AND c.competitive = 1
            AND c.deleted = 0
            AND m2.date < m.date
            AND m2.player_id = m.player_id) as games_elapsed,
          (SELECT COUNT(m3.id)
        FROM view_match_goals m3
        LEFT JOIN competition c ON c.id = m3.competition
        WHERE m3.goals > 0
            AND c.competitive = 1
            AND c.deleted = 0
            AND m3.date <= m.date
            AND m3.player_id = m.player_id) as goal_number
    FROM view_match_goals m
    LEFT JOIN opposition o ON o.id = m.opposition
    LEFT JOIN competition c ON c.id = m.competition
    LEFT JOIN competition_stage cs ON cs.id = m.stage
    WHERE c.competitive = 1
        AND m.status != 'unused'
        AND o.deleted = 0
        AND c.deleted = 0
        AND (cs.deleted = 0 || ISNULL(m.stage))
    HAVING goal_number = 1
    ORDER BY m.date ASC) as accumulated_data
GROUP BY player_id
ORDER BY player_id ASC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('debut_and_first_goal_game_difference', $type, $season, $row->player_id, $row->games_elapsed, serialize($row));
        }
    }

    /**
     * Generate and cache Real Points Gained Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function realPointsGained($type = false, $season = NULL)
    {
        self::deleteRealPointsGained($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, SUM(m.points) - SUM(m.adjusted_points) as points_gained, COUNT(m.id) as matches_played
FROM view_match_affected_results m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY points_gained DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('real_points_gained', $type, $season, $row->player_id, $row->points_gained, serialize($row));
        }
    }

    /**
     * Generate and cache Average Points Gained Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function averagePointsGained($type = false, $season = NULL)
    {
        self::deleteAveragePointsGained($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, AVG(m.points) - AVG(m.adjusted_points) as points_gained, COUNT(m.id) as matches
FROM view_match_affected_results m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY points_gained DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('average_points_gained', $type, $season, $row->player_id, $row->points_gained, serialize($row));
        }
    }

    /**
     * Generate and cache Real Points Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function realPoints($type = false, $season = NULL)
    {
        self::deleteRealPoints($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, SUM(m.points) as points, COUNT(m.id) as matches_played
FROM view_match_affected_results m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY points DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('real_points', $type, $season, $row->player_id, $row->points, serialize($row));
        }
    }

    /**
     * Generate and cache Average Points Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function averagePoints($type = false, $season = NULL)
    {
        self::deleteAveragePoints($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, AVG(m.points) as points, COUNT(m.id) as matches
FROM view_match_affected_results m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY points DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('average_points', $type, $season, $row->player_id, $row->points, serialize($row));
        }
    }

    /**
     * Generate and cache Real Goals Gained Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function realGoalsGained($type = false, $season = NULL)
    {
        self::deleteRealGoalsGained($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, SUM(m.h) - SUM(m.adjusted_h) as goals_gained, COUNT(m.id) as matches_played
FROM view_match_affected_results m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY goals_gained DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('real_goals_gained', $type, $season, $row->player_id, $row->goals_gained, serialize($row));
        }
    }

    /**
     * Generate and cache Average Goals Gained Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function averageGoalsGained($type = false, $season = NULL)
    {
        self::deleteAverageGoalsGained($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, AVG(m.h) - AVG(m.adjusted_h) as goals_gained, COUNT(m.id) as matches
FROM view_match_affected_results m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY goals_gained DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('average_goals_gained', $type, $season, $row->player_id, $row->goals_gained, serialize($row));
        }
    }

    /**
     * Generate and cache Real Goals Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function realGoals($type = false, $season = NULL)
    {
        self::deleteRealGoals($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, SUM(m.h) as goals, COUNT(m.id) as matches_played
FROM view_match_affected_results m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY goals DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('real_goals', $type, $season, $row->player_id, $row->goals, serialize($row));
        }
    }

    /**
     * Generate and cache Average Goals For Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function averageGoalsFor($type = false, $season = NULL)
    {
        self::deleteAverageGoalsFor($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, AVG(m.h) as goals, COUNT(m.id) as matches
FROM view_match_affected_results m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY goals DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('average_goals_for', $type, $season, $row->player_id, $row->goals, serialize($row));
        }
    }

    /**
     * Generate and cache Average Goals Against Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function averageGoalsAgainst($type = false, $season = NULL)
    {
        self::deleteAverageGoalsAgainst($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, AVG(m.a) as goals, COUNT(m.id) as matches
FROM view_appearances_matches m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY goals DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('average_goals_against', $type, $season, $row->player_id, $row->goals, serialize($row));
        }
    }

    /**
     * Generate and cache Total Clean Sheets Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function totalCleanSheets($type = false, $season = NULL)
    {
        self::deleteTotalCleanSheets($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, SUM(IF(m.a = 0, 1, 0)) as clean_sheets, COUNT(m.id) as matches
FROM view_appearances_matches m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY clean_sheets DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('total_clean_sheets', $type, $season, $row->player_id, $row->clean_sheets, serialize($row));
        }
    }

    /**
     * Generate and cache Average Clean Sheets Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function averageCleanSheets($type = false, $season = NULL)
    {
        self::deleteAverageCleanSheets($type, $season);

        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $sql = "SELECT m.player_id, p.first_name, p.surname, AVG(IF(m.a = 0, 1, 0)) as clean_sheets, COUNT(m.id) as matches
FROM view_appearances_matches m
LEFT JOIN player p ON p.id = m.player_id
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND p.deleted = 0
GROUP BY m.player_id
ORDER BY clean_sheets DESC";

        $query = $this->db->query($sql);
        $rows = $query->result();

        foreach ($rows as $row) {
            $this->insertCache('average_clean_sheets', $type, $season, $row->player_id, $row->clean_sheets, serialize($row));
        }
    }

    /**
     * Generate and cache Consecutive Games a Player Scored-In Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function consecutiveGamesScored($type = false, $season = NULL)
    {
        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $distinctPlayers = $this->fetchDistinctPlayerIds();

        $sql = "SELECT m.*
FROM view_match_goals m
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.date ASC";

        $query = $this->db->query($sql);
        $matches = $query->result();

        foreach ($distinctPlayers as $player) {
            $this->sequenceBase($matches, $player->id, "\$match->goals > 0", 'consecutive_games_scored', $type, $season);
        }
    }

    /**
     * Generate and cache Consecutive Games a Player Assisted-In Statistics by competition type or season
     * @param  boolean|string $type    Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return NULL
     */
    public function consecutiveGamesAssisted($type = false, $season = NULL)
    {
        $whereConditions = array();
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
        }

        $distinctPlayers = $this->fetchDistinctPlayerIds();

        $sql = "SELECT m.*
FROM view_match_assists m
WHERE m.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
ORDER BY m.date ASC";

        $query = $this->db->query($sql);
        $matches = $query->result();

        foreach ($distinctPlayers as $player) {
            $this->sequenceBase($matches, $player->id, "\$match->assists > 0", 'consecutive_games_assisted', $type, $season);
        }
    }

    /**
     * Generate and cache Base Method Statistics by competition type or season
     * @param  array    $matches    List of Matches
     * @param  int      $playerId   Player ID
     * @param  boolean  $byType     Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function sequenceBase($matches, $playerId, $comparisonCode, $statisticGroup, $type = false, $season = NULL)
    {
        $this->deleteRows($statisticGroup, $playerId, $type, $season);

        $records = array();

        $record = new stdClass();
        $record->sequence = 0;
        $record->sequenceStart = '';

        if (count($matches) > 0) {
            $highestSequence = 0;
            $currentSequence = 0;
            $currentSequenceStart = '';
            $currentSequenceFinish = '';

            foreach ($matches as $match) {
                if ($match->player_id == $playerId) {
                    eval("\$comparisonResult = " . $comparisonCode . ";");

                    if ($comparisonResult) {
                        $currentSequence++;

                        if ($currentSequenceStart == '') {
                            $currentSequenceStart = $match->date;
                        }

                        $currentSequenceFinish = $match->date;
                    } else {
                        $record = new stdClass();
                        if ($currentSequence > 1) {
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
            }

            $record = new stdClass();
            if ($currentSequence > 1) {
                $record->sequence = $currentSequence;
                $record->sequenceStart = $currentSequenceStart;
                $record->sequenceFinish = $currentSequenceFinish;

                $records[$match->id] = $record;
            }
        }

        foreach ($records as $record) {
            $this->insertCache($statisticGroup, $type, $season, $playerId, $record->sequence, serialize($record));
        }
    }

    /**
     * Base Class for Generating and Caching Appearance Combination Statistics by competition type or season
     * @param  boolean|string $type  Generate by competition type, set to false for "overall"
     * @param  int|NULL $season      Season to generate, set to null for entire career
     * @param  int      $playerCount Players involved in combination
     * @param  array    $positions   Positions to include in combination, if any
     * @param  boolean  $startsOnly  Should combination include substitutes and starters or just starters
     * @return boolean              Whether query was executed correctly
     */
    public function appearanceCombinationBase($type = false, $season = NULL, $playerCount = 2, $positions = array(), $startsOnly = true)
    {
        self::deleteAverageCleanSheets($type, $season);

        $selectFields = array('COUNT(a1.id) as matches');
        $fromSection = array();
        $whereConditions = array();
        $positionConditions = array();
        $groupByFields = array();

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
        }

        $whereConditions[] = "(m.deleted = 0)";
        $whereConditions[] = "(c.deleted = 0)";

        for ($i = 1; $i <= $playerCount; $i++) {
            $selectFields[] = str_replace("{d}", $i, "a{d}.player_id as player_{d}_id, p{d}.first_name as player_{d}_first_name, p{d}.surname as player_{d}_surname");

            if ($i > 1) {
                $fromSection[] = str_replace("{d}", $i, "
LEFT JOIN appearance a{d} ON a1.match_id = a{d}.match_id
LEFT JOIN player p{d} ON a{d}.player_id = p{d}.id");
            } else {
                $fromSection[] = "FROM appearance a1
LEFT JOIN matches m ON m.id = a1.match_id
LEFT JOIN competition c ON m.competition = c.id
LEFT JOIN player p1 ON a1.player_id = p1.id";
            }

            $whereConditions[] = sprintf("(a%d.`status` " . ($startsOnly ? "= 'starter'" : "!= 'unused'") . ")", $i);
            $whereConditions[] = sprintf("(a%d.deleted = 0)", $i);
            $whereConditions[] = sprintf("(p%d.deleted = 0)", $i);

            for ($j = 2; $j <= $playerCount; $j++) {
                if ($i != $j) {
                    $whereConditions[] = sprintf("a%d.player_id != a%d.player_id", $i, $j);
                }
            }

            if (count($positions) > 0) {
                $positionConditions[] = sprintf("a%d.position", $i) . " IN (" . implode(", ", $positions) . ")";
            }

            $groupByFields[] = sprintf("a%d.player_id", $i);
        }

        if (count($positions) > 0) {
            $whereConditions[] = "(" . implode("\r\n\t\tAND ", $positionConditions) . ")";
        }

        $sql = "SELECT " . implode(", ", $selectFields) . "
" . implode("", $fromSection) . "
WHERE " . implode("\r\n\t AND ", $whereConditions) . "
GROUP BY " . implode(", ", $groupByFields) . "
ORDER BY matches DESC";

        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Generate and cache Most Common Two Player Combination Statistics by competition type or season
     * @param  boolean|string $type  Generate by competition type, set to false for "overall"
     * @param  int|NULL $season      Season to generate, set to null for entire career
     * @return NULL
     */
    public function mostCommonTwoPlayerCombination($type = false, $season = NULL)
    {
        $this->deleteRows('most_common_two_player_combination', false, $type, $season);

        $records = $this->appearanceCombinationBase($type, $season);

        foreach ($records as $record) {
            $this->insertCache('most_common_two_player_combination', $type, $season, $record->player_1_id, $record->matches, serialize($record));
        }
    }

    /**
     * Generate and cache Most Common Centre Back Pairing Statistics by competition type or season
     * @param  boolean|string $type  Generate by competition type, set to false for "overall"
     * @param  int|NULL $season      Season to generate, set to null for entire career
     * @return NULL
     */
    public function mostCommonCentreBackPairing($type = false, $season = NULL)
    {
        $this->deleteRows('most_common_centre_back_pairing', false, $type, $season);

        $records = $this->appearanceCombinationBase($type, $season, 2, array(4));

        foreach ($records as $record) {
            $this->insertCache('most_common_centre_back_pairing', $type, $season, $record->player_1_id, $record->matches, serialize($record));
        }
    }

    /**
     * Generate and cache Most Common Centre Midfield Pairing Statistics by competition type or season
     * @param  boolean|string $type  Generate by competition type, set to false for "overall"
     * @param  int|NULL $season      Season to generate, set to null for entire career
     * @return NULL
     */
    public function mostCommonCentreMidfieldPairing($type = false, $season = NULL)
    {
        $this->deleteRows('most_common_centre_midfield_pairing', false, $type, $season);

        $records = $this->appearanceCombinationBase($type, $season, 2, array(11));

        foreach ($records as $record) {
            $this->insertCache('most_common_centre_midfield_pairing', $type, $season, $record->player_1_id, $record->matches, serialize($record));
        }
    }

    /**
     * Generate and cache Most Common Right Hand Side Pairing Statistics by competition type or season
     * @param  boolean|string $type  Generate by competition type, set to false for "overall"
     * @param  int|NULL $season      Season to generate, set to null for entire career
     * @return NULL
     */
    public function mostCommonRightHandSidePairing($type = false, $season = NULL)
    {
        $this->deleteRows('most_common_right_hand_side_pairing', false, $type, $season);

        $records = $this->appearanceCombinationBase($type, $season, 2, array(2, 9));

        foreach ($records as $record) {
            $this->insertCache('most_common_right_hand_side_pairing', $type, $season, $record->player_1_id, $record->matches, serialize($record));
        }
    }

    /**
     * Generate and cache Most Common Left Hand Side Pairing Statistics by competition type or season
     * @param  boolean|string $type  Generate by competition type, set to false for "overall"
     * @param  int|NULL $season      Season to generate, set to null for entire career
     * @return NULL
     */
    public function mostCommonLeftHandSidePairing($type = false, $season = NULL)
    {
        $this->deleteRows('most_common_left_hand_side_pairing', false, $type, $season);

        $records = $this->appearanceCombinationBase($type, $season, 2, array(3, 10));

        foreach ($records as $record) {
            $this->insertCache('most_common_left_hand_side_pairing', $type, $season, $record->player_1_id, $record->matches, serialize($record));
        }
    }

    /**
     * Generate and cache Most Common Strike Partners Statistics by competition type or season
     * @param  boolean|string $type  Generate by competition type, set to false for "overall"
     * @param  int|NULL $season      Season to generate, set to null for entire career
     * @return NULL
     */
    public function mostCommonStrikePartner($type = false, $season = NULL)
    {
        $this->deleteRows('most_common_strike_partner', false, $type, $season);

        $records = $this->appearanceCombinationBase($type, $season, 2, array(16));

        foreach ($records as $record) {
            $this->insertCache('most_common_strike_partner', $type, $season, $record->player_1_id, $record->matches, serialize($record));
        }
    }

    /**
     * Delete cached Debut
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteDebut($byType = false, $season = NULL)
    {
        return $this->deleteRows('debut', false, $byType, $season);
    }

    /**
     * Delete cached First Goal
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteFirstGoal($byType = false, $season = NULL)
    {
        return $this->deleteRows('first_goal', false, $byType, $season);
    }

    /**
     * Delete cached Scored on Debut
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteScoredOnDebut($byType = false, $season = NULL)
    {
        return $this->deleteRows('scored_on_debut', false, $byType, $season);
    }

    /**
     * Delete cached Hattricks
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteHattricks($byType = false, $season = NULL)
    {
        return $this->deleteRows('hattricks', false, $byType, $season);
    }

    /**
     * Delete cached Debut & First Goal Time Difference
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteDebutAndFirstGoalTimeDifference($byType = false, $season = NULL)
    {
        return $this->deleteRows('debut_and_first_goal_time_difference', false, $byType, $season);
    }

    /**
     * Delete cached Debut & First Goal Game Difference
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteDebutAndFirstGoalGameDifference($byType = false, $season = NULL)
    {
        return $this->deleteRows('debut_and_first_goal_game_difference', false, $byType, $season);
    }

    /**
     * Delete cached Real Points Gained
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteRealPointsGained($byType = false, $season = NULL)
    {
        return $this->deleteRows('real_points_gained', false, $byType, $season);
    }

    /**
     * Delete cached Average Points Gained
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteAveragePointsGained($byType = false, $season = NULL)
    {
        return $this->deleteRows('average_points_gained', false, $byType, $season);
    }

    /**
     * Delete cached Real Points
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteRealPoints($byType = false, $season = NULL)
    {
        return $this->deleteRows('real_points', false, $byType, $season);
    }

    /**
     * Delete cached Average Points
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteAveragePoints($byType = false, $season = NULL)
    {
        return $this->deleteRows('average_points', false, $byType, $season);
    }

    /**
     * Delete cached Real Goals Gained
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteRealGoalsGained($byType = false, $season = NULL)
    {
        return $this->deleteRows('real_goals_gained', false, $byType, $season);
    }

    /**
     * Delete cached Average Goals Gained
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteAverageGoalsGained($byType = false, $season = NULL)
    {
        return $this->deleteRows('average_goals_gained', false, $byType, $season);
    }

    /**
     * Delete cached Real Goals
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteRealGoals($byType = false, $season = NULL)
    {
        return $this->deleteRows('real_goals', false, $byType, $season);
    }

    /**
     * Delete cached Average Goals For
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteAverageGoalsFor($byType = false, $season = NULL)
    {
        return $this->deleteRows('average_goals_for', false, $byType, $season);
    }

    /**
     * Delete cached Average Goals Against
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteAverageGoalsAgainst($byType = false, $season = NULL)
    {
        return $this->deleteRows('average_goals_against', false, $byType, $season);
    }

    /**
     * Delete cached Total Clean Sheets
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteTotalCleanSheets($byType = false, $season = NULL)
    {
        return $this->deleteRows('total_clean_sheets', false, $byType, $season);
    }

    /**
     * Delete cached Average Clean Sheets
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteAverageCleanSheets($byType = false, $season = NULL)
    {
        return $this->deleteRows('average_clean_sheets', false, $byType, $season);
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
     * Particular Player Statistics, based on Season and/or Competition Type
     * @param  int $statisticGroup  Statistic Group
     * @param  int|boolean $playerId   Player ID, false for all
     * @param  boolean  $byType        Generate by competition type, set to false for "overall"
     * @param  int|NULL $season        Season to generate, set to null for entire career
     * @return boolean                 Whether query was executed correctly
     */
    public function deleteRows($statisticGroup, $playerId = false, $type = false, $season = NULL)
    {
        $whereConditions = array();

        $whereConditions['statistic_group'] = $statisticGroup;
        $whereConditions['type']            = $type ? $type : 'overall';
        $whereConditions['season']          = $season ? $season : 'career';

        if ($playerId) {
            $whereConditions['player_id']   = $playerId;
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