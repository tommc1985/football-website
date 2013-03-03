<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Goals
 */
class Goal extends CI_Controller/*Backend_Controller*/
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
        $this->load->model('Goal_model');
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
     * Edit Action - Edit the Goals for a Match
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

            $this->session->set_flashdata('message', "Goals for Match {$match->id} have been updated");
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

        if ($value == $assisterIdValues[$index] && $value != '0') {
            $this->form_validation->set_message('is_same_assister', 'A player cannot assist themselves');
            return FALSE;
        }

        return TRUE;
    }
}

/* End of file goal.php */
/* Location: ./application/controllers/admin/goal.php */