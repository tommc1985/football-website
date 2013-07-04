<?php
require_once('_base_frontend_model.php');

/**
 * Model for Position Page
 */
class Position_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'position';
    }

    /**
     * Fetch All Positions and format for dropdown
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

}