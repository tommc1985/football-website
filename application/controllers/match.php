<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Match extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Frontend_Match_model');
        $this->load->model('Season_model');
        $this->lang->load('factfile');
        $this->lang->load('match');
        $this->load->helper(array('card', 'competition', 'competition_stage', 'factfile', 'goal', 'match', 'milestone', 'official', 'opposition', 'player', 'position', 'url', 'utility'));
    }

    /**
     * Index Action
     * @return NULL
     */
    public function index()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('season', 'type', 'order-by', 'order'));

        $season = (int) $parameters['season'];
        if ($parameters['season'] === false) {
            $season = Season_model::fetchCurrentSeason();
        } elseif ($parameters['season'] == 'all-time') {
            $season = 'all-time';
        }

        $type = $parameters['type'];
        if ($parameters['type'] === false) {
            $type = 'overall';
        }

        $orderBy = $this->Frontend_Match_model->getOrderBy($parameters['order-by']);
        $order = $this->Frontend_Match_model->getOrder($parameters['order']);

        $matches = $this->Frontend_Match_model->fetchMatchList($season, $type, $orderBy, $order, Season_model::fetchCurrentSeason() == $season);

        $this->templateData['matches'] = $matches;
        $this->templateData['season']  = $season;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/match/welcome_message", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('id'));

        $match = $this->Frontend_Match_model->fetchMatchDetails($parameters['id']);

        if ($match === false) {
            show_error($this->lang->line('match_not_found'), 404);
        }

        $this->templateData['match'] = $match;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);

        if (!is_null($match->h) || !is_null($match->status)) {
            $this->load->view("themes/{$this->theme}/match/result", $this->templateData);
        } else if(!is_null($match->date)) {
            $this->load->view("themes/{$this->theme}/match/preview", $this->templateData);
        } else {
            $this->load->view("themes/{$this->theme}/match/tbc", $this->templateData);
        }

        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file match.php */
/* Location: ./application/controllers/match.php */