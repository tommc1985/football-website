<?php
class Match_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    public $tableName;

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
     * Fetch the earliest matches stored in the system
     * @param  integer $limit Number of matches to return
     * @return array|object   Earliest match/matches
     */
    public function fetchEarliest($limit = 1)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('deleted', 0)
            ->order_by('date', 'asc')
            ->limit($limit, 0);

        $result = $this->db->get()->result();

        if ($limit == 1) {
            return $result[0];
        }

        return $result;
    }

}