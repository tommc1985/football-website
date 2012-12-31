<?php
require_once('_base_model.php');

/**
 * Model for League Registration data
 */
class League_Registration_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'league_registration';
    }

    /**
     * Apply Form Validation for Adding & Updating League Registrations
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('league_id', 'League', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('opposition_id', 'Team', 'trim|xss_clean');
    }

}