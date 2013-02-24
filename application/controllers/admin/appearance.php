<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Matches
 */
class Appearance extends CI_Controller/*Backend_Controller*/
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
        $this->load->model('Appearance_model');
        $this->load->model('Competition_model');
        $this->load->model('Competition_Stage_model');
        $this->load->model('Match_model');
        $this->load->model('Official_model');
        $this->load->model('Opposition_model');
        $this->load->model('Player_model');
        $this->load->model('Player_Registration_model');
        $this->load->model('Position_model');
        $this->load->model('Season_model');
        $this->load->config('match', true);
    }

    /**
     * Edit Action - Edit the Appearances for a Match
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
            $this->load->view('admin/appearance/not_found', $data);
            return;
        }

        $data['appearances'] = $this->Appearance_model->fetch($match->id);
        $data['season'] = Season_model::fetchSeasonFromDateTime($match->date);
        $competition = $this->Competition_model->fetch($match->competition_id);

        $data['playerCounts'] = array(
            'starts' => $competition->starts,
            'subs' => $competition->subs);
        $this->playerCounts = $data['playerCounts'];

        $this->Appearance_model->formValidation($data['playerCounts']);

        if ($this->form_validation->run() !== false) {
            /*$this->Match_model->updateEntry($parameters['id'], array(
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

            $match = $this->Match_model->fetch($parameters['id']);*/

            //$this->session->set_flashdata('message', "Appearances have been updated");
            //redirect('/admin/appearance');
        }

        $data['match'] = $match;

        $this->load->view('admin/appearance/edit', $data);
    }

    /**
     * Check if the player has only been selected once
     * @param  int  $playerId The specified Player ID
     * @return boolean        Has the player been chosen more than once for the same game?
     */
    public function is_unique_player_id($playerId)
    {
        $values = array();
        $playerIdValues = $this->input->post("player_id");

        foreach ($this->playerCounts as $appearanceType => $playerCount) {
            $i = 0;
            while($i < $playerCount) {
                $value = $playerIdValues[$appearanceType][$i];

                if (!empty($value)) {
                    $values[] = $value;
                }

                $i++;
            }
        }

        $valuesCounted = array_count_values($values);

        if (isset($valuesCounted[$playerId]) && $valuesCounted[$playerId] > 1)
        {
            $this->form_validation->set_message('is_unique_player_id', 'This Player has been selected more than once for the same match');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}

/* End of file appearance.php */
/* Location: ./application/controllers/appearance.php */