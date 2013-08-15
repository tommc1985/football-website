<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Player_Goal_Statistics extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Player_Goal_Statistics_model');
        $this->load->model('Cache_Player_Goals_Statistics_model');
        $this->load->model('Competition_model');
        $this->load->model('Goal_model');
        $this->load->model('Season_model');
        $this->lang->load('player_goal_statistics');
        $this->lang->load('match');
        $this->load->helper(array('player_goal_statistics', 'competition', 'competition_stage', 'form', 'goal', 'match', 'opposition', 'player', 'url', 'utility'));
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

        if ($this->input->post()) {
            $redirectString = '/player-goal-statistics/view';

            if ($season != Season_model::fetchCurrentSeason()) {
                $redirectString .= '/season/' . $season;
            }

            if ($this->input->post('type')) {
                if ($this->input->post('type') != 'overall') {
                    $redirectString .= '/type/' . $this->input->post('type');
                }
            }

            redirect($redirectString);
        }

        $type = 'overall';
        if ($parameters['type'] !== false) {
            $type = $parameters['type'];
        }

        $statistics = $this->Player_Goal_Statistics_model->fetchAll($season == 'all-time' ? 'career' : $season, $type);

        switch (true) {
            case $season == 'all-time' && $type == 'overall':
                $metaTitleString       = 'player_goal_statistics_view_frontend_meta_title_all_time';
                $metaDescriptionString = 'player_goal_statistics_view_frontend_meta_description_all_time';
                break;
            case is_numeric($season) && $type == 'overall':
                $metaTitleString       = 'player_goal_statistics_view_frontend_meta_title_season_only';
                $metaDescriptionString = 'player_goal_statistics_view_frontend_meta_description_season_only';
                break;
            case $season == 'all-time' && $type != 'overall':
                $metaTitleString       = 'player_goal_statistics_view_frontend_meta_title_all_time_and_type';
                $metaDescriptionString = 'player_goal_statistics_view_frontend_meta_description_all_time_and_type';
                break;
            default:
                $metaTitleString       = 'player_goal_statistics_view_frontend_meta_title_season_and_type';
                $metaDescriptionString = 'player_goal_statistics_view_frontend_meta_description_season_and_type';
        }

        $metaData = array(
            Configuration::get('team_name'),
            Utility_helper::formattedSeason($season),
            $type != 'overall' ? Competition_helper::type($type) : '',
        );

        $this->templateData['metaTitle']       = vsprintf($this->lang->line($metaTitleString), $metaData);
        $this->templateData['metaDescription'] = vsprintf($this->lang->line($metaDescriptionString), $metaData);
        $this->templateData['statistics']      = $statistics;
        $this->templateData['season']          = $season;
        $this->templateData['type']            = $type;
        $this->templateData['goalTypes']       = Goal_model::fetchTypes();
        $this->templateData['bodyParts']       = Goal_model::fetchBodyParts();
        $this->templateData['distances']       = Goal_model::fetchDistances();
        $this->templateData['minuteIntervals'] = Goal_model::fetchMinuteIntervals();

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/player-goal-statistics/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file player_goal_statistics.php */
/* Location: ./application/controllers/player_goal_statistics.php */