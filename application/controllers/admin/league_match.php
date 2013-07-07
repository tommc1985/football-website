<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing League Matches
 */
class League_Match extends Backend_Controller
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
        $this->load->model('League_Match_model');
        $this->load->model('League_Registration_model');
        $this->load->config('league_match', true);

        $this->load->helper(array('opposition'));

        $this->lang->load('league_match');
    }

    /**
     * Index Action - Show Paginated List of League Matches
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
        $data['leagueMatches'] = $this->League_Match_model->fetchAll($perPage, $offset, $parameters['order-by'], $order);

        $config['base_url'] = '/admin/league-match/index/';

        if ($parameters['order-by']) {
            $config['base_url'] .= "order-by/{$parameters['order-by']}/";
        }

        if ($parameters['order']) {
            $config['base_url'] .= "order/{$parameters['order']}/";
        }

        $config['base_url'] .= 'offset/';
        $config['total_rows'] = $this->League_Match_model->countAll();
        $config['per_page'] = $perPage;
        $config['cur_page'] = $offset;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($message = $this->session->flashdata('message')) {
            $data['message'] = $message;
        }

        $this->load->view('admin/league-match/index', $data);
    }

    /**
     * Add Action - Add a League Match
     * @return NULL
     */
    public function add()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('league-id'));

        $data['submitButtonText'] = $this->lang->line('league_match_add');

        $league = false;
        if ($parameters['league-id'] !== false) {
            $league = $this->League_model->fetch($parameters['league-id']);
        }

        if (empty($league)) {
            $this->load->view('admin/league/not_found', $data);
            return;
        }

        $data['league'] = $league;

        $this->League_Match_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $insertId = $this->League_Match_model->processInsert();

            $leagueMatch = $this->League_Match_model->fetch($insertId);

            $this->Cache_League_model->insertEntry($league->id);
            $this->Cache_League_Statistics_model->insertEntries($league->id);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_match_added'), $leagueMatch->id));
            redirect('/admin/league-match');
        }

        $this->load->view('admin/league-match/add', $data);
    }

    /**
     * Edit Action - Edit a League Match
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('league_match_save');

        $leagueMatch = false;
        if ($parameters['id'] !== false) {
            $leagueMatch = $this->League_Match_model->fetch($parameters['id']);
        }

        if (empty($leagueMatch)) {
            $this->load->view('admin/league-match/not_found', $data);
            return;
        }

        $data['league'] = $this->League_model->fetch($leagueMatch->league_id);

        $this->League_Match_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $this->League_Match_model->processUpdate($parameters['id']);

            $leagueMatch = $this->League_Match_model->fetch($parameters['id']);

            $this->Cache_League_model->insertEntry($leagueMatch->league_id);
            $this->Cache_League_Statistics_model->insertEntries($leagueMatch->league_id);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_match_updated'), $leagueMatch->id));
            redirect('/admin/league-match');
        }

        $data['leagueMatch'] = $leagueMatch;

        $this->load->view('admin/league-match/edit', $data);
    }

    /**
     * Delete Action - Delete a League Match
     * @return NULL
     */
    public function delete()
    {
        $this->load->helper(array('form', 'url'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $leagueMatch = false;
        if ($parameters['id'] !== false) {
            $leagueMatch = $this->League_Match_model->fetch($parameters['id']);
        }

        if (empty($leagueMatch)) {
            $this->load->view('admin/league-match/not_found', $data);
            return;
        }

        $data['leagueMatch'] = $leagueMatch;

        if (!$this->League_Match_model->isDeletable($parameters['id'])) {
            $this->load->view('admin/league-match/cannot_delete', $data);
            return;
        }

        if ($this->input->post('confirm_delete') !== false) {
            $this->League_Match_model->deleteEntry($parameters['id']);

            $this->Cache_League_model->insertEntry($leagueMatch->league_id);
            $this->Cache_League_Statistics_model->insertEntries($leagueMatch->league_id);

            $this->session->set_flashdata('message', sprintf($this->lang->line('league_match_deleted'), $leagueMatch->id));
            redirect('/admin/league-match');
        }

        $this->load->view('admin/league-match/delete', $data);
    }
}

/* End of file league_match.php */
/* Location: ./application/controllers/admin/league_match.php */