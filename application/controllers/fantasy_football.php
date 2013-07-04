<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Fantasy_Football extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Fantasy_Football_model');
        $this->load->model('frontend/Position_model');
        $this->load->model('Competition_model');
        $this->load->model('Season_model');
        $this->lang->load('fantasy_football');
        $this->load->helper(array('club_statistics', 'competition', 'competition_stage', 'fantasy_football', 'form', 'goal', 'match', 'opposition', 'player', 'position', 'url', 'utility'));
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('season', 'type', 'order-by', 'position', 'formation', 'measurement'));

        $season = Season_model::fetchCurrentSeason();
        if ($parameters['season'] !== false) {
            if ($parameters['season'] == 'all-time') {
                $season = 'all-time';
            } else {
                $season = (int) $parameters['season'];
            }
        }

        $type = 'overall';
        if ($parameters['type'] !== false) {
            $type = $parameters['type'];
        }

        $position = 'all';
        if ($parameters['position'] !== false) {
            $position = $parameters['position'];
        }

        $orderBy = '';
        if ($parameters['order-by'] !== false) {
            $orderBy = $parameters['order-by'];
        }

        $formation = '4-4-2';
        if ($parameters['formation'] !== false) {
            $formation = $parameters['formation'];
        }

        $measurement = '';
        if ($parameters['measurement'] !== false) {
            $measurement = $parameters['measurement'];
        }

        if ($this->input->post()) {
            $redirectString = '/fantasy-football/view';

            if ($this->input->post('season')) {
                $season = $this->input->post('season');
            }

            if ($this->input->post('type')) {
                $type = $this->input->post('type');
            }

            if ($this->input->post('position')) {
                $position = $this->input->post('position');
            }

            if ($this->input->post('order_by')) {
                $orderBy = $this->input->post('order_by');
            }

            if ($this->input->post('formation')) {
                $formation = $this->input->post('formation');
            }

            if ($this->input->post('measurement')) {
                $measurement = $this->input->post('measurement');
            }

            $redirectString .= '/season/' . $season;
            $redirectString .= '/type/' . $type;
            $redirectString .= '/position/' . $position;
            $redirectString .= '/order-by/' . $orderBy;
            $redirectString .= '/formation/' . $formation;
            $redirectString .= '/measurement/' . $measurement;

            redirect($redirectString);
        }

        // Fetch Formation Info
        $formationInfo = $this->Fantasy_Football_model->fetchFormationInfo($formation);

        if ($formationInfo === false) {
            show_error($this->lang->line('fantasy_football_formation_not_exist'), 404);
        }

        // Fetch Data for table
        $fantasyFootballData = $this->Fantasy_Football_model->fetchAll($season == 'all-time' ? 'career' : $season, $type, $position, $orderBy);

        // Fetch Best Lineup for specified formation
        $bestLineup = $this->Fantasy_Football_model->fetchBestLineup($formation, $season == 'all-time' ? 'career' : $season, $type, $measurement);

        $data = array(
            'fantasyFootballData' => $fantasyFootballData,
            'bestLineup'          => $bestLineup,
            'formationInfo'       => $formationInfo,
            'formation'           => $formation,
            'season'              => $season,
            'type'                => $type,
            'position'            => $position,
            'orderBy'             => $orderBy,
            'measurement'         => $measurement,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/fantasy-football/view", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file fantasy_football.php */
/* Location: ./application/controllers/fantasy_football.php */