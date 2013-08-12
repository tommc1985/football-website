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
        $this->load->model('frontend/Frontend_Match_model');
        $this->load->model('Opposition_model');
        $this->lang->load('head_to_head');
        $this->lang->load('match');
        $this->load->helper(array('form', 'match', 'opposition', 'player', 'url', 'utility'));
    }

    /**
     * Index Action
     * @return NULL
     */
    public function view()
    {
        $this->load->library('pagination');
        $parameters = $this->uri->uri_to_assoc(3, array('opposition'));

        if ($this->input->post()) {
            $redirectString = '/head-to-head/view';

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
            $matches         = $this->Frontend_Match_model->fetchMatchesByOpposition($opposition);
            $accumulatedData = $this->Head_To_Head_model->calculateHeadToHeadAccumulatedData($matches);
            $scorers         = $this->Head_To_Head_model->fetchTopScorers($opposition);
            $assisters       = $this->Head_To_Head_model->fetchTopAssisters($opposition);
            $offenders       = $this->Head_To_Head_model->fetchWorstDiscipline($opposition);
            $pointsGainers   = $this->Head_To_Head_model->fetchPointsGained($opposition);
        }

        switch (true) {
            case $opposition:
                $metaTitleString       = 'head_to_head_frontend_meta_title_selected_team';
                $metaDescriptionString = 'head_to_head_frontend_meta_description_selected_team';
                break;
            default:
                $metaTitleString       = 'head_to_head_frontend_meta_title';
                $metaDescriptionString = 'head_to_head_frontend_meta_description';
        }

        $metaData = array(
            Configuration::get('team_name'),
            $opposition ? Opposition_helper::name($opposition) : '',
        );

        $this->templateData['metaTitle']       = vsprintf($this->lang->line($metaTitleString), $metaData);
        $this->templateData['metaDescription'] = vsprintf($this->lang->line($metaDescriptionString), $metaData);
        $this->templateData['opposition']      = $opposition;
        $this->templateData['matches']         = $matches;
        $this->templateData['accumulatedData'] = $accumulatedData;
        $this->templateData['scorers']         = $scorers;
        $this->templateData['assisters']       = $assisters;
        $this->templateData['offenders']       = $offenders;
        $this->templateData['pointsGainers']   = $pointsGainers;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/head_to_head/view", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }
}

/* End of file head_to_head.php */
/* Location: ./application/controllers/head_to_head.php */