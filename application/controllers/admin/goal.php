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

        $data['goals'] = $this->Goal_model->fetch($match->id);

        $this->Goal_model->formValidation();

        if ($this->form_validation->run() !== false) {
            $matchId = $parameters['id'];

            $j = 1;
            foreach ($this->playerCounts as $appearanceType => $playerCount) {
                $i = 0;
                while($i < $playerCount) {
                    /*$id       = $this->form_validation->set_value("id[{$appearanceType}][{$i}]", '');
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
                    }*/

                    $i++;
                    $j++;
                }
            }

            $this->session->set_flashdata('message', "Goals for Match {$match->id} have been updated");
            redirect('/admin/match');
        }

        $data['match'] = $match;

        $this->load->view('admin/goal/edit', $data);
    }
}

/* End of file goal.php */
/* Location: ./application/controllers/admin/goal.php */