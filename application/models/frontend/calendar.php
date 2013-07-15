<?php
require_once('_base_frontend_model.php');

/**
 * Model for Calendar
 */
class Calendar_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->ci =& get_instance();
        $this->ci->load->model('Player_Registration_model');
        $this->ci->load->model('Season_model');
    }

    /**
     * Return data for Calendar from Player's Birthdays, Upcoming Matches, Previous Results and One-Off Events (not yet implemented)
     * @return array          Multidimensional array of aggregated Calendar Data
     */
    public function fetchData()
    {
        $currentSeason = Season_model::fetchCurrentSeason();

        $players = $this->ci->Player_Registration_model->fetchBySeason($currentSeason);

        $matches = $this->ci->Season_model->fetchMatches(false, $currentSeason);

        $events = array(); // One-off Events not yet implemented

        $data = array(
            'players' => $players,
            'matches' => $matches,
            'events' => $events,
        );

        return $data;
    }

}