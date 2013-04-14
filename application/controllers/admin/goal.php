<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Goals
 */
class Goal extends Backend_Controller
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
        $this->load->model('Official_model');
        $this->load->model('Opposition_model');
        $this->load->model('Player_model');
        $this->load->model('Player_Registration_model');
        $this->load->model('Position_model');
        $this->load->model('Season_model');
        $this->load->config('match', true);

        $this->lang->load('goal');
    }

    /**
     * Edit Action - Edit the Goals for a Match
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('goal_save');

        $match = false;
        if ($parameters['id'] !== false) {
            $match = $this->Match_model->fetch($parameters['id']);
        }

        if (empty($match)) {
            $this->load->view('admin/goal/not_found', $data);
            return;
        }

        if (is_null($match->h)) {
            $this->load->view('admin/goal/no_result', $data);
            return;
        }

        if ($match->h == 0) {
            $this->load->view('admin/goal/no_goals', $data);
            return;
        }

        $data['goals'] = $this->Goal_model->fetch($match->id);

        $this->Goal_model->formValidation($match->h);

        if ($this->form_validation->run() !== false) {
            $oldData = $data['goals'];
            $matchId = $parameters['id'];

            $i = 0;
            while($i < $match->h) {
                $id          = $this->form_validation->set_value("id[{$i}]", '');
                $scorerId    = $this->form_validation->set_value("scorer_id[{$i}]", '');
                $assistId    = $this->form_validation->set_value("assist_id[{$i}]", '');
                $minute      = $this->form_validation->set_value("minute[{$i}]", '');
                $type        = $this->form_validation->set_value("type[{$i}]", '');
                $bodyPart    = $this->form_validation->set_value("body_part[{$i}]", '');
                $distance    = $this->form_validation->set_value("distance[{$i}]", '');
                $rating      = $this->form_validation->set_value("rating[{$i}]", '');
                $description = $this->form_validation->set_value("description[{$i}]", '');

                if (empty($id)) { // Insert
                    if (!empty($minute)) {
                        $this->Goal_model->insertEntry(array(
                            'match_id'    => $matchId,
                            'scorer_id'   => $scorerId,
                            'assist_id'   => $assistId,
                            'minute'      => $minute,
                            'type'        => $type,
                            'body_part'   => $bodyPart,
                            'distance'    => $distance,
                            'rating'      => $rating,
                            'description' => $description,
                        ));
                    }
                } else { // Update or Delete
                    if (empty($minute)) { // Delete
                        $this->Goal_model->deleteEntry($id);
                    } else { // Update
                        $this->Goal_model->updateEntry($id, array(
                            'match_id'    => $matchId,
                            'scorer_id'   => $scorerId,
                            'assist_id'   => $assistId,
                            'minute'      => $minute,
                            'type'        => $type,
                            'body_part'   => $bodyPart,
                            'distance'    => $distance,
                            'rating'      => $rating,
                            'description' => $description,
                        ));
                    }
                }

                $i++;
            }

            $newData = $this->Goal_model->fetch($match->id);

            if ($this->Goal_model->isDifferent($oldData, $newData)) {
                $matchSeason = Season_model::fetchSeasonFromDateTime($match->date);

                $this->Cache_Club_Statistics_model->insertEntries($matchSeason);
                $this->Cache_Fantasy_Football_model->insertEntries($matchSeason);
                $this->Cache_Player_Accumulated_Statistics_model->insertEntries($matchSeason);
                $this->Cache_Player_Goals_Statistics_model->insertEntries($matchSeason);
                $this->Cache_Player_Milestones_model->insertEntries($matchSeason);
                $this->Cache_Player_Statistics_model->insertEntries($matchSeason);
            }

            $this->session->set_flashdata('message', sprintf($this->lang->line('goal_data_updated'), $match->id));
            redirect('/admin/match');
        }

        $data['match'] = $match;

        $this->load->view('admin/goal/edit', $data);
    }

    /**
     * Has the scorer and assister been set to the same player
     * @param  int  $value    Selected Scorer Value
     * @param  int  $index    Indexes of Scorer field
     * @return boolean        Has the scorer and assister been set to the same player (and not o.g.)
     */
    public function is_same_assister($value, $index)
    {
        $values = array();
        $assisterIdValues = $this->input->post("assist_id");

        if ($value == $assisterIdValues[$index] && $value != '0' && $value != '') {
            $this->form_validation->set_message('is_same_assister', $this->lang->line('goal_same_assister'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * If the Goal Type field is set to "Own Goal", is the Scorer field set to "Own Goal" too
     * @param  int  $value    Selected Goal Type Value
     * @param  int  $index    Index of Goal Type field
     * @return boolean        If the Goal Type field is set to "Own Goal", is the Scorer field set to "Own Goal" too
     */
    public function is_own_goal($value, $index)
    {
        $values = array();
        $scorerIdValues = $this->input->post("scorer_id");

        if (($value == '0' && $scorerIdValues[$index] != '0') || ($value != '0' && $scorerIdValues[$index] == '0')) {
            $this->form_validation->set_message('is_own_goal', $this->lang->line('goal_is_own_goal'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * If the Minute field is set, is the specified field field set to "Own Goal" too
     * @param  int  $value    Selected Goal Type Value
     * @param  int  $index    Index of Goal Type field
     * @return boolean        If the Goal Type field is set to "Own Goal", is the Scorer field set to "Own Goal" too
     */
    public function is_required($value, $index)
    {
        $values = array();
        $scorerIdValues = $this->input->post("scorer_id");
        $assistIdValues = $this->input->post("assist_id");
        $typeValues     = $this->input->post("type");
        $bodyPartValues = $this->input->post("body_part");
        $distanceValues = $this->input->post("distance");
        $ratingValues   = $this->input->post("rating");

        if ($value != '') {
            switch (true) {
                case $scorerIdValues[$index] == '':
                    $this->form_validation->set_message('is_required', $this->lang->line('goal_scorer_required'));
                    return FALSE;
                    break;
                case $assistIdValues[$index] == '':
                    $this->form_validation->set_message('is_required', $this->lang->line('goal_assister_required'));
                    return FALSE;
                    break;
                case $typeValues[$index] == '':
                    $this->form_validation->set_message('is_required', $this->lang->line('goal_type_required'));
                    return FALSE;
                    break;
                case $bodyPartValues[$index] == '':
                    $this->form_validation->set_message('is_required', $this->lang->line('goal_body_part_required'));
                    return FALSE;
                    break;
                case $distanceValues[$index] == '':
                    $this->form_validation->set_message('is_required', $this->lang->line('goal_distance_required'));
                    return FALSE;
                    break;
                case $ratingValues[$index] == '':
                    $this->form_validation->set_message('is_required', $this->lang->line('goal_rating_required'));
                    return FALSE;
                    break;
            }
        }

        return TRUE;
    }
}

/* End of file goal.php */
/* Location: ./application/controllers/admin/goal.php */