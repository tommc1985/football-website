<?php
require_once('_base_model.php');

/**
 * Model for Award data
 */
class Award_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'award';
    }

    /**
     * Insert an Award from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'long_name' => $this->ci->form_validation->set_value('long_name', NULL),
            'short_name' => $this->ci->form_validation->set_value('short_name', NULL),
            'importance' => $this->ci->form_validation->set_value('importance', NULL),
        ));
    }

    /**
     * Update an Award from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'long_name' => $this->ci->form_validation->set_value('long_name', NULL),
            'short_name' => $this->ci->form_validation->set_value('short_name', NULL),
            'importance' => $this->ci->form_validation->set_value('importance', NULL),
        ));
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        return 'importance';
    }

    /**
     * Return "asc" or "desc" depending on value passed
     * @param  string $order Either "asc" or "desc"
     * @return string        Either "asc" or "desc"
     */
    public function getOrder($order)
    {
        return 'desc';
    }

    /**
     * Apply Form Validation for Adding & Updating Awards
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('long_name', 'Long Name', "trim|required|regex_match[/^[A-Za-z -']+$/]|max_length[" . $this->config->item('long_name_max_length', 'award') . "]|xss_clean");
        $this->ci->form_validation->set_rules('short_name', 'Short Name', "trim|required|regex_match[/^[A-Za-z -']+$/]|max_length[" . $this->config->item('short_name_max_length', 'award') . "]|xss_clean");
        $this->ci->form_validation->set_rules('importance', 'Importance', "trim|required|greater_than[" . $this->config->item('importance_min_value', 'award') . "]|less_than[" . $this->config->item('importance_max_value', 'award') . "]|xss_clean");
    }

    /**
     * Fetch all Awards and format for dropdown
     * @return array List of Positions
     */
    public function fetchForDropdown()
    {
        $results = $this->fetchAll();

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = $result->long_name;
        }

        return $dropdownOptions;
    }

    /**
     * Can the Award be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean     Can the specified Award be deleted?
     */
    public function isDeletable($id)
    {
        $ci =& get_instance();
        $ci->load->model('Player_To_Award_model');

        $playerAwards = $ci->Player_To_Award_model->fetchAllByField('award_id', $id);

        if ($playerAwards) {
            return false;
        }

        return true;
    }

}