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
        $this->load->model('frontend/League_Collated_Results_model');
        $this->load->model('Cache_League_model');
        $this->load->model('Competition_model');
        $this->load->model('Season_model');
        $this->lang->load('league');
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
        $parameters = $this->uri->uri_to_assoc(3, array('id', 'type', 'date-until'));

        $league = $this->League_model->fetch($parameters['id']);

        $formMatchCount = Configuration::get('form_match_count');

        if ($league === false) {
            show_error($this->lang->line('league_not_found'), 404);
        }

        $type = 'overall';
        if ($parameters['type'] !== false) {
            $type = $parameters['type'];
        }

        $dateUntil = 'overall';
        if ($parameters['date-until'] !== false) {
            $dateUntil = $parameters['date-until'];
        }

        $standings = $this->League_Collated_Results_model->fetchStandings($parameters['id'], $dateUntil, $type);

        $formTeams = $this->League_Collated_Results_model->fetchForm($standings, $formMatchCount);

        $data = array(
            'standings'      => $standings,
            'formTeams'      => $formTeams,
            'formMatchCount' => $formMatchCount,
            'id'             => $parameters['id'],
            'type'           => $type,
            'dateUntil'      => $dateUntil,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/league/view", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file league.php */
/* Location: ./application/controllers/league.php */