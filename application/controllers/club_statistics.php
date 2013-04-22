<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Club_Statistics extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Club_Statistics_model');
        $this->load->model('Cache_Club_Statistics_model');
        $this->load->model('Season_model');
        $this->lang->load('club_statistics');
        $this->lang->load('match');
        $this->load->helper(array('club_statistics', 'competition', 'competition_stage', 'goal', 'match', 'opposition', 'player', 'url', 'utility'));
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('season', 'type'));

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

        $statistics = $this->Club_Statistics_model->fetchAll($season == 'all-time' ? 'career' : $season, $type);

        $data = array(
            'statistics' => $statistics,
            'season'     => $season,
            'type'       => $type,
            'venues'     => array(
                '',
                'h',
                'a'
            )
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/club-statistics/view", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file club-statistics.php */
/* Location: ./application/controllers/club-statistics.php */