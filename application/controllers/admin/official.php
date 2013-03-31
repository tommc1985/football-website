<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Officials
 */
class Official extends CI_Controller/*Backend_Controller*/
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
        $this->load->model('Official_model');
        $this->load->config('official', true);
    }

    /**
     * Index Action - Show Paginated List of Officials
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

        $data['officials'] = $this->Official_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = '/admin/official/index/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->Official_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/official/index', $data);
    }

    /**
     * Add Action - Add a Official
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = 'Save';

        $this->Official_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Official_model->processInsert();

            $official = $this->Official_model->fetch($insertId);

            $this->session->set_flashdata('message', "{$official->first_name} {$official->surname} has been added");
            redirect('/admin/official');
        }

        $this->load->view('admin/official/add', $data);
    }

    /**
     * Edit Action - Edit a Official
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = 'Save';

        $official = false;
        if ($parameters['id'] !== false) {
            $official = $this->Official_model->fetch($parameters['id']);
        }

        if (empty($official)) {
            $this->load->view('admin/official/not_found', $data);
            return;
        }

        $this->Official_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Official_model->processUpdate($parameters['id']);

            $official = $this->Official_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', "{$official->first_name} {$official->surname} has been updated");
            redirect('/admin/official');
        }

        $data['official'] = $official;

        $this->load->view('admin/official/edit', $data);
    }

    /**
     * Delete Action - Delete a Official
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $official = false;
        if ($parameters['id'] !== false) {
            $official = $this->Official_model->fetch($parameters['id']);
        }

        if (empty($official)) {
            $this->load->view('admin/official/not_found', $data);
            return;
        }

        $data['official'] = $official;

        if (!$this->Official_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/official/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->Official_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', "{$data['official']->first_name} {$data['official']->surname} has been deleted");
            redirect('/admin/official');
        }

        $this->load->view('admin/official/delete', $data);
    }
}

/* End of file official.php */
/* Location: ./application/controllers/admin/official.php */