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
        $this->load->helper(array('league', 'league_match', 'league_statistics', 'competition', 'competition_stage', 'form', 'goal', 'match', 'opposition', 'player', 'url', 'utility'));
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

        $type = 'overall';
        $dateUntil = 'overall';
        if ($this->input->post()) {
            $redirectString = '/league/view/id/' . $parameters['id'];

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

            redirect($redirectString);
        }

        if ($parameters['type'] !== false) {
            $type = $parameters['type'];
        }

        if ($parameters['date-until'] !== false) {
            $dateUntil = $parameters['date-until'];
        }

        $matchDate = $this->League_Match_model->fetchNextDate($parameters['id']);
        $matchDate = $matchDate !== false ? $matchDate : $this->League_Match_model->fetchLastDate($parameters['id']);
        if ($parameters['match-date'] !== false) {
            $matchDate = $parameters['match-date'];
        }

        $formMatchCount = Configuration::get('form_match_count');
        if ($parameters['form-match-count'] !== false) {
            $formMatchCount = (int) $parameters['form-match-count'];
        }

        $standings        = $this->League_Collated_Results_model->fetchStandings($parameters['id'], $dateUntil, $type);
        $alternativeTable = $this->League_Collated_Results_model->fetchAlternativeTable($parameters['id'], $dateUntil, $type);

        $formTeams = $this->League_Collated_Results_model->fetchForm($standings, $formMatchCount);

        $dropdownDates = $this->League_Match_model->fetchDatesForDropdown($parameters['id']);
        $leagueMatches = $this->League_Match_model->fetchByDate($parameters['id'], $matchDate);

        $this->templateData['standings']        = $standings;
        $this->templateData['alternativeTable'] = $alternativeTable;
        $this->templateData['formTeams']        = $formTeams;
        $this->templateData['formMatchCount']   = $formMatchCount;
        $this->templateData['id']               = $parameters['id'];
        $this->templateData['type']             = $type;
        $this->templateData['dateUntil']        = $dateUntil;
        $this->templateData['matchDate']        = $matchDate;
        $this->templateData['dropdownDates']    = $dropdownDates;
        $this->templateData['leagueMatches']    = $leagueMatches;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/league/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file league.php */
/* Location: ./application/controllers/league.php */