<?php
class Player_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function fetchPlayers()
    {
        $query = $this->db->get('player', 10);
        return $query->result();
    }

}