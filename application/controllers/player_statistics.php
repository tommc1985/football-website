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
        $this->load->model('Season_model');
        $this->lang->load('player_statistics');
        $this->lang->load('match');
        $this->load->helper(array('player_statistics', 'competition', 'competition_stage', 'goal', 'match', 'opposition', 'player', 'url', 'utility'));
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('season', 'type', 'threshold', 'unit'));

        if ($this->input->post()) {
            $redirectString = '/player-statistics/view';

            if ($this->input->post('season')) {
                $redirectString .= '/season/' . $this->input->post('season');
            }

            if ($this->input->post('type')) {
                $redirectString .= '/type/' . $this->input->post('type');
            }

            if ($this->input->post('threshold')) {
                $redirectString .= '/threshold/' . (int) $this->input->post('threshold');
            }

            if ($this->input->post('unit')) {
                $redirectString .= '/unit/' . $this->input->post('unit');
            }

            redirect($redirectString);
        }

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

        $thresholdPercentage = 0;
        $thresholdMatches = 0;
        $matchCount = count($this->Season_model->fetchMatches($type == 'overall' ? NULL : $type, $season == 'all-time' ? NULL : $season, NULL, true));
        if ($parameters['threshold'] !== false) {
            if ($parameters['unit'] == 'percent') {
                $thresholdPercentage = (int) $parameters['threshold'];

                if ($matchCount > 0) {
                    $thresholdMatches = (int) ($matchCount * $thresholdPercentage / 100);
                }
            } else {
                $thresholdMatches = (int) $parameters['threshold'];
            }
        }

        $statistics = $this->Player_Statistics_model->fetchAll($season == 'all-time' ? 'career' : $season, $type);

        $data = array(
            'statistics'          => $statistics,
            'season'              => $season,
            'type'                => $type,
            'thresholdPercentage' => $thresholdPercentage,
            'thresholdMatches'    => $thresholdMatches,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/player-statistics/view", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file player_statistics.php */
/* Location: ./application/controllers/player_statistics.php */