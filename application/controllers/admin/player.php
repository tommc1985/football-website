<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Players
 */
class Player extends CI_Controller/*Backend_Controller*/
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
        $this->load->config('player', true);
    }

    /**
     * Index Action - Show Paginated List of Players
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

        $data['players'] = $this->Player_model->fetchAll($perPage, $offset, $parameters['order-by'], $parameters['order']);

        $config['base_url'] = '/admin/player/index/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->Player_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/player/index', $data);
    }

    /**
     * Add Action - Add a Player
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = 'Save';

        $this->Player_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Player_model->processInsert();

            $player = $this->Player_model->fetch($insertId);

            $this->session->set_flashdata('message', "{$player->first_name} {$player->surname} has been added");
            redirect('/admin/player');
        }

        $this->load->view('admin/player/add', $data);
    }

    /**
     * Edit Action - Edit a Player
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = 'Save';

        $player = false;
        if ($parameters['id'] !== false) {
            $player = $this->Player_model->fetch($parameters['id']);
        }

        if (empty($player)) {
            $this->load->view('admin/player/not_found', $data);
            return;
        }

        $this->Player_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Player_model->processUpdate($parameters['id']);

            $player = $this->Player_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', "{$player->first_name} {$player->surname} has been updated");
            redirect('/admin/player');
        }

        $data['player'] = $player;

        $this->load->view('admin/player/edit', $data);
    }

    /**
     * Delete Action - Delete a Player
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $player = false;
        if ($parameters['id'] !== false) {
            $player = $this->Player_model->fetch($parameters['id']);
        }

        if (empty($player)) {
            $this->load->view('admin/player/not_found', $data);
            return;
        }

        $data['player'] = $player;

        if (!$this->Player_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/player/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->Player_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', "{$data['player']->first_name} {$data['player']->surname} has been deleted");
            redirect('/admin/player');
        }

        $this->load->view('admin/player/delete', $data);
    }
}

/* End of file player.php */
/* Location: ./application/controllers/admin/player.php */