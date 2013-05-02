<?php
require_once('_base_frontend_model.php');

/**
 * Model for Player Statistics
 */
class Player_Statistics_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'cache_player_statistics';
    }

    /**
     * Fetch all Player Statistics
     * @param  string           Season of Statistics
     * @param  string           Type of Statistics
     * @return array            Returned Statistics
     */
    public function fetchAll($season, $type, $player_id = false)
    {
        $this->db->select('*');
        $this->db->from($this->tableName);

        $this->db->where('season', $season);
        $this->db->where('type', $type);
        $this->db->order_by('CAST(`statistic_key` AS SIGNED)', 'desc');

        $result = $this->db->get();

        $statistics = array();

        if ($result) {
            foreach ($result->result() as $statistic) {
                if (!isset($statistics[$statistic->statistic_group])) {
                    $statistics[$statistic->statistic_group] = array();
                }
                $data = unserialize($statistic->statistic_value);
                $statistics[$statistic->statistic_group][] = $data === false ? $statistic->statistic_key : $data;
            }
        }

        return $statistics;
    }

}