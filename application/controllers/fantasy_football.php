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
        $this->load->model('Season_model');
        $this->lang->load('fantasy_football');
        $this->load->helper(array('club_statistics', 'competition', 'competition_stage', 'fantasy_football', 'goal', 'match', 'opposition', 'player', 'url', 'utility'));
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('season', 'type', 'order-by', 'position', 'formation'));

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

        $formation = '4-4-2';
        if ($parameters['formation'] !== false) {
            $formation = $parameters['formation'];
        }

        $orderBy = '';
        if ($parameters['order-by'] !== false) {
            $orderBy = $parameters['order-by'];
        }

        $fantasyFootballData = $this->Fantasy_Football_model->fetchAll($season == 'all-time' ? 'career' : $season, $type, $position, $orderBy);

        $bestLineup = $this->Fantasy_Football_model->fetchBestLineup($formation, $season == 'all-time' ? 'career' : $season, $type, $orderBy);

        $data = array(
            'fantasyFootballData' => $fantasyFootballData,
            'bestLineup'          => $bestLineup,
            'season'              => $season,
            'type'                => $type,
            'position'            => $position,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/fantasy-football/view", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file fantasy_football.php */
/* Location: ./application/controllers/fantasy_football.php */