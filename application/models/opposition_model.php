<?php
require_once('_base_model.php');

/**
 * Model for Opposition data
 */
class Opposition_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'opposition';

        $this->ci->load->model('League_Match_model');
    }

    /**
     * Insert a Opposition from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'name' => $this->ci->form_validation->set_value('name', NULL),
        ));
    }

    /**
     * Update a Opposition from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'name' => $this->ci->form_validation->set_value('name', NULL),
        ));
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        return 'name';
    }

    /**
     * Apply Form Validation for Adding & Updating Oppositions
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
    }

    /**
     * Fetch All Oppositions and format for dropdown
     * @return array List of Oppositions
     */
    public function fetchForDropdown()
    {
        $results = $this->fetchAll();

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = $result->name;
        }

        return $dropdownOptions;
    }

    /**
     * Can the Opposition be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified Opposition be deleted?
     */
    public function isDeletable($id)
    {
        $homeLeagueMatches = $this->ci->League_Match_model->fetchAllByField('h_opposition_id', $id);
        $awayLeagueMatches = $this->ci->League_Match_model->fetchAllByField('a_opposition_id', $id);
        $leagueRegistrations = $this->ci->League_Registration_model->fetchAllByField('opposition_id', $id);

        if ($homeLeagueMatches || $awayLeagueMatches || $leagueRegistrations) {
            return false;
        }

        return true;
    }

}