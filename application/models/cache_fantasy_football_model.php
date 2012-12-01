<?php
class Cache_Fantasy_Football_model extends CI_Model {

    /**
     * CodeIgniter instance
     * @var object
     */
    public $ci;

    public $tableName;
    public $queueTableName;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
        $this->ci->load->model('Season_model');

        $this->tableName = 'cache_fantasy_football_statistics';
        $this->queueTableName = 'cache_queue_fantasy_football_statistics';
    }

    /**
     * Insert row into process queue table to be processed
     * @param  int|NULL $season     Season "career"
     * @return boolean
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
     * @param  int|NULL $byType   Group by "type" or "overall"
     * @param  int|NULL $season   Season "career"
     * @return boolean
     */
    public function insertEntry($byType = NULL, $season = NULL)
    {
        $data = array('by_type' => $byType,
            'season' => $season,
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
     * @param  int     $limit  Number of rows to return
     * @return results Query Object
     */
    public function fetchLatest($limit = 2)
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

        // Flag the row is being processed
        $row->in_progress = 1;
        $this->updateEntry($row);

        $this->generateStatistics(false, $row->season);

        if (!is_null($row->by_type)) { // Generate all statistics
            $competitionTypes = $this->ci->Season_model->fetchCompetitionTypes();

            foreach ($competitionTypes as $competitionType) {
                $this->generateStatistics($competitionType, $row->season);
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
     * Fetch matches
     * @param  boolean  $type     Competition Type
     * @param  int|NULL $season   Season or Career
     * @return array List of matches
     */
    public function fetchMatches($type = false, $season = NULL)
    {
        $whereConditions = array();

        $whereConditions[] = "(vamc.competitive = '1')";
        $whereConditions[] = "(vamc.status != 'unused')";

        if (!is_null($season)) {
            $dates = Season_model::generateStartEndDates($season);
            $whereConditions[] = "(vamc.date {$dates['startDate']} AND vamc.date {$dates['endDate']})";
        }

        if (is_string($type)) {
            $whereConditions[] = "(vamc.competition_type = '{$type}')";
        }

        $sql = "SELECT vamc.*
FROM view_appearances_matches_combined vamc" . (count($whereConditions) > 0 ? "
WHERE " . implode(" \r\nAND ", $whereConditions) : '');

        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Create Fantasy Football Statistics object
     * @param  int  $playerId     Unique Player Id
     * @param  boolean  $type     Competition Type
     * @param  int|NULL $season   Season or Career
     * @return object Fantasy Football Statistics object
     */
    public function createObject($playerId, $type = false, $season = NULL)
    {
        $player = new stdClass();

        $player->player_id = $playerId;
        $player->type      = 'overall';
        $player->season    = 'career';

        if (is_string($type)) {
            $player->type = $type;
        }

        if (!is_null($season)) {
            $player->season = $season;
        }

        $player->starter_appearances_points = 0;
        $player->substitute_appearances_points = 0;

        $player->clean_sheets_by_goalkeeper_points = 0;
        $player->clean_sheets_by_defender_points = 0;
        $player->clean_sheets_by_midfielder_points = 0;

        $player->assists_by_goalkeeper_points = 0;
        $player->assists_by_defender_points = 0;
        $player->assists_by_midfielder_points = 0;
        $player->assists_by_striker_points = 0;

        $player->goals_by_goalkeeper_points = 0;
        $player->goals_by_defender_points = 0;
        $player->goals_by_midfielder_points = 0;
        $player->goals_by_striker_points = 0;

        $player->man_of_the_match_points = 0;
        $player->yellow_card_points = 0;
        $player->red_card_points = 0;

        $player->appearances = 0;
        $player->total_points = 0;
        $player->points_per_game = 0;

        return $player;
    }

    /**
     * Add points to Player object from Appearance object
     * @param  object   $player     Player Object
     * @param  object   $appearance Appearance Object
     * @return object Fantasy Football Statistics object
     */
    public function calculatePointsFromAppearance($player, $appearance)
    {
        if ($appearance->status == 'starter') {
            $player->starter_appearances_points += Configuration::get('starting_appearance_points');
            $player->total_points += Configuration::get('starting_appearance_points');
        }

        if ($appearance->status == 'substitute') {
            $player->substitute_appearances_points += Configuration::get('substitute_appearance_points');
            $player->total_points += Configuration::get('substitute_appearance_points');
        }

        if ($appearance->a == 0) {
            switch (true) {
                case $appearance->position == 1:
                    $player->clean_sheets_by_goalkeeper_points += Configuration::get('clean_sheet_by_goalkeeper_points');
                    $player->total_points += Configuration::get('clean_sheet_by_goalkeeper_points');
                    break;
                case in_array($appearance->position, array(2, 3, 4, 5, 6, 7)):
                    $player->clean_sheets_by_defender_points += Configuration::get('clean_sheet_by_defender_points');
                    $player->total_points += Configuration::get('clean_sheet_by_defender_points');
                    break;
                case in_array($appearance->position, array(8 ,9, 10, 11, 12, 13, 14)):
                    $player->clean_sheets_by_midfielder_points += Configuration::get('clean_sheet_by_midfielder_points');
                    $player->total_points += Configuration::get('clean_sheet_by_midfielder_points');
                    break;
            }
        }

        if ($appearance->assists > 0) {
            switch (true) {
                case $appearance->position == 1:
                    $player->assists_by_goalkeeper_points += (Configuration::get('assist_by_goalkeeper_points') * $appearance->assists);
                    $player->total_points += (Configuration::get('assist_by_goalkeeper_points') * $appearance->assists);
                    break;
                case in_array($appearance->position, array(2, 3, 4, 5, 6, 7)):
                    $player->assists_by_defender_points += (Configuration::get('assist_by_defender_points') * $appearance->assists);
                    $player->total_points += (Configuration::get('assist_by_defender_points') * $appearance->assists);
                    break;
                case in_array($appearance->position, array(8 ,9, 10, 11, 12, 13, 14)):
                    $player->assists_by_midfielder_points += (Configuration::get('assist_by_midfielder_points') * $appearance->assists);
                    $player->total_points += (Configuration::get('assist_by_midfielder_points') * $appearance->assists);
                    break;
                case in_array($appearance->position, array(15, 16)):
                    $player->assists_by_striker_points += (Configuration::get('assist_by_striker_points') * $appearance->assists);
                    $player->total_points += (Configuration::get('assist_by_striker_points') * $appearance->assists);
                    break;
            }
        }

        if ($appearance->goals > 0) {
            switch (true) {
                case $appearance->position == 1:
                    $player->goals_by_goalkeeper_points += (Configuration::get('goal_by_goalkeeper_points') * $appearance->goals);
                    $player->total_points += (Configuration::get('goal_by_goalkeeper_points') * $appearance->goals);
                    break;
                case in_array($appearance->position, array(2, 3, 4, 5, 6, 7)):
                    $player->goals_by_defender_points += (Configuration::get('goal_by_defender_points') * $appearance->goals);
                    $player->total_points += (Configuration::get('goal_by_defender_points') * $appearance->goals);
                    break;
                case in_array($appearance->position, array(8 ,9, 10, 11, 12, 13, 14)):
                    $player->goals_by_midfielder_points += (Configuration::get('goal_by_midfielder_points') * $appearance->goals);
                    $player->total_points += (Configuration::get('goal_by_midfielder_points') * $appearance->goals);
                    break;
                case in_array($appearance->position, array(15, 16)):
                    $player->goals_by_striker_points += (Configuration::get('goal_by_striker_points') * $appearance->goals);
                    $player->total_points += (Configuration::get('goal_by_striker_points') * $appearance->goals);
                    break;
            }
        }

        if ($appearance->motm == 1) {
            $player->man_of_the_match_points += Configuration::get('man_of_the_match_points');
            $player->total_points += Configuration::get('man_of_the_match_points');
        }

        if ($appearance->yellows == 1) {
            $player->yellow_card_points += Configuration::get('yellow_card_points');
            $player->total_points += Configuration::get('yellow_card_points');
        }

        if ($appearance->reds == 1) {
            $player->red_card_points += Configuration::get('red_card_points');
            $player->total_points += Configuration::get('red_card_points');
        }

        $player->appearances++;
        $player->points_per_game = $player->total_points / $player->appearances;

        return $player;
    }

    /**
     * Generate statistics
     * @return boolean Whether query was executed correctly
     */
    public function generateStatistics($type = false, $season = NULL)
    {
        $this->deleteStatistics($type, $season);

        $appearances = $this->fetchMatches($type, $season);

        $players = array();

        foreach ($appearances as $appearance) {
            if (!isset($players[$appearance->player_id])) {
                $players[$appearance->player_id] = $this->createObject($appearance->player_id, $type, $season);
            }

            $players[$appearance->player_id] = $this->calculatePointsFromAppearance($players[$appearance->player_id], $appearance);
        }

        foreach ($players as $player) {
            $this->db->insert($this->tableName, $player);
        }
    }

    /**
     * Delete statistics
     * @param  boolean  $type     Competition Type
     * @param  int|NULL $season   Season or Career
     * @return boolean            Were rows deleted
     */
    public function deleteStatistics($type = false, $season = NULL)
    {
        return $this->deleteRows($type, $season);
    }

    /**
     * Particular Player Statistics, based on Season and/or Competition Type
     * @param  boolean  $byType     Generate by competition type, set to false for "overall"
     * @param  int|NULL $season     Season to generate, set to null for entire career
     * @return boolean              Whether query was executed correctly
     */
    public function deleteRows($type = false, $season = NULL)
    {
        $whereConditions = array();

        $whereConditions['season']          = $season ? $season : 'career';

        if ($type) {
            $whereConditions['type']        = $type;
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