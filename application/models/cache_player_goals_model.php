<?php
class Cache_Player_Goals_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public $tableName;
    public $queueTableName;

    public $chartTypeFunctionMap;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
        $this->ci->load->model('Season_model');

        $this->tableName = 'cache_player_goal_statistics';
        $this->queueTableName = 'cache_queue_player_goal_statistics';

        $this->chartTypeFunctionMap = array(1 => 'byGoalType',
            2 => 'byBodyPart',
            3 => 'byDistance',
            4 => 'byAssister',
            5 => 'byScorer',
            6 => 'byMinuteInterval');
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int|NULL $byType     Group by "type" or "overall"
     * @param  int|NULL $season     Season "career"
     * @param  int|NULL $playerId   Single Player
     * @param  int|NULL $cacheData  What specific data to cache
     * @return boolean
     */
    public function insertEntry($byType = NULL, $season = NULL, $playerId = NULL, $cacheData = NULL)
    {
        $data = array('by_type' => $byType,
            'season' => $season,
            'player_id' => $playerId,
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
            ->where('completed', 0)
            ->where('deleted', 0)
            ->order_by('id', 'asc')
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
        if ($row->cache_data > 0) {
            $method = $this->chartTypeFunctionMap[$row->cache_data];

            if (is_null($row->player_id)) { // Cache all players
                if (is_null($row->by_type)) { // "Overall" statistics
                    $this->$method(false, $row->season);
                    //$byType = false, $season = NULL, $playerId = NULL
                } else { // Grouped by "type"
                    $this->$method(true, $row->season);
                }
            } else { // Cache specific player
                if (is_null($row->by_type)) { // "Overall" statistics
                    $this->$method(false, $row->season, $row->player_id);
                } else { // Grouped by "type"
                    $this->$method(true, $row->season, $row->player_id);
                }
            }
        } else {
            $this->generateAllStatistics();
        }

        $row->completed = 1;

        return $this->updateEntry($row);
    }

    /**
     * Return SQL used for generating cached Player Goal Statistics, with placeholders
     * @return string SQL
     */
    public static function playerStatisticsSQL()
    {
        return "INSERT INTO cache_player_goal_statistics (type, season, statistic_group, player_id, statistic_key, statistic_value)
SELECT
    {competition_type} as type,
    {season} as season,
    '{statistic_group}' as statistic_group,
    {extra_fields}
FROM goal g
LEFT JOIN matches m ON g.match_id = m.id
LEFT JOIN competition c ON m.competition = c.id
{extra_joins}
WHERE c.competitive = 1
    AND m.deleted = 0
    AND g.deleted = 0
    {where_conditions}
{group_by}
{order_by}
{limit}";
    }

    /**
     * Replace placeholder with values from array
     * @param  array $data Key/Value pairs of data to replace placeholders
     * @return string       Return SQL with specified values inserted
     */
    public static function insertSQLValues($data)
    {
        $sql = self::playerStatisticsSQL();

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'competition_type':
                    $value = !$value ? 'c.type' : $value;
                    break;
                case 'season':
                    $value = !$value ? "'career'" : $value;
                    break;
                case 'statistic_group':
                    $value = !$value ? 0 : $value;
                    break;
                case 'extra_fields':
                    $value = !$value ? '' : implode(",\n    ", $value);
                    break;
                case 'extra_joins':
                    $value = !$value ? '' : implode("\n", $value);
                    break;
                case 'where_conditions':
                    $value = !$value ? '' : "AND {$value}";
                    break;
                case 'group_by':
                    $value = !$value ? '' : 'GROUP BY ' . implode(", ", $value);
                    break;
                case 'order_by':
                    $value = !$value ? '' : 'ORDER BY ' . implode(", ", $value);
                    break;
                case 'limit':
                    $value = !$value ? '' : 'LIMIT ' . $value;
                    break;
            }

            $sql = str_replace('{' . $key . '}', $value, $sql);
        }

        return $sql;
    }

    /**
     * Generate and cache Goal Type Statistics by season, type or player
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  int|ULL $playerId    Specific player, leave empty for entire team
     * @return boolean              Whether query was executed correctly
     */
    public function byGoalType($byType = false, $season = NULL, $playerId = NULL)
    {
        self::deleteByGoalType($byType, $season, $playerId);

        $competitionType = $byType ? '' : "'overall'";
        $statisticGroup  = 'by_goal_type';
        $extraFields     = array();
        $extraJoins      = array();
        $whereConditions = array();
        $groupBy         = array();
        $orderBy         = array();
        $limit           = '';

        $extraFields[] = 'g.scorer_id';
        $extraFields[] = 'g.type';
        $extraFields[] = 'COUNT(g.id) as frequency';

        if ($playerId) {
            $whereConditions[] = "g.scorer_id = {$playerId}";
        }

        if ($season) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $groupBy[] = 'g.scorer_id';
        $groupBy[] = 'g.type';

        if ($byType) {
            $groupBy[] = 'c.type';
        }

        $data = array('competition_type' => $competitionType,
            'season' => $season,
            'statistic_group' => $statisticGroup,
            'extra_fields' => $extraFields,
            'extra_joins' => $extraJoins,
            'where_conditions' => implode(" \r\nAND ", $whereConditions),
            'group_by' => $groupBy,
            'order_by' => $orderBy,
            'limit' => $limit);

        $sql = self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Generate and cache Body Part Statistics, by season, type or player
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  int|ULL $playerId    Specific player, leave empty for entire team
     * @return boolean              Whether query was executed correctly
     */
    public function byBodyPart($byType = false, $season = NULL, $playerId = NULL)
    {
        self::deleteByBodyPart($byType, $season, $playerId);

        $competitionType = $byType ? '' : "'overall'";
        $statisticGroup  = 'by_body_part';
        $extraFields     = array();
        $extraJoins      = array();
        $whereConditions = array();
        $groupBy         = array();
        $orderBy         = array();
        $limit           = '';

        $extraFields[] = 'g.scorer_id';
        $extraFields[] = 'g.body_part';
        $extraFields[] = 'COUNT(g.id) as frequency';

        if ($playerId) {
            $whereConditions[] = "g.scorer_id = {$playerId}";
        }

        if ($season) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $groupBy[] = 'g.scorer_id';
        $groupBy[] = 'g.body_part';

        if ($byType) {
            $groupBy[] = 'c.type';
        }

        $data = array('competition_type' => $competitionType,
            'season' => $season,
            'statistic_group' => $statisticGroup,
            'extra_fields' => $extraFields,
            'extra_joins' => $extraJoins,
            'where_conditions' => implode(" \r\nAND ", $whereConditions),
            'group_by' => $groupBy,
            'order_by' => $orderBy,
            'limit' => $limit);

        $sql = self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Generate and cache Distance Statistics, by season, type or player
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  int|ULL $playerId    Specific player, leave empty for entire team
     * @return boolean              Whether query was executed correctly
     */
    public function byDistance($byType = false, $season = NULL, $playerId = NULL)
    {
        self::deleteByDistance($byType, $season, $playerId);

        $competitionType = $byType ? '' : "'overall'";
        $statisticGroup  = 'by_distance';
        $extraFields     = array();
        $extraJoins      = array();
        $whereConditions = array();
        $groupBy         = array();
        $orderBy         = array();
        $limit           = '';

        $extraFields[] = 'g.scorer_id';
        $extraFields[] = 'g.distance';
        $extraFields[] = 'COUNT(g.id) as frequency';

        if ($playerId) {
            $whereConditions[] = "g.scorer_id = {$playerId}";
        }

        if ($season) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $groupBy[] = 'g.scorer_id';
        $groupBy[] = 'g.distance';

        if ($byType) {
            $groupBy[] = 'c.type';
        }

        $data = array('competition_type' => $competitionType,
            'season' => $season,
            'statistic_group' => $statisticGroup,
            'extra_fields' => $extraFields,
            'extra_joins' => $extraJoins,
            'where_conditions' => implode(" \r\nAND ", $whereConditions),
            'group_by' => $groupBy,
            'order_by' => $orderBy,
            'limit' => $limit);

        $sql = self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Generate and cache Assister Statistics, by season, type or player
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  int|ULL $playerId    Specific player, leave empty for entire team
     * @return boolean              Whether query was executed correctly
     */
    public function byAssister($byType = false, $season = NULL, $playerId = NULL)
    {
        self::deleteByDistance($byType, $season, $playerId);

        $competitionType = $byType ? '' : "'overall'";
        $statisticGroup  = 'by_assister';
        $extraFields     = array();
        $extraJoins      = array();
        $whereConditions = array();
        $groupBy         = array();
        $orderBy         = array();
        $limit           = '';

        $extraFields[] = 'g.scorer_id';
        $extraFields[] = 'g.assist_id';
        $extraFields[] = 'COUNT(g.id) as frequency';

        if ($playerId) {
            $whereConditions[] = "g.scorer_id = {$playerId}";
        }

        if ($season) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $groupBy[] = 'g.scorer_id';
        $groupBy[] = 'g.assist_id';

        if ($byType) {
            $groupBy[] = 'c.type';
        }

        $data = array('competition_type' => $competitionType,
            'season' => $season,
            'statistic_group' => $statisticGroup,
            'extra_fields' => $extraFields,
            'extra_joins' => $extraJoins,
            'where_conditions' => implode(" \r\nAND ", $whereConditions),
            'group_by' => $groupBy,
            'order_by' => $orderBy,
            'limit' => $limit);

        $sql = self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Generate and cache Scorer Statistics, by season, type or player
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  int|ULL $playerId    Specific player, leave empty for entire team
     * @return boolean              Whether query was executed correctly
     */
    public function byScorer($byType = false, $season = NULL, $playerId = NULL)
    {
        self::deleteByDistance($byType, $season, $playerId);

        $competitionType = $byType ? '' : "'overall'";
        $statisticGroup  = 'by_scorer';
        $extraFields     = array();
        $extraJoins      = array();
        $whereConditions = array();
        $groupBy         = array();
        $orderBy         = array();
        $limit           = '';

        $extraFields[] = 'g.assist_id';
        $extraFields[] = 'g.scorer_id';
        $extraFields[] = 'COUNT(g.id) as frequency';

        if ($playerId) {
            $whereConditions[] = "g.scorer_id = {$playerId}";
        }

        if ($season) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $groupBy[] = 'g.assist_id';
        $groupBy[] = 'g.scorer_id';

        if ($byType) {
            $groupBy[] = 'c.type';
        }

        $data = array('competition_type' => $competitionType,
            'season' => $season,
            'statistic_group' => $statisticGroup,
            'extra_fields' => $extraFields,
            'extra_joins' => $extraJoins,
            'where_conditions' => implode(" \r\nAND ", $whereConditions),
            'group_by' => $groupBy,
            'order_by' => $orderBy,
            'limit' => $limit);

        $sql = self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Generate and cache Minute Interval Statistics, by season, type or player
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  int|ULL $playerId    Specific player, leave empty for entire team
     * @return boolean              Whether query was executed correctly
     */
    public function byMinuteInterval($byType = false, $season = NULL, $playerId = NULL)
    {
        self::deleteByMinuteInterval($byType, $season, $playerId);

        $competitionType = $byType ? '' : "'overall'";
        $statisticGroup  = 'by_minute_interval';
        $extraFields     = array();
        $extraJoins      = array();
        $whereConditions = array();
        $groupBy         = array();
        $orderBy         = array();
        $limit           = '';

        $extraFields[] = 'g.scorer_id';
        $extraFields[] = "IF(g.minute > 0 AND g.minute <=15, 1,
    IF(g.minute > 15 AND g.minute <=30, 2,
        IF(g.minute > 30 AND g.minute <=45, 3,
            IF(g.minute > 45 AND g.minute <=60, 4,
                IF(g.minute > 60 AND g.minute <=75, 5,
                    IF(g.minute > 75 AND g.minute <=90, 6,
                        IF(g.minute > 90 AND g.minute <=105, 7, 8)
                    )
                )
            )
        )
    )
) as minute_interval";
        $extraFields[] = 'COUNT(g.id) as frequency';

        if ($playerId) {
            $whereConditions[] = "g.scorer_id = {$playerId}";
        }

        if ($season) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(m.date {$dates['startDate']} AND m.date {$dates['endDate']})";
        }

        $groupBy[] = 'g.scorer_id';
        $groupBy[] = 'minute_interval';

        if ($byType) {
            $groupBy[] = 'c.type';
        }

        $data = array('competition_type' => $competitionType,
            'season' => $season,
            'statistic_group' => $statisticGroup,
            'extra_fields' => $extraFields,
            'extra_joins' => $extraJoins,
            'where_conditions' => implode(" \r\nAND ", $whereConditions),
            'group_by' => $groupBy,
            'order_by' => $orderBy,
            'limit' => $limit);

        $sql = self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Delete cached Goal Type Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @param  int|NULL $playerId Specific player or all players
     * @return boolean            Were rows deleted
     */
    public function deleteByGoalType($byType = false, $season = NULL, $playerId = NULL)
    {
        return $this->deleteRows('by_goal_type', $byType, $season, $playerId);
    }

    /**
     * Delete cached Body Part Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @param  int|NULL $playerId Specific player or all players
     * @return boolean            Were rows deleted
     */
    public function deleteByBodyPart($byType = false, $season = NULL, $playerId = NULL)
    {
        return $this->deleteRows('by_body_type', $byType, $season, $playerId);
    }

    /**
     * Delete cached Distance Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @param  int|NULL $playerId Specific player or all players
     * @return boolean            Were rows deleted
     */
    public function deleteByDistance($byType = false, $season = NULL, $playerId = NULL)
    {
        return $this->deleteRows('by_distance', $byType, $season, $playerId);
    }

    /**
     * Delete cached Assister Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @param  int|NULL $playerId Specific player or all players
     * @return boolean            Were rows deleted
     */
    public function deleteAssister($byType = false, $season = NULL, $playerId = NULL)
    {
        return $this->deleteRows('by_assister', $byType, $season, $playerId);
    }

    /**
     * Delete cached Scorer Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @param  int|NULL $playerId Specific player or all players
     * @return boolean            Were rows deleted
     */
    public function deleteScorer($byType = false, $season = NULL, $playerId = NULL)
    {
        return $this->deleteRows('by_scorer', $byType, $season, $playerId);
    }

    /**
     * Delete cached Minute Interval Statistics
     * @param  boolean  $byType   Competition Type
     * @param  int|NULL $season   Season or Career
     * @param  int|NULL $playerId Specific player or all players
     * @return boolean            Were rows deleted
     */
    public function deleteByMinuteInterval($byType = false, $season = NULL, $playerId = NULL)
    {
        return $this->deleteRows('by_minute_interval', $byType, $season, $playerId);
    }

    /**
     * Generate all statistics
     * @return boolean Whether query was executed correctly
     */
    public function generateAllStatistics()
    {
        $this->byGoalType();
        $this->byGoalType(true);
        $this->byBodyPart();
        $this->byBodyPart(true);
        $this->byDistance();
        $this->byDistance(true);
        $this->byAssister();
        $this->byAssister(true);
        $this->byScorer();
        $this->byScorer(true);
        $this->byMinuteInterval();
        $this->byMinuteInterval(true);

        $season = $this->ci->Season_model->fetchEarliestYear();
        while($season <= Season_model::fetchCurrentSeason()){
            $this->byGoalType(false, $season);
            $this->byGoalType(true, $season);
            $this->byBodyPart(false, $season);
            $this->byBodyPart(true, $season);
            $this->byDistance(false, $season);
            $this->byDistance(true, $season);
            $this->byAssister(false, $season);
            $this->byAssister(true, $season);
            $this->byScorer(false, $season);
            $this->byScorer(true, $season);
            $this->byMinuteInterval(false, $season);
            $this->byMinuteInterval(true, $season);
            $season++;
        }

        return true;
    }

    /**
     * Particular Stats, based on Season, Competition Type and/or Player
     * @param  int $statisticGroup  Statistic Group
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @param  boolean $byType      Generate by competition type, set to false for "overall"
     * @param  int|ULL $playerId    Specific player, leave empty for entire team
     * @return boolean              Whether query was executed correctly
     */
    public function deleteRows($statisticGroup, $byType = false, $season = NULL, $playerId = NULL)
    {
        $whereConditions = array();

        $whereConditions['statistic_group'] = $statisticGroup;
        $whereConditions['season']           = $season ? $season : 'career';

        if ($byType) {
            $whereConditions['type !=']       = 'overall';
        } else {
            $whereConditions['type']       = 'overall';
        }

        if ($playerId) {
            $whereConditions['player_id']       = $playerId;
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