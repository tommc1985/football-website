<?php
require_once('_base_model.php');

/**
 * Model for Appearance data
 */
class Appearance_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'appearance';
    }

    /**
     * Insert a Match from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'opposition_id' => $this->ci->form_validation->set_value('opposition_id', NULL),
            'competition_id' => $this->ci->form_validation->set_value('competition_id', NULL),
            'competition_stage_id' => $this->ci->form_validation->set_value('competition_stage_id', NULL),
            'venue' => $this->ci->form_validation->set_value('venue', NULL),
            'location' => $this->ci->form_validation->set_value('location', NULL),
            'official_id' => $this->ci->form_validation->set_value('official_id', NULL),
            'h' => $this->ci->form_validation->set_value('h', NULL),
            'a' => $this->ci->form_validation->set_value('a', NULL),
            'report' => $this->ci->form_validation->set_value('report', NULL),
            'date' => $this->ci->form_validation->set_value('date', NULL) . ' ' . $this->ci->form_validation->set_value('time', NULL) . ':00',
            'h_et' => $this->ci->form_validation->set_value('h_et', NULL),
            'a_et' => $this->ci->form_validation->set_value('a_et', NULL),
            'h_pen' => $this->ci->form_validation->set_value('h_pen', NULL),
            'a_pen' => $this->ci->form_validation->set_value('a_pen', NULL),
            'status' => $this->ci->form_validation->set_value('status', NULL),
        ));
    }

    /**
     * Update a Match from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'opposition_id' => $this->ci->form_validation->set_value('opposition_id', NULL),
            'competition_id' => $this->ci->form_validation->set_value('competition_id', NULL),
            'competition_stage_id' => $this->ci->form_validation->set_value('competition_stage_id', NULL),
            'venue' => $this->ci->form_validation->set_value('venue', NULL),
            'location' => $this->ci->form_validation->set_value('location', NULL),
            'official_id' => $this->ci->form_validation->set_value('official_id', NULL),
            'h' => $this->ci->form_validation->set_value('h', NULL),
            'a' => $this->ci->form_validation->set_value('a', NULL),
            'report' => $this->ci->form_validation->set_value('report', NULL),
            'date' => $this->ci->form_validation->set_value('date', NULL) . ' ' . $this->ci->form_validation->set_value('time', NULL) . ':00',
            'h_et' => $this->ci->form_validation->set_value('h_et', NULL),
            'a_et' => $this->ci->form_validation->set_value('a_et', NULL),
            'h_pen' => $this->ci->form_validation->set_value('h_pen', NULL),
            'a_pen' => $this->ci->form_validation->set_value('a_pen', NULL),
            'status' => $this->ci->form_validation->set_value('status', NULL),
        ));
    }

    /**
     * Fetch appearance data for a particular match
     * @param  int $matchId  The ID for the specified Match
     * @return object|false  The object (or false if not found)
     */
    public function fetch($matchId)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('match_id', $matchId)
            ->where('deleted', 0)
            ->order_by('order');

        $result = $this->db->get()->result();

        $appearances = array(
            'starts' => array(),
            'subs' => array());

        foreach ($result as $appearance) {
            switch ($appearance->status) {
                case 'starter':
                    $appearances['starts'][] = $appearance;
                    break;
                case 'substitute':
                case 'unused':
                    $appearances['subs'][] = $appearance;
                    break;
            }
        }

        return $appearances;
    }

    /**
     * Apply Form Validation for Adding & Updating Appearance Data
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('opposition_id', 'Opposition', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('competition_id', 'Competition', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('competition_stage_id', 'Competition Stage', 'trim|integer|xss_clean');
        $this->ci->form_validation->set_rules('venue', 'Venue', "trim|required|regex_match[/^(h)|(a)|(n)$/|xss_clean");
        $this->ci->form_validation->set_rules('location', 'Location', "trim||max_length[" . $this->config->item('location_max_length', 'match') . "]xss_clean");
        $this->ci->form_validation->set_rules('official_id', 'Official', 'trim|integer|xss_clean');
        $this->ci->form_validation->set_rules('h', 'Your Score', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('a', 'Opposition Score', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('report', 'Report', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('date', 'Date', 'trim|required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|xss_clean');
        $this->ci->form_validation->set_rules('time', 'Time', 'trim|required|regex_match[/^[0-9]{2}:[0-9]{2}$/]|xss_clean');
        $this->ci->form_validation->set_rules('h_et', 'Your Goals After 90 mins (If Extra Time is played)', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('a_et', 'Opposition Goals After 90 mins (If Extra Time is played)', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('h_pen', 'Your Score Penalties', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('a_pen', 'Opposition Score Penalties', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('status', 'Status', 'trim|regex_match[/^(hw)|(aw)|(p)|(a)$/]|xss_clean');
    }

}