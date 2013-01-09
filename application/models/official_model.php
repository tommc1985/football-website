<?php
require_once('_base_model.php');

/**
 * Model for Official data
 */
class Official_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'official';
    }

    /**
     * Insert a Official from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'first_name' => $this->ci->form_validation->set_value('first_name', NULL),
            'surname' => $this->ci->form_validation->set_value('surname', NULL),
        ));
    }

    /**
     * Update a Official from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'first_name' => $this->ci->form_validation->set_value('first_name', NULL),
            'surname' => $this->ci->form_validation->set_value('surname', NULL),
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
            case 'firstname':
                return 'first_name, surname';
                break;
            case 'dob':
                return 'dob';
                break;
        }

        return 'surname, first_name';
    }

    /**
     * Apply Form Validation for Adding & Updating Officials
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('first_name', 'First Name', "trim|required|regex_match[/^[A-Za-z -']+$/]|max_length[" . $this->config->item('first_name_max_length', 'official') . "]|xss_clean");
        $this->ci->form_validation->set_rules('surname', 'Surname', "trim|required|regex_match[/^[A-Za-z -']+$/]|max_length[" . $this->config->item('surname_max_length', 'official') . "]|xss_clean");
    }

    /**
     * Fetch All Officials and format for dropdown
     * @return array List of Officials
     */
    public function fetchForDropdown()
    {
        $results = $this->fetchAll();

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = "{$result->surname}, $result->first_name";
        }

        return $dropdownOptions;
    }

    /**
     * Can the Official be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified Official be deleted?
     */
    public function isDeletable($id)
    {
        $ci =& get_instance();
        $ci->load->model('Match_model');

        $matches = $ci->Match_model->fetchAllByField('official_id', $id);

        if ($matches) {
            return false;
        }

        return true;
    }

}