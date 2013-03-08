<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('backend_controller.php');

/**
 * The Backend Controller for managing Red & Yellow Cards
 */
class Card extends CI_Controller/*Backend_Controller*/
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
        $this->load->model('Card_model');
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
            $this->load->view('admin/card/not_found', $data);
            return;
        }

        if (is_null($match->h)) {
            $this->load->view('admin/card/no_result', $data);
            return;
        }

        $data['cards'] = $this->Card_model->fetch($match->id);

        $cardCount = 1; //@TODO

        $this->Goal_model->formValidation($cardCount);

        if ($this->form_validation->run() !== false) {
            $matchId = $parameters['id'];

            $i = 0;
            while($i < $cardCount) {
                $id          = $this->form_validation->set_value("id[{$i}]", '');
                $playerId    = $this->form_validation->set_value("player_id[{$i}]", '');
                $type        = $this->form_validation->set_value("type[{$i}]", '');
                $minute      = $this->form_validation->set_value("minute[{$i}]", '');
                $offence     = $this->form_validation->set_value("offence[{$i}]", '');

                if (empty($id)) { // Insert
                    if (!empty($minute)) {
                        $this->Card_model->insertEntry(array(
                            'match_id'    => $matchId,
                            'player_id'   => $playerId,
                            'type'        => $type,
                            'minute'      => $minute,
                            'offence'     => $offence,
                        ));
                    }
                } else { // Update or Delete
                    if (empty($minute)) { // Delete
                        $this->Card_model->deleteEntry($id);
                    } else { // Update
                        $this->Card_model->updateEntry($id, array(
                            'match_id'    => $matchId,
                            'player_id'   => $playerId,
                            'type'        => $type,
                            'minute'      => $minute,
                            'offence'     => $offence,
                        ));
                    }
                }

                $i++;
            }

            $this->session->set_flashdata('message', "Card info for Match {$match->id} have been updated");
            redirect('/admin/match');
        }

        $data['match'] = $match;

        $this->load->view('admin/card/edit', $data);
    }
}

/* End of file card.php */
/* Location: ./application/controllers/admin/card.php */