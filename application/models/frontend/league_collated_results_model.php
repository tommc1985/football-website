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