<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Awards
 */
class Award extends Backend_Controller
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
        $this->load->model('Cache_model');
        $this->load->model('Award_model');
        $this->load->config('award', true);

        $this->lang->load('award');
        $this->load->helper('award');
    }

    /**
     * Index Action - Show Paginated List of Awards
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

        $data['awards'] = $this->Award_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = '/admin/award/index/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->Award_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/award/index', $data);
    }

    /**
     * Add Action - Add an Award
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = $this->lang->line('award_add');

        $this->Award_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Award_model->processInsert();

            $award = $this->Award_model->fetch($insertId);

            $this->session->set_flashdata('message', sprintf($this->lang->line('award_added'), Award_helper::longName($award->id)));
            redirect('/admin/award');
        }

        $this->load->view('admin/award/add', $data);
    }

    /**
     * Edit Action - Edit an Award
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('award_save');

        $award = false;
        if ($parameters['id'] !== false) {
            $award = $this->Award_model->fetch($parameters['id']);
        }

        if (empty($award)) {
            $this->load->view('admin/award/not_found', $data);
            return;
        }

        $this->Award_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Award_model->processUpdate($parameters['id']);

            $award = $this->Award_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', sprintf($this->lang->line('award_updated'), Award_helper::longName($award->id)));
            redirect('/admin/award');
        }

        $data['award'] = $award;

        $this->load->view('admin/award/edit', $data);
    }

    /**
     * Delete Action - Delete a Award
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $award = false;
        if ($parameters['id'] !== false) {
            $award = $this->Award_model->fetch($parameters['id']);
        }

        if (empty($award)) {
            $this->load->view('admin/award/not_found', $data);
            return;
        }

        $data['award'] = $award;

        if (!$this->Award_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/award/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->Award_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', sprintf($this->lang->line('award_deleted'), $award->id));
            redirect('/admin/award');
        }

        $this->load->view('admin/award/delete', $data);
    }
}

/* End of file award.php */
/* Location: ./application/controllers/admin/award.php */