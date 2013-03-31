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
        $this->load->model('Cache_model');
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

        $this->lang->load('card');
    }

    /**
     * Edit Action - Edit the Goals for a Match
     * @return NULL
     */
    public function edit()
    {
        $this->load->helper(array('form', 'url', 'html5_form_fields'));

        $parameters = $this->uri->uri_to_assoc(4, array('id'));

        $data['submitButtonText'] = $this->lang->line('card_save');

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

        $cardCount = $this->input->post('id');

        $data['cards'] = $this->Card_model->fetch($match->id);

        $data['cardCount'] = ($cardCount !== false ? count($cardCount) : count($data['cards']) + 1);

        $this->Card_model->formValidation($data['cardCount']);

        if ($this->form_validation->run() !== false) {
            $oldData = $data['cards'];
            $matchId = $parameters['id'];

            $i = 0;
            while($i < $data['cardCount']) {
                $id              = $this->form_validation->set_value("id[{$i}]", '');
                $minute          = $this->form_validation->set_value("minute[{$i}]", '');
                $playerId        = $this->form_validation->set_value("player_id[{$i}]", '');
                $offence         = $this->form_validation->set_value("offence[{$i}]", '');
                $selectedOffence = Card_model::fetchOffence($offence);
                $type            = $selectedOffence['card'];

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

            $newData = $this->Card_model->fetch($match->id);

            if ($this->Card_model->isDifferent($oldData, $newData)) {
                $matchSeason = Season_model::fetchSeasonFromDateTime($match->date);

                $this->Cache_Fantasy_Football_model->insertEntries($matchSeason);
                $this->Cache_Player_Accumulated_Statistics_model->insertEntries($matchSeason);
                $this->Cache_Player_Statistics_model->insertEntries($matchSeason);
            }

            $this->session->set_flashdata('message', sprintf($this->lang->line('card_data_updated'), $match->id));
            redirect('/admin/match');
        }

        $data['match'] = $match;

        $this->load->view('admin/card/edit', $data);
    }

    /**
     * Has a Player been selected (only applicable if a minute is chosen)
     * @param  int  $value    Selected Player
     * @param  int  $index    Indexes of Minute field
     * @return boolean        Has a Player been selected (only applicable if a minute is chosen)
     */
    public function player_required($value, $index)
    {
        $values = array();
        $minuteValues = $this->input->post("minute");

        if ($minuteValues[$index] != '' && $value == '') {
            $this->form_validation->set_message('player_required', $this->lang->line('card_player_required'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Has an Offence been selected (only applicable if a minute is chosen)
     * @param  int  $value    Selected Offence
     * @param  int  $index    Indexes of Minute field
     * @return boolean        Has an Offence been selected (only applicable if a minute is chosen)
     */
    public function offence_required($value, $index)
    {
        $values = array();
        $minuteValues = $this->input->post("minute");

        if ($minuteValues[$index] != '' && $value == '') {
            $this->form_validation->set_message('offence_required', $this->lang->line('card_offence_required'));
            return FALSE;
        }

        return TRUE;
    }
}

/* End of file card.php */
/* Location: ./application/controllers/admin/card.php */