<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Oppositions
 */
class Opposition extends Backend_Controller
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
        $this->load->model('Opposition_model');
        $this->load->config('opposition', true);

        $this->lang->load('opposition');
        $this->load->helper('opposition');
    }

    /**
     * Index Action - Show Paginated List of Oppositions
     * @return NULL
     */
    public function index()
    {
        $this->load->library('pagination');
        $this->load->helper('pagination');

        $parameters = $this->uri->uri_to_assoc(4, array('offset', 'order-by', 'order'));

        $perPage = Configuration::get('per_page');
        $offset = false;
        if ($parameters['offset'] !== false && $parameters['offset'] > 0) {
            $offset = $parameters['offset'];
        }

        $data['oppositions'] = $this->Opposition_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = site_url('admin/opposition/index') . '/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->Opposition_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;
        $config + Pagination_helper::settings();

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/opposition/index', $data);
    }

    /**
     * Add Action - Add a Opposition
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = $this->lang->line('opposition_add');

        $this->Opposition_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Opposition_model->processInsert();

            $opposition = $this->Opposition_model->fetch($insertId);

            $this->session->set_flashdata('message', sprintf($this->lang->line('opposition_added'), $opposition->name));
            redirect('/admin/opposition');
        }

        $this->load->view('admin/opposition/add', $data);
    }

    /**
     * Edit Action - Edit a Opposition
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('opposition_save');

        $opposition = false;
        if ($parameters['id'] !== false) {
            $opposition = $this->Opposition_model->fetch($parameters['id']);
        }

        if (empty($opposition)) {
            $this->load->view('admin/opposition/not_found', $data);
            return;
        }

        $this->Opposition_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Opposition_model->processUpdate($parameters['id']);

            $opposition = $this->Opposition_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', sprintf($this->lang->line('opposition_updated'), $opposition->name));
            redirect('/admin/opposition');
        }

        $data['opposition'] = $opposition;

        $this->load->view('admin/opposition/edit', $data);
    }

    /**
     * Delete Action - Delete a Opposition
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $opposition = false;
        if ($parameters['id'] !== false) {
            $opposition = $this->Opposition_model->fetch($parameters['id']);
        }

        if (empty($opposition)) {
            $this->load->view('admin/opposition/not_found', $data);
            return;
        }

        $data['opposition'] = $opposition;

        if (!$this->Opposition_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/opposition/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->Opposition_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', sprintf($this->lang->line('opposition_deleted'), $opposition->name));
            redirect('/admin/opposition');
        }

        $this->load->view('admin/opposition/delete', $data);
    }
}

/* End of file opposition.php */
/* Location: ./application/controllers/admin/opposition.php */