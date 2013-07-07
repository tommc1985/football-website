<?php
require_once('_base_frontend_model.php');

/**
 * Model for League Statistics
 */
class League_Statistics_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'cache_league_statistics';
    }

    /**
     * Fetch all League Statistics
     * @param  int $id          League ID
     * @return array            Returned Statistics
     */
    public function fetchAll($id)
    {
        $this->db->select('*');
        $this->db->from($this->tableName);

        $this->db->where('league_id', $id);

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