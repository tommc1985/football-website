<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Player extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Player_model');
        $this->load->model('Season_model');
        $this->lang->load('player');
        $this->load->helper(array('competition', 'player', 'position', 'url', 'utility'));
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
        } elseif ($parameters['season'] == 'career') {
            $season = 'career';
        }

        $type = $parameters['type'];
        if ($parameters['type'] === false) {
            $type = 'overall';
        }

        $orderBy = $this->Player_model->getOrderBy($parameters['order-by']);
        $order = $this->Player_model->getOrder($parameters['order']);

        $players = $this->Player_model->fetchPlayerList($season, $type, $orderBy, $order);

        $data = array(
            'players' => $players,
            'season'  => $season,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/player/welcome_message", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }

    /**
     * View Action
     * @return NULL
     */
    public function view()
    {
        $parameters = $this->uri->uri_to_assoc(3, array('id'));

        $player = $this->Player_model->fetchPlayerDetails($parameters['id']);

        if ($player === false) {
            show_error($this->lang->line('player_not_found'), 404);
        }

        $data = array(
            'player' => $player,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/player/view", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file player.php */
/* Location: ./application/controllers/player.php */