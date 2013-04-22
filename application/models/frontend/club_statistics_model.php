<?php
require_once('_base_frontend_model.php');

/**
 * Model for Club Statistics
 */
class Club_Statistics_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'cache_club_statistics';
    }

    /**
     * Fetch all Club Statistics
     * @param  string           Season of Statistics
     * @param  string           Type of Statistics
     * @return array            Returned Statistics
     */
    public function fetchAll($season, $type)
    {
        $this->db->select('*');
        $this->db->from($this->tableName);

        $this->db->where('season', $season);
        $this->db->where('type', $type);

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