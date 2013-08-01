<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Player Awards
 */
class Player_Award extends Backend_Controller
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
        $this->load->model('Player_model');
        $this->load->model('Player_To_Award_model');
        $this->load->model('Season_model');
        $this->load->config('player_to_award', true);

        $this->lang->load('player_to_award');
        $this->load->helper(array('award', 'player', 'utility'));
    }

    /**
     * Index Action - Show Paginated List of Player Awards
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

        $data['playerAwards'] = $this->Player_To_Award_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = site_url('admin/player-award/index') . '/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->Player_To_Award_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;
        $config + Pagination_helper::settings();

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/player-award/index', $data);
    }

    /**
     * Add Action - Add a Player Award
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = $this->lang->line('player_to_award_add');

        $this->Player_To_Award_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Player_To_Award_model->processInsert();

            $playerAward = $this->Player_To_Award_model->fetch($insertId);

            $this->session->set_flashdata('message', sprintf($this->lang->line('player_to_award_added'), $playerAward->id));
            redirect('/admin/player-award');
        }

        $this->load->view('admin/player-award/add', $data);
    }

    /**
     * Edit Action - Edit a Player Award
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('player_to_award_save');

        $playerAward = false;
        if ($parameters['id'] !== false) {
            $playerAward = $this->Player_To_Award_model->fetch($parameters['id']);
        }

        if (empty($playerAward)) {
            $this->load->view('admin/player-award/not_found', $data);
            return;
        }

        $this->Player_To_Award_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Player_To_Award_model->processUpdate($parameters['id']);

            $playerAward = $this->Player_To_Award_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', sprintf($this->lang->line('player_to_award_updated'), $playerAward->id));
            redirect('/admin/player-award');
        }

        $data['playerAward'] = $playerAward;

        $this->load->view('admin/player-award/edit', $data);
    }

    /**
     * Delete Action - Delete a Player Award
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $playerAward = false;
        if ($parameters['id'] !== false) {
            $playerAward = $this->Player_To_Award_model->fetch($parameters['id']);
        }

        if (empty($playerAward)) {
            $this->load->view('admin/player-award/not_found', $data);
            return;
        }

        $data['playerAward'] = $playerAward;

        if (!$this->Player_To_Award_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/player-award/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->Player_To_Award_model->deleteEntry($parameters['id']);

            $this->session->set_flashdata('message', sprintf($this->lang->line('player_to_award_deleted'), $playerAward->id));
            redirect('/admin/player-award');
        }

        $this->load->view('admin/player-award/delete', $data);
    }
}

/* End of file player_to_award.php */
/* Location: ./application/controllers/admin/player_to_award.php */