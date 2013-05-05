<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

class Head_To_Head extends Frontend_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('frontend/Head_To_Head_model');
        $this->load->model('frontend/Match_model');
        $this->load->model('Opposition_model');
        $this->lang->load('head_to_head');
        $this->lang->load('match');
        $this->load->helper(array('form', 'match', 'opposition', 'player', 'url', 'utility'));
    }

    /**
     * Index Action
     * @return NULL
     */
    public function index()
    {
        $this->load->library('pagination');
        $parameters = $this->uri->uri_to_assoc(3, array('opposition'));

        if ($this->input->post()) {
            $redirectString = '/head-to-head/index';

            if ($this->input->post('opposition')) {
                $redirectString .= '/opposition/' . $this->input->post('opposition');
            }

            redirect($redirectString);
        }

        $opposition = false;
        if ($parameters['opposition'] !== false && $parameters['opposition'] > 0) {
            if ($this->Opposition_model->fetch($parameters['opposition'])) {
                $opposition = (int) $parameters['opposition'];
            }
        }

        $matches = array();
        $accumulatedData = array();
        $scorers = array();
        $assisters = array();
        $offenders = array();
        $pointsGainers = array();
        if ($opposition !== false) {
            $matches         = $this->Match_model->fetchMatchesByOpposition($opposition);
            $accumulatedData = $this->Head_To_Head_model->calculateHeadToHeadAccumulatedData($matches);
            $scorers         = $this->Head_To_Head_model->fetchTopScorers($opposition);
            $assisters       = $this->Head_To_Head_model->fetchTopAssisters($opposition);
            $offenders       = $this->Head_To_Head_model->fetchWorstDiscipline($opposition);
            $pointsGainers   = $this->Head_To_Head_model->fetchPointsGained($opposition);
        }

        $data = array(
            'opposition'      => $opposition,
            'matches'         => $matches,
            'accumulatedData' => $accumulatedData,
            'scorers'         => $scorers,
            'assisters'       => $assisters,
            'offenders'       => $offenders,
            'pointsGainers'   => $pointsGainers,
        );

        $this->load->view("themes/{$this->theme}/header", $data);
        $this->load->view("themes/{$this->theme}/head_to_head/welcome_message", $data);
        $this->load->view("themes/{$this->theme}/footer", $data);
    }
}

/* End of file head_to_head.php */
/* Location: ./application/controllers/head_to_head.php */