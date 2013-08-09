<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Player_Statistics extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Player_Statistics_model');
        $this->load->model('Cache_Player_Statistics_model');
        $this->load->model('Competition_model');
        $this->load->model('Season_model');
        $this->lang->load('player_statistics');
        $this->lang->load('match');
        $this->load->helper(array('player_statistics', 'competition', 'competition_stage', 'form', 'goal', 'match', 'opposition', 'player', 'url', 'utility'));
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('season', 'type', 'threshold'));

        $season = Season_model::fetchCurrentSeason();
        if ($parameters['season'] !== false) {
            if ($parameters['season'] == 'all-time') {
                $season = 'all-time';
            } else {
                $season = (int) $parameters['season'];
            }
        }

        if ($this->input->post()) {
            $redirectString = '/player-statistics/view';

            if ($season != Season_model::fetchCurrentSeason()) {
                $redirectString .= '/season/' . $season;
            }

            if ($this->input->post('type')) {
                $redirectString .= '/type/' . $this->input->post('type');
            }

            if ($this->input->post('threshold')) {
                $redirectString .= '/threshold/' . (int) $this->input->post('threshold');
            }

            redirect($redirectString);
        }

        $type = 'overall';
        if ($parameters['type'] !== false) {
            $type = $parameters['type'];
        }

        $defaultThreshold = Configuration::get('default_threshold');
        $matchCount = count($this->Season_model->fetchMatches($type == 'overall' ? NULL : $type, $season == 'all-time' ? NULL : $season, NULL, true));

        $threshold = (int) ceil($matchCount * $defaultThreshold / 100);
        if ($parameters['threshold'] !== false) {
            $threshold = (int) $parameters['threshold'];
        }

        $statistics = $this->Player_Statistics_model->fetchAll($season == 'all-time' ? 'career' : $season, $type, $threshold);

        $this->templateData['statistics'] = $statistics;
        $this->templateData['season']     = $season;
        $this->templateData['type']       = $type;
        $this->templateData['matchCount'] = $matchCount;
        $this->templateData['threshold']  = $threshold;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/player-statistics/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file player_statistics.php */
/* Location: ./application/controllers/player_statistics.php */