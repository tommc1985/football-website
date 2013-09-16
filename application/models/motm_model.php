<?php
require_once('_base_model.php');

/**
 * Model for MotM data
 */
class Motm_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'motm';
    }

    /**
     * Fetch vote data for a particular match
     * @param  int $matchId  The ID for the specified Match
     * @return object|false  The object (or false if not found)
     */
    public function fetch($matchId)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('match_id', $matchId)
            ->where('deleted', 0)
            ->order_by('placing');

        return $this->db->get()->result();
    }

    /**
     * Fetch vote data for a particular match and user
     * @param  int $matchId  The ID for the specified Match
     * @param  int $user Id  The ID for the specified User
     * @return object|false  The object (or false if not found)
     */
    public function fetch_by_user($matchId, $userId)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('match_id', $matchId)
            ->where('user_id', $userId)
            ->where('deleted', 0)
            ->order_by('placing');

        return $this->db->get()->result();
    }

    /**
     * Apply Form Validation for Adding & Updating MotM votes
     * @return NULL
     */
    public function formValidation($placingCount)
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules("match_id", 'Match ID', "trim|integer|required|xss_clean");

        $i = 0;
        while ($i < $placingCount) {
            $this->ci->form_validation->set_rules("id[{$i}]", 'Goal', "trim|integer|xss_clean");
                $this->ci->form_validation->set_rules("player_id[{$i}]", "Player", "trim|integer|callback_is_unique_player_id|xss_clean");

            $i++;
        }
    }

}