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

                if (Configuration::get('include_appearances_ratings') === true) {
                    $this->ci->form_validation->set_rules("rating[{$appearanceType}][{$i}]", "Rating", "trim|integer|less_than[" . (Configuration::get('max_appearance_rating') + 1) . "]|greater_than[0]|callback_is_rating_set[{$appearanceType}_{$i}]|xss_clean");
                }

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
     * Fetch all player appearances for a particular Match
     * @param  int $matchId         Match Id
     * @return array                Returned rows
     */
    public function fetchByMatch($matchId)
    {
        $this->db->select("{$this->ci->Player_model->tableName}.*");
        $this->db->from($this->tableName);
        $this->db->join($this->ci->Player_model->tableName, "{$this->tableName}.player_id = {$this->ci->Player_model->tableName}.id");
        $this->db->where('match_id', $matchId);
        $this->db->where("{$this->tableName}.deleted", 0);
        $this->db->where("{$this->ci->Player_model->tableName}.deleted", 0);
        $this->db->order_by("{$this->ci->Player_model->tableName}.surname, {$this->ci->Player_model->tableName}.first_name", "asc");

        return $this->db->get()->result();
    }

    /**
     * Fetch All Players that appeared in the specified match and format for dropdown
     * @param  int $matchId         Match Id
     * @return array                List of Player Appearances
     */
    public function fetchForDropdown($matchId)
    {
        $results = $this->fetchByMatch($matchId);

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = "{$result->surname}, {$result->first_name}";
        }

        return $dropdownOptions;
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

    /**
     * Fetch Shirt Numbers for dropdown
     * @return array List of Shirt Numbers
     */
    public static function fetchShirtNumbers()
    {
        $i = 1;
        $shirtNumbers = array();

        while ($i <= Configuration::get('max_shirt_number')) {
            $shirtNumbers[$i] = $i;

            $i++;
        }

        return $shirtNumbers;
    }

    /**
     * Compare two sets of appearance data (before and after it's been saved) to verify if they're different
     * @param  object  $dataset_1  Old data (before save)
     * @param  object  $dataset_2  New data (after save)
     * @return boolean             Is the data the different
     */
    public function isDifferent($dataset_1, $dataset_2)
    {
        $appearances = array(
            'starts',
            'subs'
        );

        foreach ($appearances as $appearanceType) {
            if (isset($dataset_1[$appearanceType])) {
                foreach ($dataset_1[$appearanceType] as $index => $object) {
                    unset($dataset_1[$appearanceType][$index]->date_updated);
                }
            }

            if (isset($dataset_2[$appearanceType])) {
                foreach ($dataset_2[$appearanceType] as $index => $object) {
                    unset($dataset_2[$appearanceType][$index]->date_updated);
                }
            }
        }

        return md5(serialize($dataset_1)) != md5(serialize($dataset_2));
    }

}