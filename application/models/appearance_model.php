<?php
require_once('_base_model.php');

/**
 * Model for Appearance data
 */
class Appearance_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'appearance';
    }

    /**
     * Insert a Match from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'opposition_id' => $this->ci->form_validation->set_value('opposition_id', NULL),
            'competition_id' => $this->ci->form_validation->set_value('competition_id', NULL),
            'competition_stage_id' => $this->ci->form_validation->set_value('competition_stage_id', NULL),
            'venue' => $this->ci->form_validation->set_value('venue', NULL),
            'location' => $this->ci->form_validation->set_value('location', NULL),
            'official_id' => $this->ci->form_validation->set_value('official_id', NULL),
            'h' => $this->ci->form_validation->set_value('h', NULL),
            'a' => $this->ci->form_validation->set_value('a', NULL),
            'report' => $this->ci->form_validation->set_value('report', NULL),
            'date' => $this->ci->form_validation->set_value('date', NULL) . ' ' . $this->ci->form_validation->set_value('time', NULL) . ':00',
            'h_et' => $this->ci->form_validation->set_value('h_et', NULL),
            'a_et' => $this->ci->form_validation->set_value('a_et', NULL),
            'h_pen' => $this->ci->form_validation->set_value('h_pen', NULL),
            'a_pen' => $this->ci->form_validation->set_value('a_pen', NULL),
            'status' => $this->ci->form_validation->set_value('status', NULL),
        ));
    }

    /**
     * Update a Match from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'opposition_id' => $this->ci->form_validation->set_value('opposition_id', NULL),
            'competition_id' => $this->ci->form_validation->set_value('competition_id', NULL),
            'competition_stage_id' => $this->ci->form_validation->set_value('competition_stage_id', NULL),
            'venue' => $this->ci->form_validation->set_value('venue', NULL),
            'location' => $this->ci->form_validation->set_value('location', NULL),
            'official_id' => $this->ci->form_validation->set_value('official_id', NULL),
            'h' => $this->ci->form_validation->set_value('h', NULL),
            'a' => $this->ci->form_validation->set_value('a', NULL),
            'report' => $this->ci->form_validation->set_value('report', NULL),
            'date' => $this->ci->form_validation->set_value('date', NULL) . ' ' . $this->ci->form_validation->set_value('time', NULL) . ':00',
            'h_et' => $this->ci->form_validation->set_value('h_et', NULL),
            'a_et' => $this->ci->form_validation->set_value('a_et', NULL),
            'h_pen' => $this->ci->form_validation->set_value('h_pen', NULL),
            'a_pen' => $this->ci->form_validation->set_value('a_pen', NULL),
            'status' => $this->ci->form_validation->set_value('status', NULL),
        ));
    }

    /**
     * Fetch appearance data for a particular match
     * @param  int $matchId  The ID for the specified Match
     * @return object|false  The object (or false if not found)
     */
    public function fetch($matchId)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('match_id', $matchId)
            ->where('deleted', 0)
            ->order_by('order');

        $result = $this->db->get()->result();

        $appearances = array(
            'starts' => array(),
            'subs' => array());

        foreach ($result as $appearance) {
            switch ($appearance->status) {
                case 'starter':
                    $appearances['starts'][] = $appearance;
                    break;
                case 'substitute':
                case 'unused':
                    $appearances['subs'][] = $appearance;
                    break;
            }
        }

        return $appearances;
    }

    /**
     * Apply Form Validation for Adding & Updating Appearance Data
     * @param  array $playerCounts The number of players starting and substitutes
     * @return NULL
     */
    public function formValidation($playerCounts)
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('match_id', 'Match', "trim|required|integer|xss_clean");
        $this->ci->form_validation->set_rules("captain", 'Captain', "trim|integer|callback_is_valid_captain|xss_clean");
        $this->ci->form_validation->set_rules("motm", 'Man of the Match', "trim|required|xss_clean");

        foreach ($playerCounts as $appearanceType => $playerCount) {
            $i = 0;
            while($i < $playerCount) {
                $this->ci->form_validation->set_rules("id[{$appearanceType}][{$i}]", "Appearance Id", "trim|integer|xss_clean");
                $this->ci->form_validation->set_rules("player_id[{$appearanceType}][{$i}]", "Player Id", "trim|integer|callback_is_unique_player_id|xss_clean");
                $this->ci->form_validation->set_rules("rating[{$appearanceType}][{$i}]", "Rating", "trim|integer|less_than[" . (Configuration::get('max_appearance_rating') + 1) . "]|greater_than[0]|callback_is_rating_set[{$appearanceType}_{$i}]|xss_clean");
                $this->ci->form_validation->set_rules("injury[{$appearanceType}][]", "Injury", "trim|integer|xss_clean");
                $this->ci->form_validation->set_rules("position[{$appearanceType}][{$i}]", "Position", "trim|greater_than[0]|integer|callback_is_position_set[{$appearanceType}_{$i}]|xss_clean");
                $this->ci->form_validation->set_rules("order[{$appearanceType}][{$i}]", "Order", "trim|integer|xss_clean");
                $this->ci->form_validation->set_rules("shirt[{$appearanceType}][{$i}]", "Shirt", "trim|integer|xss_clean");

                $this->ci->form_validation->set_rules("on[{$appearanceType}][{$i}]", "Substituted On", "trim|less_than[" . (Configuration::get('max_minute') + 1) . "]|greater_than[0]|integer|xss_clean");
                $this->ci->form_validation->set_rules("off[{$appearanceType}][{$i}]", "Substituted Off", 'trim|less_than[' . (Configuration::get('max_minute') + 1) . "]|greater_than[0]|integer|xss_clean");

                $i++;
            }
        }
    }

    /**
     * Fetch ratings for dropdown
     * @return array List of ratings
     */
    public static function fetchRatings()
    {
        $i = 1;
        $ratings = array();

        while ($i <= Configuration::get('max_appearance_rating')) {
            $ratings[$i] = $i;

            $i++;
        }

        $ratings = array_reverse($ratings, true);

        return $ratings;
    }

}