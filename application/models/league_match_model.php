<?php
require_once('_base_model.php');

/**
 * Model for League Match data
 */
class League_Match_model extends Base_Model {

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
     * Fetch list of Status options
     * @return array List of Status options
     */
    public static function fetchStatuses()
    {
        return array(
            'hw' => 'Home Win',
            'aw' => 'Away Win',
            'p'  => 'Postponed',
            'a'  => 'Abandoned',
        );
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        return 'date';
    }

    /**
     * Apply Form Validation for Adding & Updating League Matches
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('league_id', 'League', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('date', 'Date', 'trim|required||regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]xss_clean');
        $this->ci->form_validation->set_rules('h_opposition_id', 'Home Team', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('a_opposition_id', 'Away Team', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('h_score', 'Home Score', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('a_score', 'Away Score', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('status', 'Status', 'trim|xss_clean');
    }

    /**
     * Fetch all matches from a particular League
     * @param  int $leagueId         League ID
     * @param  int|false $limit      Number of rows to return
     * @param  int|false $offset     The offset
     * @param  string|false $orderBy Which fields to order results by
     * @param  string|false $order   Order the results Ascending or Descending
     * @return array                 Returned rows
     */
    public function fetchByLeagueId($leagueId, $limit = false, $offset = false, $orderBy = false, $order = false)
    {
        $orderBy = $this->getOrderBy($orderBy);
        $order   = self::getOrder($order);

        $this->db->select('*');
        $this->db->from($this->tableName);
        $this->db->where('league_id', $leagueId);
        $this->db->where('deleted', 0);

        if ($limit !== false) {
            if ($offset !== false) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }

        $this->db->order_by($orderBy, $order);

        return $this->db->get()->result();
    }

}