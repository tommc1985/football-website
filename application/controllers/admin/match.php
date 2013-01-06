<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Matches
 */
class Match extends CI_Controller/*Backend_Controller*/
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
        $this->load->model('Competition_model');
        $this->load->model('Competition_Stage_model');
        $this->load->model('Match_model');
        $this->load->model('Official_model');
        $this->load->model('Opposition_model');
        $this->load->config('match', true);
    }

    /**
     * Index Action - Show Paginated List of Matches
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
        $data['matches'] = $this->Match_model->fetchAll($perPage, $offset, $parameters['order-by'], $order);

        $config['base_url'] = '/admin/match/index/offset/';
        $config['total_rows'] = $this->Match_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/match/index', $data);
    }

    /**
     * Add Action - Add a Match
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $data['submitButtonText'] = 'Save';

        $this->Match_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->Match_model->insertEntry(array(
                'opposition_id' => $this->form_validation->set_value('opposition_id', NULL),
                'competition_id' => $this->form_validation->set_value('competition_id', NULL),
                'competition_stage_id' => $this->form_validation->set_value('competition_stage_id', NULL),
                'venue' => $this->form_validation->set_value('venue', NULL),
                'location' => $this->form_validation->set_value('location', NULL),
                'official_id' => $this->form_validation->set_value('official_id', NULL),
                'h' => $this->form_validation->set_value('h', NULL),
                'a' => $this->form_validation->set_value('a', NULL),
                'report' => $this->form_validation->set_value('report', NULL),
                'date' => $this->form_validation->set_value('date', NULL) . ' ' . $this->form_validation->set_value('time', NULL) . ':00',
                'h_et' => $this->form_validation->set_value('h_et', NULL),
                'a_et' => $this->form_validation->set_value('a_et', NULL),
                'h_pen' => $this->form_validation->set_value('h_pen', NULL),
                'a_pen' => $this->form_validation->set_value('a_pen', NULL),
                'status' => $this->form_validation->set_value('status', NULL),
            ));

            $match = $this->Match_model->fetch($insertId);

            $this->session->set_flashdata('message', "Match has been added");
            redirect('/admin/match');
        }

        $this->load->view('admin/match/add', $data);
    }

    /**
     * Edit Action - Edit a Match
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = 'Save';

        $match = false;
        if ($parameters['id'] !== false) {
            $match = $this->Match_model->fetch($parameters['id']);
        }

        if (empty($match)) {
            $this->load->view('admin/match/not_found', $data);
            return;
        }

        $this->Match_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->Match_model->updateEntry($parameters['id'], array(
                'opposition_id' => $this->form_validation->set_value('opposition_id', NULL),
                'competition_id' => $this->form_validation->set_value('competition_id', NULL),
                'competition_stage_id' => $this->form_validation->set_value('competition_stage_id', NULL),
                'venue' => $this->form_validation->set_value('venue', NULL),
                'location' => $this->form_validation->set_value('location', NULL),
                'official_id' => $this->form_validation->set_value('official_id', NULL),
                'h' => $this->form_validation->set_value('h', NULL),
                'a' => $this->form_validation->set_value('a', NULL),
                'report' => $this->form_validation->set_value('report', NULL),
                'date' => $this->form_validation->set_value('date', NULL) . ' ' . $this->form_validation->set_value('time', NULL) . ':00',
                'h_et' => $this->form_validation->set_value('h_et', NULL),
                'a_et' => $this->form_validation->set_value('a_et', NULL),
                'h_pen' => $this->form_validation->set_value('h_pen', NULL),
                'a_pen' => $this->form_validation->set_value('a_pen', NULL),
                'status' => $this->form_validation->set_value('status', NULL),
            ));

            $match = $this->Match_model->fetch($parameters['id']);

            $this->session->set_flashdata('message', "Match has been updated");
            redirect('/admin/match');
        }

        $data['match'] = $match;

        $this->load->view('admin/match/edit', $data);
    }

    /**
     * Delete Action - Delete a Match
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $match = false;
        if ($parameters['id'] !== false) {
            $match = $this->Match_model->fetch($parameters['id']);
        }

        if (empty($match)) {
            $this->load->view('admin/match/not_found', $data);
            return;
        }

        $data['match'] = $match;

        if ($this->input->post('confirm_delete') !== false) {
            $this->Match_model->deleteEntry($parameters['id']);
            $this->session->set_flashdata('message', "Match has been deleted");
            redirect('/admin/match');
        }

        $this->load->view('admin/match/delete', $data);
    }
}

/* End of file match.php */
/* Location: ./application/controllers/match.php */