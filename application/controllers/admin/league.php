<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Leagues
 */
class League extends CI_Controller/*Backend_Controller*/
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
        $this->load->model('League_model');
        $this->load->config('league', true);
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

        $config['base_url'] = '/admin/league/index/offset/';
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

        $data['submitButtonText'] = 'Save';

        $this->League_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->League_model->insertEntry(array(
                'competition_id' => $this->form_validation->set_value('competition_id', NULL),
                'season' => $this->form_validation->set_value('season', NULL),
                'name' => $this->form_validation->set_value('name', NULL),
                'short_name' => $this->form_validation->set_value('short_name', NULL),
                'abbreviation' => $this->form_validation->set_value('abbreviation', NULL),
                'points_for_win' => $this->form_validation->set_value('points_for_win', NULL),
                'points_for_draw' => $this->form_validation->set_value('points_for_draw', NULL),
            ));

            $league = $this->League_model->fetch($insertId);

            $this->session->set_flashdata('message', "League has been added");
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

        $data['submitButtonText'] = 'Save';

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
            $this->League_model->updateEntry($parameters['id'], array(
                'competition_id' => $this->form_validation->set_value('competition_id', NULL),
                'season' => $this->form_validation->set_value('season', NULL),
                'name' => $this->form_validation->set_value('name', NULL),
                'short_name' => $this->form_validation->set_value('short_name', NULL),
                'abbreviation' => $this->form_validation->set_value('abbreviation', NULL),
                'points_for_win' => $this->form_validation->set_value('points_for_win', NULL),
                'points_for_draw' => $this->form_validation->set_value('points_for_draw', NULL),
            ));

            $league = $this->League_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', "League has been updated");
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
            $this->load->view('admin/league/not_found', $data);
            return;
        }

        $data['league'] = $league;

        if ($this->input->post('confirm_delete') !== false) {
            $this->League_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', "League has been deleted");
            redirect('/admin/league');
        }

        $this->load->view('admin/league/delete', $data);
    }
}

/* End of file league.php */
/* Location: ./application/controllers/league.php */