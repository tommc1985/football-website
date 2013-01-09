<?php
require_once('_base_model.php');

/**
 * Model for Competition Stage data
 */
class Competition_Stage_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'competition_stage';

        if (class_exists('Match_model')) {
            $this->ci->load->model('Match_model');
        }
    }

    /**
     * Insert a Competition Stage from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'name' => $this->ci->form_validation->set_value('name', NULL),
            'abbreviation' => $this->ci->form_validation->set_value('abbreviation', NULL),
        ));
    }

    /**
     * Update a Competition Stage from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'name' => $this->ci->form_validation->set_value('name', NULL),
            'abbreviation' => $this->ci->form_validation->set_value('abbreviation', NULL),
        ));
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        switch ($orderBy) {
            case 'abbreviation':
                return 'abbreviation';
                break;
        }

        return 'name';
    }

    /**
     * Apply Form Validation for Adding & Updating Competition Stages
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('abbreviation', 'Abbreviation', "trim|required|max_length[" . $this->config->item('abbreviation_max_length', 'competition_stage') . "]|regex_match[/^[A-Za-z0-9']+$/]|strtoupper|xss_clean");
    }

    /**
     * Fetch All Competition Stages and format for dropdown
     * @return array List of Competition Stages
     */
    public function fetchForDropdown()
    {
        $results = $this->fetchAll(false, false, 'name', 'asc');

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = $result->name;
        }

        return $dropdownOptions;
    }

    /**
     * Can the Competition Stage be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified Competition Stage be deleted?
     */
    public function isDeletable($id)
    {
        $matches = $this->ci->Match_model->fetchAllByField('competition_stage_id', $id);

        if ($matches) {
            return false;
        }

        return true;
    }

}