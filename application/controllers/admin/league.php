<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Leagues
 */
class League extends Backend_Controller
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
        $this->load->model('Competition_model');
        $this->load->model('League_model');
        $this->load->model('Season_model');
        $this->load->config('league', true);

        $this->lang->load('league');
    }

    /**
     * Index Action - Show Paginated List of Leagues
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

        $order = 'desc';
        if ($parameters['order'] !== false && strlen($parameters['offset']) > 0) {
            $order = $parameters['order'];
        }
        $data['leagues'] = $this->League_model->fetchAll($perPage, $offset, $parameters['order-by'], $order);

        $config['base_url'] = '/admin/league/index/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->League_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/league/index', $data);
    }

    /**
     * Add Action - Add a League
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = $this->lang->line('league_add');

        $this->League_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->League_model->processInsert();

            $league = $this->League_model->fetch($insertId);

            $this->Cache_League_model->insertEntry($league->id);
            $this->Cache_League_Statistics_model->insertEntries($league->id);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_added'), $league->name));
            redirect('/admin/league');
        }

        $this->load->view('admin/league/add', $data);
    }

    /**
     * Edit Action - Edit a League
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('league_save');

        $league = false;
        if ($parameters['id'] !== false) {
            $league = $this->League_model->fetch($parameters['id']);
        }

        if (empty($league)) {
            $this->load->view('admin/league/not_found', $data);
            return;
        }

        $this->League_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->League_model->processUpdate($parameters['id']);

            $league = $this->League_model->fetch($parameters['id']);

            $this->Cache_League_model->insertEntry($league->id);
            $this->Cache_League_Statistics_model->insertEntries($league->id);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_updated'), $league->name));
            redirect('/admin/league');
        }

        $data['league'] = $league;

        $this->load->view('admin/league/edit', $data);
    }

    /**
     * Delete Action - Delete a League
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $league = false;
        if ($parameters['id'] !== false) {
            $league = $this->League_model->fetch($parameters['id']);
        }

        if (empty($league)) {
            $this->load->view('admin/league/not_found');
            return;
        }

        $data['league'] = $league;

        if (!$this->League_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/league/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->League_model->deleteEntry($parameters['id']);

            $this->Cache_League_model->insertEntry($parameters['id']);
            $this->Cache_League_Statistics_model->insertEntries($parameters['id']);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_deleted'), $league->name));
            redirect('/admin/league');
        }

        $this->load->view('admin/league/delete', $data);
    }
}

/* End of file league.php */
/* Location: ./application/controllers/admin/league.php */