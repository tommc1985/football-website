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
        $this->load->model('frontend/Match_model');
        $this->load->model('Season_model');
        $this->lang->load('match');
        $this->load->helper(array('competition', 'competition_stage', 'goal', 'match', 'opposition', 'url', 'utility'));
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

        $orderBy = $this->Match_model->getOrderBy($parameters['order-by']);
        $order = $this->Match_model->getOrder($parameters['order']);

        $matches = $this->Match_model->fetchMatchList($season, $type, $orderBy, $order);

        $data = array(
            'matches' => $matches,
            'season'  => $season,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/match/welcome_message", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('id'));

        $match = $this->Match_model->fetchMatchDetails($parameters['id']);

        if ($match === false) {
            show_error('Player cannot be found', 404);
        }

        $data = array(
            'match' => $match,
        );

        $this->load->view("themes/{$this->theme}/header", $data);

        if (is_null($match->h) && is_null($match->status)) {
            $this->load->view("themes/{$this->theme}/match/preview", $data);
        } else {

            $this->load->view("themes/{$this->theme}/match/result", $data);
        }

        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file match.php */
/* Location: ./application/controllers/match.php */