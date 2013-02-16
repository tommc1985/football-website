<?php
require_once('_base_model.php');

/**
 * Model for Position data
 */
class Position_model extends Base_Model {

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
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        return 'id';
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
            $dropdownOptions[$result->id] = $result->abbreviation;
        }

        return $dropdownOptions;
    }

}