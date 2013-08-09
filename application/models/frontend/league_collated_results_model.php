<?php
require_once('_base_frontend_model.php');

/**
 * Model for League Collated Results
 */
class League_Collated_Results_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'cache_league_results_collated';
    }

    /**
     * Fetch the standings for a particular league,
     * @param  int $id            League ID
     * @param  string $dateUntil  The date to include, or 'overall' for the current standings
     * @param  string $type       Home, Away or overall)
     * @return array              Ordered list of standings
     */
    public function fetchStandings($id, $dateUntil = 'overall', $type = 'overall')
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('league_id', $id)
            ->where('date_until', $dateUntil)
            ->where('type', $type)
            ->order_by($this->getOrderBy(), '');

        $results = $this->db->get()->result();

        $data = array();

        foreach ($results as $result) {
            $data[$result->opposition_id] = $result;
        }

        return $data;
    }

    /**
     * Fetch the form for a particular league,
     * @param  array $standings      League Standings
     * @param  int $numberOfMatches  Number of matches to include in form
     * @return array                 Ordered list of form
     */
    public function fetchForm($standings, $numberOfMatches)
    {
        $form = array();
        foreach ($standings as $standing) {
            $form[$standing->opposition_id] = League_helper::calculateFormPoints($standing->form, $numberOfMatches);
        }

        arsort($form);

        return $form;
    }

    /**
     * Return maximum number of matches played by a team this season
     * @param  int $leagueId      League ID
     * @param  string $dateUntil  Date Until
     * @return array              Distinct Dates
     */
    public function fetchMaxMatchCount($leagueId, $dateUntil)
    {
        $this->db->select('MAX(played) as played');
        $this->db->from($this->tableName);

        $this->db->where('league_id', $leagueId);
        $this->db->where('date_until', $dateUntil);

        $result = $this->db->get()->result();

        if (count($result) == 1) {
            return $result[0]->played;
        }

        return 0;
    }

    /**
     * Return array of match count for Dropdown
     * @param  int $leagueId      League ID
     * @param  string $dateUntil  Date Until
     * @return array              Formatted Data
     */
    public function fetchMaxCountFormDropdown($leagueId, $dateUntil)
    {
        $max = $this->fetchMaxMatchCount($leagueId, $dateUntil);

        $options = array();
        $i = 1;
        do {
            $options[$i] = $i;

            $i++;
        } while ($i <= $max);

        return $options;
    }

    /**
     * Return string of fields to order data by
     * @param  string $orderBy Fields passed
     * @return string          Processed string of fields
     */
    public function getOrderBy($orderBy = '')
    {
        switch ($orderBy) {
            case 'form':
                return 'form DESC';
        }

        return 'points DESC, gd DESC, gf DESC';
    }

    /**
     * Return "asc" or "desc" depending on value passed
     * @param  string $order Either "asc" or "desc"
     * @return string        Either "asc" or "desc"
     */
    public function getOrder($order)
    {
        return '';
    }

}