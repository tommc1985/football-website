<?php
require_once('_base_model.php');

/**
 * Model for Competition data
 */
class Competition_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'competition';

        $this->ci->load->model('League_model');
        $this->ci->load->model('Match_model');
    }

    /**
     * Insert a Competition from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'name' => $this->ci->form_validation->set_value('name', NULL),
            'short_name' => $this->ci->form_validation->set_value('short_name', NULL),
            'abbreviation' => $this->ci->form_validation->set_value('abbreviation', NULL),
            'type' => $this->ci->form_validation->set_value('type', NULL),
            'starts' => $this->ci->form_validation->set_value('starts', NULL),
            'subs' => $this->ci->form_validation->set_value('subs', NULL),
            'competitive' => $this->ci->form_validation->set_value('competitive', NULL),
        ));
    }

    /**
     * Update a Competition from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'name' => $this->ci->form_validation->set_value('name', NULL),
            'short_name' => $this->ci->form_validation->set_value('short_name', NULL),
            'abbreviation' => $this->ci->form_validation->set_value('abbreviation', NULL),
            'type' => $this->ci->form_validation->set_value('type', NULL),
            'starts' => $this->ci->form_validation->set_value('starts', NULL),
            'subs' => $this->ci->form_validation->set_value('subs', NULL),
            'competitive' => $this->ci->form_validation->set_value('competitive', NULL),
        ));
    }

    /**
     * Fetch list of Types
     * @return array List of competition types
     */
    public static function fetchTypes()
    {
        return array(
            'friendly' => 'Friendly',
            'league' => 'League',
            'cup' => 'Cup',
            'europe' => 'Europe',
            'other' => 'Other',
        );
    }

    /**
     * Fetch list of Competitive/Non-Competitive options
     * @return array List of Competitive/Non-Competitive options
     */
    public static function fetchCompetitive()
    {
        return array(
            '1' => 'Competitive',
            '0' => 'Non-Competitive',
        );
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        switch ($orderBy) {
            case 'type':
                return 'type, name';
                break;
            case 'abbreviation':
                return 'abbreviation';
                break;
        }

        return 'name';
    }

    /**
     * Apply Form Validation for Adding & Updating Competitions
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('short_name', 'Short Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('abbreviation', 'Abbreviation', "trim|required|regex_match[/^[A-Za-z0-9']+$/]|max_length[" . $this->config->item('abbreviation_max_length', 'competition') . "]|strtoupper|xss_clean");
        $this->ci->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('starts', 'Starts', 'trim|is_natural_no_zero|xss_clean');
        $this->ci->form_validation->set_rules('subs', 'Substitutes', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('competitive', 'Competitive', 'trim|integer|xss_clean');
    }

    /**
     * Fetch All Competitions and format for dropdown
     * @return array List of competitions
     */
    public function fetchForDropdown()
    {
        $results = $this->fetchAll(false, false, 'name', 'asc');

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = $result->short_name;
        }

        return $dropdownOptions;
    }

    /**
     * Can the Competition be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified Competition be deleted?
     */
    public function isDeletable($id)
    {
        $leagues = $this->ci->League_model->fetchAllByField('competition_id', $id);
        $matches = $this->ci->Match_model->fetchAllByField('competition_id', $id);

        if ($leagues || $matches) {
            return false;
        }

        return true;
    }

}