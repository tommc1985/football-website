<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class League_Statistics extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/League_model');
        $this->load->model('frontend/League_Statistics_model');
        $this->load->model('Cache_League_Statistics_model');
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
        $parameters = $this->uri->uri_to_assoc(3, array('id'));

        $league = $this->League_model->fetch($parameters['id']);

        if ($league === false) {
            show_error($this->lang->line('league_not_found'), 404);
        }

        $statistics = $this->League_Statistics_model->fetchAll($parameters['id']);

        $this->templateData['statistics'] = $standings;
        $this->templateData['id']         = $parameters['id'];
        $this->templateData['venues']     = array(
            '',
            'h',
            'a'
        );

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/league-statistics/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file league_statistics.php */
/* Location: ./application/controllers/league_statistics.php */