<?php
require_once('_base_frontend_model.php');

/**
 * Model for League Match Page
 */
class League_Match_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'league_match';
    }

    /**
     * Fetch all matches on the specified date
     * @param  int $leagueId     League ID
     * @param  mixed $date       Specified Date
     * @return array             Returned Matches
     */
    public function fetchByDate($leagueId, $date = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->tableName);

        $this->db->where('league_id', $leagueId);

        if (!is_null($date)) {
            $this->db->where('DATE(date)', $date);
        }
        $this->db->where('deleted', 0);
        $this->db->order_by('date', 'asc');

        return $this->db->get()->result();
    }

    /**
     * Return date of next set of matches
     * @param  int $leagueId     League ID
     * @return string            Date
     */
    public function fetchNextDate($leagueId)
    {
        $this->db->select('DISTINCT(DATE(date)) as distinct_date');
        $this->db->from($this->tableName);

        $this->db->where('h_score', NULL);
        $this->db->where('status', NULL);
        $this->db->where('league_id', $leagueId);
        $this->db->where('deleted', 0);
        $this->db->order_by('distinct_date', 'asc');
        $this->db->limit(1);

        $result = $this->db->get()->result();

        if (count($result) == 1) {
            return $result[0]->distinct_date;
        }

        return false;
    }

    /**
     * Return date of last set of completed matches
     * @param  int $leagueId     League ID
     * @return string            Date
     */
    public function fetchLastDate($leagueId)
    {
        $this->db->select('DISTINCT(DATE(date)) as distinct_date');
        $this->db->from($this->tableName);

        $this->db->where('(h_score IS NOT NULL || status IS NOT NULL)');
        $this->db->where('league_id', $leagueId);
        $this->db->where('deleted', 0);
        $this->db->order_by('distinct_date', 'desc');
        $this->db->limit(1);

        $result = $this->db->get()->result();

        if (count($result) == 1) {
            return $result[0]->distinct_date;
        }

        return false;
    }

    /**
     * Return array of distinct dates for the specified League
     * @param  int $leagueId     League ID
     * @return array             Distinct Dates
     */
    public function fetchDistinctDates($leagueId)
    {
        $this->db->select('DISTINCT(DATE(date)) as distinct_date');
        $this->db->from($this->tableName);

        $this->db->where('league_id', $leagueId);
        $this->db->where('deleted', 0);
        $this->db->order_by('distinct_date', 'desc');

        return $this->db->get()->result();
    }

    /**
     * Return array of dates for Dropdown
     * @param  int $leagueId     League ID
     * @return array             Formatted Data
     */
    public function fetchDatesForDropdown($leagueId)
    {
        $dates = $this->fetchDistinctDates($leagueId);

        $options = array();
        foreach ($dates as $date) {
            $options[$date->distinct_date] = Utility_helper::shortDate($date->distinct_date);
        }

        return $options;
    }

    /**
     * Return string of fields to order data by
     * @param  string $orderBy Fields passed
     * @return string          Processed string of fields
     */
    public function getOrderBy($orderBy)
    {
        return 'date';
    }

    /**
     * Return "asc" or "desc" depending on value passed
     * @param  string $order Either "asc" or "desc"
     * @return string        Either "asc" or "desc"
     */
    public function getOrder($order)
    {
        if ($order == 'desc') {
            return 'desc';
        }

        return 'asc';
    }

}