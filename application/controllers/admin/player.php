<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

class Player extends CI_Controller/*Backend_Controller*/
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->model('Player_model');
        $this->load->config('player', true);
    }

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

        $config['base_url'] = '/admin/player/index/offset/';
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

    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));
        $this->load->library('form_validation');

        $data['submitButtonText'] = 'Save';

        $this->Player_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Player_model->insertEntry(array(
                'first_name' => $this->form_validation->set_value('first_name', NULL),
                'surname' => $this->form_validation->set_value('surname', NULL),
                'dob' => $this->form_validation->set_value('dob', NULL),
                'nationality' => $this->form_validation->set_value('nationality', NULL),
                'profile' => $this->form_validation->set_value('profile', NULL),
                'current' => $this->form_validation->set_value('current', NULL),
                'image_id' => $this->form_validation->set_value('image_id', NULL),
                'gender' => $this->form_validation->set_value('gender', NULL),
            ));

            $this->session->set_flashdata('message', 'Player Added');
            redirect('/admin/player');
        }

        $this->load->view('admin/player/add', $data);
    }

    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));
        $this->load->library('form_validation');

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
            $this->Player_model->updateEntry($parameters['id'], array(
                'first_name' => $this->form_validation->set_value('first_name', NULL),
                'surname' => $this->form_validation->set_value('surname', NULL),
                'dob' => $this->form_validation->set_value('dob', NULL),
                'nationality' => $this->form_validation->set_value('nationality', NULL),
                'profile' => $this->form_validation->set_value('profile', NULL),
                'current' => $this->form_validation->set_value('current', NULL),
                'image_id' => $this->form_validation->set_value('image_id', NULL),
                'gender' => $this->form_validation->set_value('gender', NULL),
            ));

            $this->session->set_flashdata('message', 'Player Updated');
            redirect('/admin/player');
        }

        $data['player'] = $player[0];

        $this->load->view('admin/player/edit', $data);
    }

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

        $data['player'] = $player[0];

        if ($this->input->post('confirm_delete') !== false) {
            $this->Player_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', "{$data['player']->first_name} {$data['player']->surname} has been deleted");
            redirect('/admin/player');
        }

        $this->load->view('admin/player/delete', $data);
    }
}

/* End of file player.php */
/* Location: ./application/controllers/player.php */