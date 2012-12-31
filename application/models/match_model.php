<?php
require_once('_base_model.php');

/**
 * Model for Match data
 */
class Match_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'matches';
    }

    /**
     * Fetch the earliest matches stored in the system
     * @param  integer $limit Number of matches to return
     * @return array|object   Earliest match/matches
     */
    public function fetchEarliest($limit = 1)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('deleted', 0)
            ->order_by('date', 'asc')
            ->limit($limit, 0);

        $result = $this->db->get()->result();

        if ($limit == 1) {
            return $result[0];
        }

        return $result;
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
     * Apply Form Validation for Adding & Updating Matches
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('opposition_id', 'Opposition', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('competition_id', 'Competition', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('competition_stage_id', 'Competition Stage', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('venue', 'Venue', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('location', 'Location', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('official_id', 'Official', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('h', 'Your Score', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('a', 'Opposition Score', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('report', 'Report', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('date', 'Date', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('h_et', 'Your Goals After 90 mins (If Extra Time is played)', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('a_et', 'Opposition Goals After 90 mins (If Extra Time is played)', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('h_pen', 'Your Score Penalties', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('a_pen', 'Opposition Score Penalties', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('status', 'Status', 'trim|xss_clean');
    }

}