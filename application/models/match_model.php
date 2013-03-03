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
     * Fetch list of Venue options
     * @return array List of Venue options
     */
    public static function fetchVenues()
    {
        return array(
            'h' => 'Home',
            'a' => 'Away',
            'n'  => 'Neutral',
        );
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
     * Apply Form Validation for Adding & Updating Matches
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('opposition_id', 'Opposition', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('competition_id', 'Competition', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('competition_stage_id', 'Competition Stage', 'trim|integer|xss_clean');
        $this->ci->form_validation->set_rules('venue', 'Venue', "trim|required|regex_match[/^(h)|(a)|(n)$/|xss_clean");
        $this->ci->form_validation->set_rules('location', 'Location', "trim|max_length[" . $this->config->item('location_max_length', 'match') . "]xss_clean");
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

    /**
     * Can the Match be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified Match be deleted?
     */
    public function isDeletable($id)
    {
        $ci =& get_instance();
        $ci->load->model('Appearance_model');
        $ci->load->model('Card_model');
        $ci->load->model('Goal_model');

        $appearances = $ci->Appearance_model->fetchAllByField('match_id', $id);
        $cards = $ci->Card_model->fetchAllByField('match_id', $id);
        $goals = $ci->Goal_model->fetchAllByField('match_id', $id);

        if ($appearances || $cards || $goals) {
            return false;
        }

        return true;
    }

    /**
     * Fetch the earliest match stored in the system
     * @return object         Earliest match
     */
    public function fetchEarliest()
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('deleted', 0)
            ->order_by('date', 'asc')
            ->limit(1, 0);

        $matches = $this->db->get()->result();

        if (count($matches) > 0) {
            return reset($matches);
        }

        return false;
    }

    /**
     * Fetch minutes for dropdown
     * @return array List of minutes
     */
    public static function fetchMinutes()
    {
        $i = 1;
        $minutes = array();

        while ($i <= Configuration::get('max_minute')) {
            $minutes[$i] = $i;

            $i++;
        }

        return $minutes;
    }

}