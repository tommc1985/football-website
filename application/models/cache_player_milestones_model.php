<?php
class Cache_Player_Milestones_model extends CI_Model {

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
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
        $this->ci->load->model('Competition_model');
        $this->ci->load->model('Season_model');

        $this->tableName = 'cache_player_milestones';
        $this->queueTableName = 'cache_queue_player_milestones';

        $this->methodMap = array(
        );

        $this->hungryMethodMap = array(
            'nth_appearance'           => 'nthAppearance',
            'nth_goal'                 => 'nthGoal',
            'nth_assist'               => 'nthAssist',
            'nth_yellow_card'          => 'nthYellowCard',
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
        $this->insertEntry(1);

        foreach ($this->hungryMethodMap as $cacheData => $method) {
            $this->insertEntry(NULL, NULL, $cacheData);
            $this->insertEntry(1, NULL, $cacheData);

            if (is_null($season)) {
                $i = $this->ci->Season_model->fetchEarliestYear();
                while($i <= Season_model::fetchCurrentSeason()){

                    $this->insertEntry(NULL, $i, $cacheData);
                    $this->insertEntry(1, $i, $cacheData);

                    $i++;
                }
            } else {
                $this->insertEntry(NULL, $season, $cacheData);
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
    public function fetchLatest($limit = 5)
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

            if (is_null($row->by_type)) { // "Overall" statistics
                $this->$method(false, $row->season);
            } else { // Grouped by "type"
                foreach (Competition_model::fetchTypes() as $type => $typeFriendly) {
                    $this->$method($type, $row->season);
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
     * @param  string $statisticGroup   Unique identifier for statistic
     * @param  string|boolean $type     Competition Type - false, "league", "cup", etc
     * @param  int|boolean $season      Season relating to the statistic - false or integer
     * @param  int $playerId            Player ID
     * @param  int $matchId             Match ID
     * @param  int $date                Date
     * @param  string $statisticKey     Most Likely the most important value related to the statistic
     * @param  string $statisticValue   Most Likely a serialized object of all data related to the statistic
     * @return NULL
     */
    public function insertCache($statisticGroup, $type, $season, $playerId, $matchId, $date, $statisticKey, $statisticValue)
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
        $object->match_id = $matchId;
        $object->date = $date;
        $object->statistic_group = $statisticGroup;
        $object->statistic_key = $statisticKey;
        $object->statistic_value = $statisticValue;

        $this->db->insert($this->tableName, $object);
    }

    /**
     * Generate and cache nth Apearance Milestone Statistics by season or type
     * @param  boolean $type        Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function nthAppearance($type = false, $season = NULL)
    {
        $statisticGroup = 'nth_appearance';

        $this->deleteRows($statisticGroup, $type, $season);

        $whereConditions = array();
        $whereConditions2 = array();
        $seasonValue = "career";
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
            $seasonValue = $season;
        }

        $competitionType = "overall";
        if (is_string($type)) {
            $whereConditions[] = "(m.competition_type = '{$type}')";
            $whereConditions2[] = "(m2.competition_type = '{$type}')";
            $competitionType = $type;
        }

        $sql = "INSERT INTO {$this->tableName} (type, season, player_id, match_id, date, statistic_group, statistic_key)
SELECT
    '{$competitionType}' as type,
    '{$seasonValue}' as season,
    m.player_id,
    m.match_id,
    m.date,
    '{$statisticGroup}',
    (SELECT COUNT(m2.id)
    FROM view_appearances_ages m2
    LEFT JOIN competition c ON c.id = m2.competition_id
    WHERE m.status != 'unused'
        AND c.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND c.deleted = 0
        AND m2.date < m.date
        AND m2.player_id = m.player_id) + 1 as game_number
FROM view_appearances_ages m
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND m.status != 'unused'
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
ORDER BY m.date ASC";

        $query = $this->db->query($sql);
    }

    /**
     * Generate and cache nth Goal Milestone Statistics by season or type
     * @param  boolean $type        Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function nthGoal($type = false, $season = NULL)
    {
        $statisticGroup = 'nth_goal';

        $this->deleteRows($statisticGroup, $type, $season);

        $whereConditions = array();
        $whereConditions2 = array();
        $seasonValue = "career";
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
            $seasonValue = $season;
        }

        $competitionType = "overall";
        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
            $whereConditions2[] = "(c2.type = '{$type}')";
            $competitionType = $type;
        }

        $sql = "INSERT INTO {$this->tableName} (type, season, player_id, match_id, date, statistic_group, statistic_key)
SELECT
    '{$competitionType}' as type,
    '{$seasonValue}' as season,
    g.scorer_id,
    g.match_id,
    m.date,
    '{$statisticGroup}',
    (SELECT COUNT(m2.id)
    FROM goal g2
    LEFT JOIN matches m2 ON m2.id = g2.match_id
    LEFT JOIN competition c2 ON c2.id = m2.competition_id
    LEFT JOIN competition_stage cs2 ON cs2.id = m2.competition_stage_id
    WHERE c2.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND g2.deleted = 0
        AND m2.deleted = 0
        AND c2.deleted = 0
        AND (cs2.deleted = 0 || ISNULL(m2.competition_stage_id))
        AND (UNIX_TIMESTAMP(m2.date) + g2.minute) < (UNIX_TIMESTAMP(m.date) + g.minute)
        AND g2.scorer_id = g.scorer_id) + 1 as game_number
FROM goal g
LEFT JOIN matches m ON m.id = g.match_id
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND g.deleted = 0
    AND m.deleted = 0
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
ORDER BY m.date ASC, g.minute ASC";

        $query = $this->db->query($sql);
    }

    /**
     * Generate and cache nth Assist Milestone Statistics by season or type
     * @param  boolean $type        Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function nthAssist($type = false, $season = NULL)
    {
        $statisticGroup = 'nth_assist';

        $this->deleteRows($statisticGroup, $type, $season);

        $whereConditions = array();
        $whereConditions2 = array();
        $seasonValue = "career";
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
            $seasonValue = $season;
        }

        $competitionType = "overall";
        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
            $whereConditions2[] = "(c2.type = '{$type}')";
            $competitionType = $type;
        }

        $sql = "INSERT INTO {$this->tableName} (type, season, player_id, match_id, date, statistic_group, statistic_key)
SELECT
    '{$competitionType}' as type,
    '{$seasonValue}' as season,
    g.assist_id,
    g.match_id,
    m.date,
    '{$statisticGroup}',
    (SELECT COUNT(m2.id)
    FROM goal g2
    LEFT JOIN matches m2 ON m2.id = g2.match_id
    LEFT JOIN competition c2 ON c2.id = m2.competition_id
    LEFT JOIN competition_stage cs2 ON cs2.id = m2.competition_stage_id
    WHERE c2.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND g2.deleted = 0
        AND m2.deleted = 0
        AND c2.deleted = 0
        AND (cs2.deleted = 0 || ISNULL(m2.competition_stage_id))
        AND (UNIX_TIMESTAMP(m2.date) + g2.minute) < (UNIX_TIMESTAMP(m.date) + g.minute)
        AND g2.assist_id = g.assist_id) + 1 as game_number
FROM goal g
LEFT JOIN matches m ON m.id = g.match_id
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND g.deleted = 0
    AND m.deleted = 0
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
ORDER BY m.date ASC, g.minute ASC";

        $query = $this->db->query($sql);
    }

    /**
     * Generate and cache nth Yellow Card Milestone Statistics by season or type
     * @param  boolean $type        Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function nthYellowCard($type = false, $season = NULL)
    {
        $statisticGroup = 'nth_yellow_card';

        $this->deleteRows($statisticGroup, $type, $season);

        $whereConditions = array();
        $whereConditions2 = array();
        $seasonValue = "career";
        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
            $seasonValue = $season;
        }

        $competitionType = "overall";
        if (is_string($type)) {
            $whereConditions[] = "(c.type = '{$type}')";
            $whereConditions2[] = "(c2.type = '{$type}')";
            $competitionType = $type;
        }

        $sql = "INSERT INTO {$this->tableName} (type, season, player_id, match_id, date, statistic_group, statistic_key)
SELECT
    '{$competitionType}' as type,
    '{$seasonValue}' as season,
    g.player_id,
    g.match_id,
    m.date,
    '{$statisticGroup}',
    (SELECT COUNT(m2.id)
    FROM card g2
    LEFT JOIN matches m2 ON m2.id = g2.match_id
    LEFT JOIN competition c2 ON c2.id = m2.competition_id
    LEFT JOIN competition_stage cs2 ON cs2.id = m2.competition_stage_id
    WHERE c2.competitive = 1" . (count($whereConditions2) > 0 ? "
        AND " . implode(" \r\nAND ", $whereConditions2) : '') . "
        AND g2.type = 'y'
        AND g2.deleted = 0
        AND m2.deleted = 0
        AND c2.deleted = 0
        AND (cs2.deleted = 0 || ISNULL(m2.competition_stage_id))
        AND (UNIX_TIMESTAMP(m2.date) + g2.minute) < (UNIX_TIMESTAMP(m.date) + g.minute)
        AND g2.player_id = g.player_id) + 1 as game_number
FROM card g
LEFT JOIN matches m ON m.id = g.match_id
LEFT JOIN opposition o ON o.id = m.opposition_id
LEFT JOIN competition c ON c.id = m.competition_id
LEFT JOIN competition_stage cs ON cs.id = m.competition_stage_id
WHERE c.competitive = 1" . (count($whereConditions) > 0 ? "
    AND " . implode(" \r\nAND ", $whereConditions) : '') . "
    AND g.type = 'y'
    AND g.deleted = 0
    AND m.deleted = 0
    AND o.deleted = 0
    AND c.deleted = 0
    AND (cs.deleted = 0 || ISNULL(m.competition_stage_id))
ORDER BY m.date ASC, g.minute ASC";

        $query = $this->db->query($sql);
    }

    /**
     * Generate all statistics
     * @return boolean Whether query was executed correctly
     */
    public function generateAllStatistics()
    {
        foreach ($this->methodMap as $method) {
            $this->$method();
            $this->$method(true);
        }

        $season = $this->ci->Season_model->fetchEarliestYear();
        while($season <= Season_model::fetchCurrentSeason()){

            foreach ($this->methodMap as $method) {
                $this->$method(false, $season);
                $this->$method(true, $season);
            }

            $season++;
        }

        return true;
    }

    /**
     * Particular Stats, based on Season, Competition Type and/or Player
     * @param  int $statisticGroup  Statistic Group
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @return boolean              Whether query was executed correctly
     */
    public function deleteRows($statisticGroup, $byType = false, $season = NULL)
    {
        $whereConditions = array();

        $whereConditions['statistic_group'] = $statisticGroup;
        $whereConditions['season']          = $season ? $season : 'career';

        if (is_string($byType)) {
            $whereConditions['type']        = $byType;
        } else {
            $whereConditions['type']        = 'overall';
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