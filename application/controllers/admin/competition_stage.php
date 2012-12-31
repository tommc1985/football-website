<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Competition Stages
 */
class Competition_Stage extends CI_Controller/*Backend_Controller*/
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

        $config['base_url'] = '/admin/competition-stage/index/offset/';
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

        $data['submitButtonText'] = 'Save';

        $this->Competition_Stage_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Competition_Stage_model->insertEntry(array(
                'name' => $this->form_validation->set_value('name', NULL),
                'short_name' => $this->form_validation->set_value('short_name', NULL),
                'abbreviation' => $this->form_validation->set_value('abbreviation', NULL),
                'type' => $this->form_validation->set_value('type', NULL),
                'starts' => $this->form_validation->set_value('starts', NULL),
                'subs' => $this->form_validation->set_value('subs', NULL),
                'competitive' => $this->form_validation->set_value('competitive', NULL),
            ));

            $competitionStage = $this->Competition_Stage_model->fetch($insertId);

            $this->session->set_flashdata('message', "Competition Stage has been added");
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

        $data['submitButtonText'] = 'Save';

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
            $this->Competition_Stage_model->updateEntry($parameters['id'], array(
                'name' => $this->form_validation->set_value('name', NULL),
                'short_name' => $this->form_validation->set_value('short_name', NULL),
                'abbreviation' => $this->form_validation->set_value('abbreviation', NULL),
                'type' => $this->form_validation->set_value('type', NULL),
                'starts' => $this->form_validation->set_value('starts', NULL),
                'subs' => $this->form_validation->set_value('subs', NULL),
                'competitive' => $this->form_validation->set_value('competitive', NULL),
            ));

            $competitionStage = $this->Competition_Stage_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', "Competition Stage has been updated");
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
            $this->load->view('admin/competition-stage/not_found', $data);
            return;
        }

        $data['competitionStage'] = $competitionStage;

        if ($this->input->post('confirm_delete') !== false) {
            $this->Competition_Stage_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', "Competition Stage has been deleted");
            redirect('/admin/competition-stage');
        }

        $this->load->view('admin/competition-stage/delete', $data);
    }
}

/* End of file competition_stage.php */
/* Location: ./application/controllers/competition_stage.php */