<?php
require_once('_base_model.php');

/**
 * Model for Card data
 */
class Card_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'card';
    }

    /**
     * Fetch card data for a particular match
     * @param  int $matchId  The ID for the specified Match
     * @return object|false  The object (or false if not found)
     */
    public function fetch($matchId)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('match_id', $matchId)
            ->where('deleted', 0)
            ->order_by('minute');

        return $this->db->get()->result();
    }

    /**
     * Apply Form Validation for Adding & Updating Cards
     * @return NULL
     */
    public function formValidation($cardCount)
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules("match_id", 'Match ID', "trim|integer|required|xss_clean");

        $i = 0;
        while ($i < $cardCount) {
            $this->ci->form_validation->set_rules("id[{$i}]", 'Card', "trim|integer|xss_clean");
            $this->ci->form_validation->set_rules("minute[{$i}]", 'Minute', "trim|integer|less_than[" . (Configuration::get('max_minute') + 1) . "]|xss_clean");
            $this->ci->form_validation->set_rules("player_id[{$i}]", 'Player', "trim|integer|callback_player_required[{$i}]|xss_clean");
            $this->ci->form_validation->set_rules("offence[{$i}]", 'Offence', "trim|integer|callback_offence_required[{$i}]|xss_clean");

            $i++;
        }
    }

    /**
     * Fetch Card Offence
     * @param int $id    Id of Offence
     * @return mixed     List of Card Offences
     */
    public static function fetchOffence($id)
    {
        $offences = self::fetchOffences();

        if (isset($offences[$id])) {
            return $offences[$id];
        }

        return false;
    }

    /**
     * Fetch Card Offences
     * @return array List of Card Offences
     */
    public static function fetchOffences()
    {
        return array(
            '1' => array(
                'offence' => 'Unsporting Behaviour',
                'card' => 'y'
            ),
            '2' => array(
                'offence' => 'Dissent',
                'card' => 'y'
            ),
            '3' => array(
                'offence' => 'Persistent Infringement',
                'card' => 'y'
            ),
            '4' => array(
                'offence' => 'Delayed the restart of play',
                'card' => 'y'
            ),
            '5' => array(
                'offence' => 'Failed to respect required distance from corner/free kick',
                'card' => 'y'
            ),
            '6' => array(
                'offence' => "Entered field of play without Referee's permission",
                'card' => 'y'
            ),
            '7' => array(
                'offence' => "Deliberately left field of play without Referee's permission",
                'card' => 'y'
            ),
            '8' => array(
                'offence' => "Unknown",
                'card' => 'y'
            ),
            '9' => array(
                'offence' => "Serious Foul Play",
                'card' => 'r'
            ),
            '10' => array(
                'offence' => "Violent Conduct",
                'card' => 'r'
            ),
            '11' => array(
                'offence' => "Spat at an opponent or other person",
                'card' => 'r'
            ),
            '12' => array(
                'offence' => "Denied obvious goalscoring opportunity due to deliberate handball",
                'card' => 'r'
            ),
            '13' => array(
                'offence' => "Denied obvious goalscoring opportunity",
                'card' => 'r'
            ),
            '14' => array(
                'offence' => "Used offensive, insulting or abusive language",
                'card' => 'r'
            ),
            '15' => array(
                'offence' => "Unknown",
                'card' => 'r'
            ),
        );
    }

    /**
     * Fetch Card Offences
     * @return array List of Card Offences
     */
    public static function fetchOffencesForDropdown()
    {
        $offences = self::fetchOffences();

        $types = array(
            'y' => 'Yellow Card',
            'r' => 'Red Card'
        );

        $options = array();

        foreach ($types as $typeValue => $type) {
            foreach ($offences as $offenceId => $offenceData) {
                if ($typeValue == $offenceData['card']) {
                    $options[$type][$offenceId] = $offenceData['offence'];
                }
            }
        }

        return $options;
    }

}