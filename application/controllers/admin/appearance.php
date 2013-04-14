<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Appearances
 */
class Appearance extends Backend_Controller
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
        $this->load->model('Match_model');
        $this->load->model('Official_model');
        $this->load->model('Opposition_model');
        $this->load->model('Player_model');
        $this->load->model('Player_Registration_model');
        $this->load->model('Position_model');
        $this->load->model('Season_model');
        $this->load->config('match', true);

        $this->lang->load('appearance');
    }

    /**
     * Edit Action - Edit the Appearances for a Match
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('appearance_save');

        $match = false;
        if ($parameters['id'] !== false) {
            $match = $this->Match_model->fetch($parameters['id']);
        }

        if (empty($match)) {
            $this->load->view('admin/appearance/not_found', $data);
            return;
        }

        if (is_null($match->h)) {
            $this->load->view('admin/appearance/no_result', $data);
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
            $oldData = $data['appearances'];

            $matchId = $parameters['id'];
            $selectedCaptain = $this->form_validation->set_value("captain", '');
            $selectedMotm = $this->form_validation->set_value("motm", '');
            $injuries = $this->input->post("injury", '');

            $j = 1;
            foreach ($this->playerCounts as $appearanceType => $playerCount) {
                $i = 0;
                while($i < $playerCount) {
                    $id       = $this->form_validation->set_value("id[{$appearanceType}][{$i}]", '');
                    $playerId = $this->form_validation->set_value("player_id[{$appearanceType}][{$i}]", '');
                    $captain  = $selectedCaptain == ($j - 1) ? 1 : 0;
                    $rating   = $this->form_validation->set_value("rating[{$appearanceType}][{$i}]", '');
                    $motm     = $selectedMotm == "{$appearanceType}_{$i}" ? 1 : 0;
                    $injury   = isset($injuries[$appearanceType]) && in_array($i, $injuries[$appearanceType]) ? 1 : 0;
                    $position = $this->form_validation->set_value("position[{$appearanceType}][{$i}]", '');
                    $order    = $j;
                    $shirt    = $this->form_validation->set_value("shirt[{$appearanceType}][{$i}]", '');
                    $on       = $this->form_validation->set_value("on[{$appearanceType}][{$i}]", '');
                    $on       = empty($on) ? NULL : $on;
                    $off      = $this->form_validation->set_value("off[{$appearanceType}][{$i}]", '');
                    $off      = empty($off) ? NULL : $off;
                    $status   = $appearanceType == 'starts' ? 'starter' : (is_null($on) ? 'unused' : 'substitute');

                    if (empty($id)) { // Insert
                        if (!empty($playerId)) {
                            $this->Appearance_model->insertEntry(array(
                                'match_id' => $matchId,
                                'player_id' => $playerId,
                                'captain' => $captain,
                                'rating' => $rating,
                                'motm' => $motm,
                                'injury' => $injury,
                                'position' => $position,
                                'order' => $order,
                                'shirt' => $shirt,
                                'status' => $status,
                                'on' => $on,
                                'off' => $off,
                            ));
                        }
                    } else { // Update or Delete
                        if (empty($playerId)) { // Delete
                            $this->Appearance_model->deleteEntry($id);
                        } else { // Update
                            $this->Appearance_model->updateEntry($id, array(
                                'match_id' => $matchId,
                                'player_id' => $playerId,
                                'captain' => $captain,
                                'rating' => $rating,
                                'motm' => $motm,
                                'injury' => $injury,
                                'position' => $position,
                                'order' => $order,
                                'shirt' => $shirt,
                                'status' => $status,
                                'on' => $on,
                                'off' => $off,
                            ));
                        }
                    }

                    $i++;
                    $j++;
                }
                $newData = $this->Appearance_model->fetch($match->id);

                if ($this->Appearance_model->isDifferent($oldData, $newData)) {
                    $matchSeason = Season_model::fetchSeasonFromDateTime($match->date);

                    $this->Cache_Club_Statistics_model->insertEntries($matchSeason);
                    $this->Cache_Fantasy_Football_model->insertEntries($matchSeason);
                    $this->Cache_Player_Accumulated_Statistics_model->insertEntries($matchSeason);
                    $this->Cache_Player_Milestones_model->insertEntries($matchSeason);
                    $this->Cache_Player_Statistics_model->insertEntries($matchSeason);
                }
            }

            $this->session->set_flashdata('message', sprintf($this->lang->line('appearance_data_updated'), $match->id));
            redirect('/admin/match');
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
            $this->form_validation->set_message('is_unique_player_id', $this->lang->line('appearance_player_selected_more_than_once'));
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    /**
     * Check if the selected captain is a valid choice
     * @param  int  $index    Index of the selected captain
     * @return boolean        Is the selected captain a valid choice
     */
    public function is_valid_captain($index)
    {
        $values = array();
        $playerIdValues = $this->input->post("player_id");

        $appearanceCount = 0;
        foreach ($this->playerCounts as $appearanceType => $playerCount) {
            $i = 0;
            while($i < $playerCount) {
                if ($playerIdValues[$appearanceType][$i] != '') {
                    $appearanceCount++;
                }
                $i++;
            }
        }

        if ($index == '') {
            if ($appearanceCount > 0) {
                $this->form_validation->set_message('is_valid_captain', $this->lang->line('appearance_no_captain_selected'));
                return FALSE;
            } else {
                return TRUE;
            }
        }

        if (isset($playerIdValues['starts'][$index])) {
            if ($playerIdValues['starts'][$index] != '') {
                return TRUE;
            }
        }

        $this->form_validation->set_message('is_valid_captain', $this->lang->line('appearance_captain_not_linked_to_starting_player'));
        return FALSE;
    }

    /**
     * Has a rating been set for the specified player
     * @param  int  $value    Rating Value
     * @param  int  $indexes  Indexes of Rating field
     * @return boolean        Has a Rating value been entered (if it should be)
     */
    public function is_rating_set($value, $indexes)
    {
        $values = array();
        $playerIdValues = $this->input->post("player_id");
        $onValues = $this->input->post("on");

        list($appearanceType, $index) = explode("_", $indexes);

        if ($value == '' &&  $playerIdValues[$appearanceType][$index] != '' && ($appearanceType == 'starts' || ($appearanceType == 'subs' && isset($onValues[$appearanceType][$index]) && $onValues[$appearanceType][$index] != ''))) {
            $this->form_validation->set_message('is_rating_set', $this->lang->line('appearance_player_rating_missing'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Has a Position been set for the specified player
     * @param  int  $value    Position Value
     * @param  int  $indexes  Indexes of Position field
     * @return boolean        Has a Position value been set (if it should be)
     */
    public function is_position_set($value, $indexes)
    {
        $values = array();
        $playerIdValues = $this->input->post("player_id");
        $onValues = $this->input->post("on");

        list($appearanceType, $index) = explode("_", $indexes);

        if ($value == '' &&  $playerIdValues[$appearanceType][$index] != '' && ($appearanceType == 'starts' || ($appearanceType == 'subs' && isset($onValues[$appearanceType][$index]) && $onValues[$appearanceType][$index] != ''))) {
            $this->form_validation->set_message('is_position_set', $this->lang->line('appearance_player_position_missing'));
            return FALSE;
        }

        return TRUE;
    }
}

/* End of file appearance.php */
/* Location: ./application/controllers/admin/appearance.php */