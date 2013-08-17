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
     * @param  int              Matches Played Threshold
     * @return array            Returned Statistics
     */
    public function fetchAll($season, $type, $threshold)
    {
        $this->db->select('*');
        $this->db->from($this->tableName);

        $this->db->where('season', $season);
        $this->db->where('type', $type);
        $this->db->where("(matches_played >= {$threshold} || matches_played IS NULL)", NULL, false);
        $this->db->order_by('CAST(`statistic_key` AS DECIMAL(5, 2)) DESC, matches_played ASC', null, false);

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

    /**
     * Fetch Threshold values for dropdown
     * @return array List of threshold values
     */
    public static function fetchThresholdsForDropdown($maxValue)
    {
        $i = 1;
        $options = array();

        do {
            $options[$i] = $i;

            $i++;
        } while ($i <= $maxValue);

        $options = array_reverse($options, true);

        return $options;
    }

}