<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing League Registrations
 */
class League_Registration extends Backend_Controller
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
        $this->load->model('League_model');
        $this->load->model('League_Registration_model');
        $this->load->model('Opposition_model');
        $this->load->config('league_registration', true);

        $this->lang->load('league_registration');
        $this->load->helper(array('league', 'opposition', 'utility'));
    }

    /**
     * Index Action - Show Paginated List of League Registrations
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

        $data['leagueRegistrations'] = $this->League_Registration_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = site_url('admin/league-registration/index') . '/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->League_Registration_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;
        $config + Pagination_helper::settings();

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/league-registration/index', $data);
    }

    /**
     * Add Action - Add a League Registration
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = $this->lang->line('league_registration_add');

        $this->League_Registration_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->League_Registration_model->processInsert();

            $leagueRegistration = $this->League_Registration_model->fetch($insertId);

            $this->Cache_League_model->insertEntry($leagueRegistration->league_id);
            $this->Cache_League_Statistics_model->insertEntries($leagueRegistration->league_id);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_registration_added'), $leagueRegistration->id));
            redirect('/admin/league-registration');
        }

        $this->load->view('admin/league-registration/add', $data);
    }

    /**
     * Edit Action - Edit a League Registration
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('league_registration_save');

        $leagueRegistration = false;
        if ($parameters['id'] !== false) {
            $leagueRegistration = $this->League_Registration_model->fetch($parameters['id']);
        }

        if (empty($leagueRegistration)) {
            $this->load->view('admin/league-registration/not_found', $data);
            return;
        }

        $this->League_Registration_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->League_Registration_model->processUpdate($parameters['id']);

            $leagueRegistration = $this->League_Registration_model->fetch($parameters['id']);

            $this->Cache_League_model->insertEntry($leagueRegistration->league_id);
            $this->Cache_League_Statistics_model->insertEntries($leagueRegistration->league_id);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_registration_updated'), $leagueRegistration->id));
            redirect('/admin/league-registration');
        }

        $data['leagueRegistration'] = $leagueRegistration;

        $this->load->view('admin/league-registration/edit', $data);
    }

    /**
     * Delete Action - Delete a League Registration
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $leagueRegistration = false;
        if ($parameters['id'] !== false) {
            $leagueRegistration = $this->League_Registration_model->fetch($parameters['id']);
        }

        if (empty($leagueRegistration)) {
            $this->load->view('admin/league-registration/not_found', $data);
            return;
        }

        $data['leagueRegistration'] = $leagueRegistration;

        if (!$this->League_Registration_model->isDeletable($leagueRegistration->opposition_id)) {
            $this->load->view('admin/league-registration/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->League_Registration_model->deleteEntry($parameters['id']);

            $this->Cache_League_model->insertEntry($leagueRegistration->league_id);
            $this->Cache_League_Statistics_model->insertEntries($leagueRegistration->league_id);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_registration_deleted'), $leagueRegistration->id));
            redirect('/admin/league-registration');
        }

        $this->load->view('admin/league-registration/delete', $data);
    }
}

/* End of file league_registration.php */
/* Location: ./application/controllers/admin/league_registration.php */