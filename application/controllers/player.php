<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Player extends Frontend_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->database();
        $this->load->model('frontend/Player_model');
        $this->load->model('Season_model');
        $this->load->helper(array('player', 'utility'));

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
            'season'  => $season
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/player/welcome_message", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */