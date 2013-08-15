<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class League extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/League_model');
        $this->load->model('frontend/League_Match_model');
        $this->load->model('frontend/League_Collated_Results_model');
        $this->load->model('Cache_League_model');
        $this->load->model('Competition_model');
        $this->load->model('Season_model');
        $this->lang->load('league');
        $this->lang->load('league_match');
        $this->lang->load('league_statistics');
        $this->lang->load('match');
        $this->load->helper(array('chart', 'league', 'league_match', 'league_statistics', 'competition', 'competition_stage', 'form', 'goal', 'match', 'opposition', 'player', 'url', 'utility'));


        Assets::addJs('assets/js/chartjs/Chart.min.js');
        Assets::addJs('assets/js/charts.js');
        Assets::addJs('assets/modules/league/js/league.js');
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('id', 'type', 'date-until', 'match-date', 'form-match-count'));

        $league = $this->League_model->fetch($parameters['id']);

        if ($league === false) {
            show_error($this->lang->line('league_not_found'), 404);
        }

        $id = $parameters['id'];

        $type = 'overall';
        $dateUntil = 'overall';
        if ($this->input->post()) {
            $redirectString = '/league/view/id/' . $id;

            $postType = $this->input->post('type');
            if ($postType != $type) {
                $redirectString .= '/type/' . $postType;
            }

            $postDateUntil = $this->input->post('date-until');
            if ($postDateUntil != $dateUntil) {
                $redirectString .= '/date-until/' . $this->input->post('date-until');
            }

            if ($this->input->post('match-date')) {
                $redirectString .= '/match-date/' . $this->input->post('match-date');
            }

            if ($this->input->post('form-match-count')) {
                $redirectString .= '/form-match-count/' . $this->input->post('form-match-count');
            }

            if (!$this->input->is_ajax_request()) {
                redirect($redirectString);
            }
        }

        if ($parameters['type'] !== false) {
            $type = $parameters['type'];
        }

        if ($parameters['date-until'] !== false) {
            $dateUntil = $parameters['date-until'];
        }

        $matchDate = $this->League_Match_model->fetchNextDate($id);
        $matchDate = $matchDate !== false ? $matchDate : $this->League_Match_model->fetchLastDate($id);
        if ($parameters['match-date'] !== false) {
            $matchDate = $parameters['match-date'];
        }

        if ($this->input->post('match-date')) {
            $matchDate = $this->input->post('match-date');
        }

        $formMatchCount = Configuration::get('form_match_count');
        if ($parameters['form-match-count'] !== false) {
            $formMatchCount = (int) $parameters['form-match-count'];
        }

        if ($this->input->post('form-match-count')) {
            $formMatchCount = (int) $this->input->post('form-match-count');
        }

        $standings        = $this->League_Collated_Results_model->fetchStandings($id, $dateUntil, $type);
        $alternativeTable = $this->League_Collated_Results_model->fetchAlternativeTable($id, $dateUntil, $type);
        $positionProgress = $this->League_model->fetchPositionProgress($id, $dateUntil, $type);

        $formTeams = $this->League_Collated_Results_model->fetchForm($standings, $formMatchCount);

        $dropdownDates = $this->League_Match_model->fetchDatesForDropdown($id);
        $leagueMatches = $this->League_Match_model->fetchByDate($id, $matchDate);

        $metaData = array(
            League_helper::name($id),
        );

        $this->templateData['metaTitle']        = League_helper::name($id);
        $this->templateData['metaDescription']  = vsprintf($this->lang->line('league_frontend_meta_description'), $metaData);
        $this->templateData['standings']        = $standings;
        $this->templateData['alternativeTable'] = $alternativeTable;
        $this->templateData['positionProgress'] = $positionProgress;
        $this->templateData['formTeams']        = $formTeams;
        $this->templateData['formMatchCount']   = $formMatchCount;
        $this->templateData['id']               = $id;
        $this->templateData['type']             = $type;
        $this->templateData['dateUntil']        = $dateUntil;
        $this->templateData['matchDate']        = $matchDate;
        $this->templateData['dropdownDates']    = $dropdownDates;
        $this->templateData['leagueMatches']    = $leagueMatches;

        if ($this->input->is_ajax_request()) {
            switch ($this->input->post('view')) {
                case 'form':
                    $this->load->view("themes/{$this->theme}/league/_form", $this->templateData);
                    return;
                    break;
                case 'fixtures-and-results':
                    $this->load->view("themes/{$this->theme}/league/_fixtures_and_results", $this->templateData);
                    return;
                    break;
            }
        }

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/league/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file league.php */
/* Location: ./application/controllers/league.php */