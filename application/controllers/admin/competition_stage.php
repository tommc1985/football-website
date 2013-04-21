<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Competition Stages
 */
class Competition_Stage extends Backend_Controller
{
    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->model('Competition_Stage_model');
        $this->load->config('competition_stage', true);

        $this->lang->load('competition_stage');
        $this->load->helper('competition_stage');
    }

    /**
     * Index Action - Show Paginated List of Competition Stages
     * @return NULL
     */
    public function index()
    {
        $this->load->library('pagination');

        $parameters = $this->uri->uri_to_assoc(4, array('offset', 'order-by', 'order'));

        $perPage = Configuration::get('per_page');
        $offset = false;
        if ($parameters['offset'] !== false && $parameters['offset'] > 0) {
            $offset = $parameters['offset'];
        }

        $data['competitionStages'] = $this->Competition_Stage_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = '/admin/competition-stage/index/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->Competition_Stage_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/competition-stage/index', $data);
    }

    /**
     * Add Action - Add a Competition Stage
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = $this->lang->line('competition_stage_add');

        $this->Competition_Stage_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Competition_Stage_model->processInsert();

            $competitionStage = $this->Competition_Stage_model->fetch($insertId);

            $this->session->set_flashdata('message', sprintf($this->lang->line('competition_stage_added'), $competitionStage->name));
            redirect('/admin/competition-stage');
        }

        $this->load->view('admin/competition-stage/add', $data);
    }

    /**
     * Edit Action - Edit a Competition Stage
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('competition_stage_save');

        $competitionStage = false;
        if ($parameters['id'] !== false) {
            $competitionStage = $this->Competition_Stage_model->fetch($parameters['id']);
        }

        if (empty($competitionStage)) {
            $this->load->view('admin/competition-stage/not_found', $data);
            return;
        }

        $this->Competition_Stage_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Competition_Stage_model->processUpdate($parameters['id']);

            $competitionStage = $this->Competition_Stage_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', sprintf($this->lang->line('competition_stage_updated'), $competitionStage->name));
            redirect('/admin/competition-stage');
        }

        $data['competitionStage'] = $competitionStage;

        $this->load->view('admin/competition-stage/edit', $data);
    }

    /**
     * Delete Action - Delete a Competition Stage
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $competitionStage = false;
        if ($parameters['id'] !== false) {
            $competitionStage = $this->Competition_Stage_model->fetch($parameters['id']);
        }

        if (empty($competitionStage)) {
            $this->load->view('admin/competition-stage/not_found');
            return;
        }

        $data['competitionStage'] = $competitionStage;

        if (!$this->Competition_Stage_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/competition-stage/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->Competition_Stage_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', sprintf($this->lang->line('competition_stage_deleted'), $competitionStage->name));
            redirect('/admin/competition-stage');
        }

        $this->load->view('admin/competition-stage/delete', $data);
    }
}

/* End of file competition_stage.php */
/* Location: ./application/controllers/admin/competition_stage.php */