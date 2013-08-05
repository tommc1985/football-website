<?php
class Cache_Player_Accumulated_Statistics_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public $tableName;
    public $queueTableName;

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

        $this->tableName = 'cache_player_accumulated_statistics';
        $this->queueTableName = 'cache_queue_player_accumulated_statistics';
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

        if (is_null($season)) {
            $i = $this->ci->Season_model->fetchEarliestYear();
            while($i <= Season_model::fetchCurrentSeason()){

                $this->insertEntry(NULL, $i);
                $this->insertEntry(1, $i);

                $i++;
            }
        } else {
            $this->insertEntry(NULL, $season);
            $this->insertEntry(1, $season);
        }
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int|NULL $byType         Group by "type" or "overall"
     * @param  int|NULL $season         Season "career"
     * @param  int|NULL $playerId       What specific player data to cache
     * @return boolean                  Whether the row was inserted successfully
     */
    public function insertEntry($byType = NULL, $season = NULL, $playerId = NULL)
    {
        if (!$this->entryExists($byType, $season, $playerId)) {
            $data = array(
                'by_type' => $byType,
                'season' => $season,
                'player_id' => $playerId,
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
     * @param  int|NULL $playerId       What specific player data to cache
     * @return boolean                  Does the queue entry already exist?
     */
    public function entryExists($byType = NULL, $season = NULL, $playerId = NULL)
    {
        $this->db->select('*')
            ->from($this->queueTableName)
            ->where('by_type', $byType)
            ->where('season', $season)
            ->where('player_id', $playerId)
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
    public function fetchLatest($limit = 10)
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

        // Flag the row is being processed
        $row->in_progress = 1;
        $this->updateEntry($row);

        if (is_null($row->player_id)) { // Cache all players
            if (is_null($row->by_type)) { // "Overall" statistics
                $this->allOverallStatistics($row->season);
            } else { // Grouped by "type"
                $this->allStatisticsByType($row->season);
            }
        } else { // Cache specific player
            if (is_null($row->by_type)) { // "Overall" statistics
                $this->singleOverallStatistics($row->player_id, $row->season);
            } else { // Grouped by "type"
                $this->singleStatisticsByType($row->player_id, $row->season);
            }
        }

        // Flag that row is no longer being processed and is complete
        $row->in_progress = 0;
        $row->completed = 1;

        $finishUnixTime = time();
        $row->process_duration = $finishUnixTime - $startUnixTime;

        $row->peak_memory_usage = number_format(memory_get_peak_usage(true) / 1048576, 2);

        return $this->updateEntry($row);
    }

    /**
     * Return SQL used for generating cached Player Statistics, with placeholders
     * @return string SQL
     */
    public static function playerStatisticsSQL()
    {
        return "INSERT INTO cache_player_accumulated_statistics (player_id, type, season, appearances, starter_appearances, substitute_appearances, goals, assists, motms, yellows, reds, average_rating)
SELECT
    DISTINCT(pr.player_id),
    {competition_type} as type,
    {season} as season,
    COUNT(IF(vamc.status != 'unused', 1, NULL)) as appearances,
    COUNT(IF(vamc.status = 'starter', 1, NULL)) as starter_appearances,
    COUNT(IF(vamc.status = 'substitute', 1, NULL)) as substitute_appearances,
    IFNULL(SUM(vamc.goals), 0) as goals,
    IFNULL(SUM(vamc.assists), 0) as assists,
    COUNT(IF(vamc.motm = 1, 1, NULL)) as motms,
    IFNULL(SUM(vamc.yellows), 0) as yellows,
    IFNULL(SUM(vamc.reds), 0) as reds,
    AVG(IF(vamc.rating > 0 AND vamc.status != 'unused', vamc.rating, NULL)) as average_rating
FROM player_registration pr
LEFT JOIN
    (SELECT vamc.*
    FROM view_appearances_matches_combined vamc
    WHERE vamc.competitive = 1
        AND vamc.status != 'unused'
        {where_conditions_2}
    ) vamc ON vamc.player_id = pr.player_id
WHERE
    {where_conditions}
GROUP BY {group_by}
ORDER BY pr.player_id ASC";
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
                    $value = !$value ? 'vamc.competition_type' : $value;
                    break;
                case 'season':
                    $value = !$value ? "'career'" : $value;
                    break;
                case 'where_conditions':
                    $value = !$value ? '' : "{$value}";
                    break;
                case 'where_conditions_2':
                    $value = !$value ? '' : "AND {$value}";
                    break;
                case 'group_by':
                    $value = !$value ? 'vamc.competition_type' : $value;
                    break;
            }

            $sql = str_replace('{' . $key . '}', $value, $sql);
        }

        return $sql;
    }

    /**
     * Generate and cache a single Player's overall (aggregated) statistics, either for a career or a particular season
     * @param  int $playerId        Player's ID
     * @param  int|NULL $season     Four digit number for season, or NULL for Career
     * @return boolean              Whether query was executed correctly
     */
    public function singleOverallStatistics($playerId, $season = NULL)
    {
        self::deleteSingleOverallStatistics($playerId, $season);

        $whereConditions   = array();
        $whereConditions2  = array();
        $whereConditions[] = "pr.player_id = {$playerId}";
        $whereConditions[] = "pr.deleted = 0";
        $groupBy           = 'pr.player_id, pr.season';

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions2[] = "(vamc.date {$dates['startDate']} AND vamc.date {$dates['endDate']})";
            $whereConditions[]  = "pr.season = {$season}";
        }

        $data = array('competition_type' => "'overall'",
            'season' => $season,
            'where_conditions' => implode(" \r\n\tAND ", $whereConditions),
            'where_conditions_2' => implode(" \r\nAND ", $whereConditions2),
            'group_by' => $groupBy);

        $sql =  self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Delete cache of a single Player's overall (aggregated) statistics, either for a career or a particular season
     * @param  int $playerId        Player's ID
     * @param  int|NULL $season     Four digit number for season, or NULL for Career
     * @return boolean              Whether query was executed correctly
     */
    public function deleteSingleOverallStatistics($playerId, $season = NULL)
    {
        $season = !$season ? 'career' : (int) $season;

        return $this->db->delete($this->tableName, array('player_id' => $playerId,
            'type' => 'overall',
            'season' => $season));
    }

    /**
     * Generate and cache every Players' overall (aggregated) statistics, either for a career or a particular season
     * @param  int|NULL $season     Four digit number for season, or NULL for Career
     * @return boolean              Whether query was executed correctly
     */
    public function allOverallStatistics($season = NULL)
    {
        self::deleteAllOverallStatistics($season);

        $whereConditions   = array();
        $whereConditions2  = array();
        $whereConditions[] = "pr.deleted = 0";
        $groupBy           = 'pr.player_id, pr.season';

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions2[] = "(vamc.date {$dates['startDate']} AND vamc.date {$dates['endDate']})";
            $whereConditions[]  = "pr.season = {$season}";
        }

        $data = array('competition_type' => "'overall'",
            'season' => $season,
            'where_conditions' => implode(" \r\n\tAND ", $whereConditions),
            'where_conditions_2' => implode(" \r\nAND ", $whereConditions2),
            'group_by' => $groupBy);

        $sql =  self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Delete cache for every Players' overall (aggregated) statistics, either for a career or a particular season
     * @param  int|NULL $season     Four digit number for season, or NULL for Career
     * @return boolean              Whether query was executed correctly
     */
    public function deleteAllOverallStatistics($season = NULL)
    {
        $season = !$season ? 'career' : (int) $season;

        return $this->db->delete($this->tableName, array('type' => 'overall',
            'season' => $season));
    }

    /**
     * Generate cache of a single Player's statistics, by type, either for a career or a particular season
     * @param  int $playerId        Player's ID
     * @param  int|NULL $season     Four digit number for season, or NULL for Career
     * @return boolean              Whether query was executed correctly
     */
    public function singleStatisticsByType($playerId, $season = NULL)
    {
        self::deleteSingleStatisticsByType($playerId, $season);

        $whereConditions    = array();
        $whereConditions2   = array();
        $whereConditions[]  = "pr.player_id = {$playerId}";
        $whereConditions[]  = "pr.deleted = 0";
        $groupBy            = 'vamc.competition_type, pr.season';

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions2[] = "(vamc.date {$dates['startDate']} AND vamc.date {$dates['endDate']})";
            $whereConditions[]  = "pr.season = {$season}";
        }

        $data = array('competition_type' => "vamc.competition_type",
            'season' => $season,
            'where_conditions' => implode(" \r\n\tAND ", $whereConditions),
            'where_conditions_2' => implode(" \r\nAND ", $whereConditions2),
            'group_by' => $groupBy);

        $sql =  self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Delete cache of a single Player's statistics, by type, either for a career or a particular season
     * @param  int $playerId        Player's ID
     * @param  int|NULL $season     Four digit number for season, or NULL for Career
     * @return boolean              Whether query was executed correctly
     */
    public function deleteSingleStatisticsByType($playerId, $season = NULL)
    {
        $season = !$season ? 'career' : (int) $season;

        return $this->db->delete($this->tableName, array('player_id' => $playerId,
            'type !=' => 'overall',
            'season' => $season));
    }

    /**
     * Generate cache of every Players' statistics, by type, either for a career or a particular season
     * @param  int|NULL $season     Four digit number for season, or NULL for Career
     * @return boolean              Whether query was executed correctly
     */
    public function allStatisticsByType($season = NULL)
    {
        self::deleteAllStatisticsByType($season);

        $whereConditions   = array();
        $whereConditions2  = array();
        $whereConditions[] = "pr.deleted = 0";
        $groupBy           = 'pr.player_id, pr.season, vamc.competition_type';

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions2[] = "(vamc.date {$dates['startDate']} AND vamc.date {$dates['endDate']})";
            $whereConditions[]  = "pr.season = {$season}";
        }

        $data = array('competition_type' => "vamc.competition_type",
            'season' => $season,
            'where_conditions'   => implode(" \r\n\tAND ", $whereConditions),
            'where_conditions_2' => implode(" \r\nAND ", $whereConditions2),
            'group_by' => $groupBy);

        $sql =  self::insertSQLValues($data);

        return $this->db->simple_query($sql);
    }

    /**
     * Delete cache of every Players' statistics, by type, either for a career or a particular season
     * @param  int|NULL $season     Four digit number for season, or NULL for Career
     * @return boolean              Whether query was executed correctly
     */
    public function deleteAllStatisticsByType($season = NULL)
    {
        $season = !$season ? 'career' : (int) $season;

        return $this->db->delete($this->tableName, array('type !=' => 'overall',
            'season' => $season));
    }

    /**
     * Generate all statistics
     * @return boolean Whether query was executed correctly
     */
    public function generateAllStatistics()
    {
        $this->allOverallStatistics();
        $this->allStatisticsByType();

        $season = $this->ci->Season_model->fetchEarliestYear();
        while($season <= Season_model::fetchCurrentSeason()){
            $this->allOverallStatistics($season);
            $this->allStatisticsByType($season);
            $season++;
        }

        return true;
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