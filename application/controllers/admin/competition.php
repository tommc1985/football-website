<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Competitions
 */
class Competition extends CI_Controller/*Backend_Controller*/
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
        $this->load->model('Competition_model');
        $this->load->config('competition', true);
    }

    /**
     * Index Action - Show Paginated List of Competitions
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

        $data['competitions'] = $this->Competition_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = '/admin/competition/index/offset/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->Competition_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/competition/index', $data);
    }

    /**
     * Add Action - Add a Competition
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = 'Save';

        $this->Competition_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Competition_model->processInsert();

            $competition = $this->Competition_model->fetch($insertId);

            $this->session->set_flashdata('message', "Competition has been added");
            redirect('/admin/competition');
        }

        $this->load->view('admin/competition/add', $data);
    }

    /**
     * Edit Action - Edit a Competition
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = 'Save';

        $competition = false;
        if ($parameters['id'] !== false) {
            $competition = $this->Competition_model->fetch($parameters['id']);
        }

        if (empty($competition)) {
            $this->load->view('admin/competition/not_found', $data);
            return;
        }

        $this->Competition_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Competition_model->processUpdate($parameters['id']);

            $competition = $this->Competition_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', "Competition has been updated");
            redirect('/admin/competition');
        }

        $data['competition'] = $competition;

        $this->load->view('admin/competition/edit', $data);
    }

    /**
     * Delete Action - Delete a Competition
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $competition = false;
        if ($parameters['id'] !== false) {
            $competition = $this->Competition_model->fetch($parameters['id']);
        }

        if (empty($competition)) {
            $this->load->view('admin/competition/not_found');
            return;
        }

        $data['competition'] = $competition;

        if (!$this->Competition_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/competition/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->Competition_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', "Competition has been deleted");
            redirect('/admin/competition');
        }

        $this->load->view('admin/competition/delete', $data);
    }
}

/* End of file competition.php */
/* Location: ./application/controllers/competition.php */