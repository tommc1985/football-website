<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('frontend_controller.php');

/**
 * The Frontend Controller for managing Motm Votes
 */
class Motm extends Frontend_Controller
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
        $this->load->model('Cache_model');
        $this->load->model('Competition_model');
        $this->load->model('Competition_Stage_model');
        $this->load->model('Goal_model');
        $this->load->model('Match_model');
        $this->load->model('Motm_model');
        $this->load->model('Official_model');
        $this->load->model('Opposition_model');
        $this->load->model('Player_model');
        $this->load->model('Player_Registration_model');
        $this->load->model('Position_model');
        $this->load->model('Season_model');
        $this->load->config('match', true);

        $this->lang->load('match');
        $this->lang->load('motm');
    }

    /**
     * Index Action - List matches that can be voted on
     * @return NULL
     */
    public function index()
    {

    }

    /**
     * Vote Action - Edit the Goals for a Match
     * @return NULL
     */
    public function vote()
    {
        $this->load->helper(array('form', 'match', 'official', 'opposition', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(3, array('id'));

        $this->templateData['submitButtonText'] = $this->lang->line('motm_vote');

        $match = false;
        if ($parameters['id'] !== false) {
            $match = $this->Match_model->fetch($parameters['id']);
        }

        if (empty($match)) {
            $this->load->view("themes/{$this->theme}/motm/not_found", $this->templateData);
            return;
        }

        if (is_null($match->h)) {
            $this->load->view("themes/{$this->theme}/motm/no_result", $this->templateData);
            return;
        }

        $userId = 1;
        $this->placingCount = Configuration::get('motm_placings');

        $this->templateData['votes'] = $this->Motm_model->fetch_by_user($match->id, $userId);

        $this->Motm_model->formValidation($this->placingCount);

        if ($this->form_validation->run() !== false) {
            $oldData = $this->templateData['votes'];
            $matchId = $parameters['id'];

            $i = 0;
            while($i < $this->placingCount) {
                $id          = $this->form_validation->set_value("id[{$i}]", '');
                $playerId    = $this->form_validation->set_value("player_id[{$i}]", '');
                $placing     = $i + 1;

                if (empty($id)) { // Insert
                    if (!empty($playerId)) {
                        $this->Motm_model->insertEntry(array(
                            'match_id'  => $matchId,
                            'player_id' => $playerId,
                            'placing'   => $placing,
                            'user_id'   => $userId,
                        ));
                    }
                } else { // Update or Delete
                    if (empty($playerId)) { // Delete
                        $this->Motm_model->deleteEntry($id);
                    } else { // Update
                        $this->Motm_model->updateEntry($id, array(
                            'match_id'  => $matchId,
                            'player_id' => $playerId,
                            'placing'   => $placing,
                            'user_id'   => $userId,
                        ));
                    }
                }

                $i++;
            }

            $newData = $this->Motm_model->fetch_by_user($match->id, $userId);

            if ($this->Motm_model->isDifferent($oldData, $newData)) {
                /*$matchSeason = Season_model::fetchSeasonFromDateTime($match->date);

                $this->Cache_Club_Statistics_model->insertEntries($matchSeason);
                $this->Cache_Fantasy_Football_model->insertEntries($matchSeason);
                $this->Cache_Player_Accumulated_Statistics_model->insertEntries($matchSeason);
                $this->Cache_Player_Goals_Statistics_model->insertEntries($matchSeason);
                $this->Cache_Player_Milestones_model->insertEntries($matchSeason);
                $this->Cache_Player_Statistics_model->insertEntries($matchSeason);*/
            }

            $this->session->set_flashdata('message', sprintf($this->lang->line('motm_data_updated'), $match->id));
            redirect('/motm');
        }

        $this->templateData['preview']      = false;
        $this->templateData['match']        = $match;
        $this->templateData['placingCount'] =  $this->placingCount;

        $this->load->view("themes/{$this->theme}/header", $this->templateData);
        $this->load->view("themes/{$this->theme}/motm/vote", $this->templateData);
        $this->load->view("themes/{$this->theme}/footer", $this->templateData);
    }

    /**
     * Check if the player has only been selected once
     * @param  int  $playerId The specified Player ID
     * @return boolean        Has the player been chosen more than once for the same Motm vote?
     */
    public function is_unique_player_id($playerId)
    {
        $values = array();
        $playerIdValues = $this->input->post("player_id");

        $i = 0;
        while($i < $this->placingCount) {
            $value = $playerIdValues[$i];

            if (!empty($value)) {
                $values[] = $value;
            }

            $i++;
        }

        $valuesCounted = array_count_values($values);

        if (isset($valuesCounted[$playerId]) && $valuesCounted[$playerId] > 1)
        {
            $this->form_validation->set_message('is_unique_player_id', $this->lang->line('motm_player_selected_more_than_once'));
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}

/* End of file motm.php */
/* Location: ./application/controllers/motm.php */