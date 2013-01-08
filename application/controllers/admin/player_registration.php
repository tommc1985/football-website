<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Player Registrations
 */
class Player_Registration extends CI_Controller/*Backend_Controller*/
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
        $this->load->model('Player_model');
        $this->load->model('Player_Registration_model');
        $this->load->model('Season_model');
        $this->load->config('player_registration', true);
    }

    /**
     * Index Action - Show Paginated List of Player Registrations
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

        $data['playerRegistrations'] = $this->Player_Registration_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = '/admin/player-registration/index/offset/';
        $config['total_rows'] = $this->Player_Registration_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/player-registration/index', $data);
    }

    /**
     * Add Action - Add a Player Registration
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = 'Save';

        $this->Player_Registration_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Player_Registration_model->insertEntry(array(
                'player_id' => $this->form_validation->set_value('player_id', NULL),
                'season' => $this->form_validation->set_value('season', NULL),
            ));

            $playerRegistration = $this->Player_Registration_model->fetch($insertId);

            $this->session->set_flashdata('message', "Player Registration has been added");
            redirect('/admin/player-registration');
        }

        $this->load->view('admin/player-registration/add', $data);
    }

    /**
     * Edit Action - Edit a Player Registration
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = 'Save';

        $playerRegistration = false;
        if ($parameters['id'] !== false) {
            $playerRegistration = $this->Player_Registration_model->fetch($parameters['id']);
        }

        if (empty($playerRegistration)) {
            $this->load->view('admin/player-registration/not_found', $data);
            return;
        }

        $this->Player_Registration_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Player_Registration_model->updateEntry($parameters['id'], array(
                'player_id' => $this->form_validation->set_value('player_id', NULL),
                'season' => $this->form_validation->set_value('season', NULL),
            ));

            $playerRegistration = $this->Player_Registration_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', "Player Registration has been updated");
            redirect('/admin/player-registration');
        }

        $data['playerRegistration'] = $playerRegistration;

        $this->load->view('admin/player-registration/edit', $data);
    }

    /**
     * Delete Action - Delete a Player Registration
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $playerRegistration = false;
        if ($parameters['id'] !== false) {
            $playerRegistration = $this->Player_Registration_model->fetch($parameters['id']);
        }

        if (empty($playerRegistration)) {
            $this->load->view('admin/player-registration/not_found', $data);
            return;
        }

        $data['playerRegistration'] = $playerRegistration;

        if ($this->input->post('confirm_delete') !== false) {
            $this->Player_Registration_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', "Player Registration has been deleted");
            redirect('/admin/player-registration');
        }

        $this->load->view('admin/player-registration/delete', $data);
    }
}

/* End of file player_registration.php */
/* Location: ./application/controllers/player_registration.php */