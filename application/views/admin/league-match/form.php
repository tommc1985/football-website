<?php
$this->load->model('League_model');
$this->load->model('League_Match_model');
$this->load->model('League_Registration_model');
$this->load->model('Opposition_model');

$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$league_id = array(
    'name'    => 'league_id',
    'id'      => 'league_id',
    'options' => array('' => '--- Select ---') + $this->League_model->fetchForDropdown(),
    'value'   => set_value('league_id'),
);

$date = array(
    'name'  => 'date',
    'id'    => 'date',
    'value' => set_value('date'),
);

$time = array(
    'name'  => 'time',
    'id'    => 'time',
    'value' => set_value('time'),
);

$h_opposition_id = array(
    'name'    => 'h_opposition_id',
    'id'      => 'h_opposition_id',
    'options' => array('' => '--- Select ---') + $this->League_Registration_model->fetchForDropdown($league->id),
    'value'   => set_value('h_opposition_id'),
);

$a_opposition_id = array(
    'name'    => 'a_opposition_id',
    'id'      => 'a_opposition_id',
    'options' => array('' => '--- Select ---') + $this->League_Registration_model->fetchForDropdown($league->id),
    'value'   => set_value('a_opposition_id'),
);

$h_score = array(
    'name'  => 'h_score',
    'id'    => 'h_score',
    'value' => set_value('h_score'),
);

$a_score = array(
    'name'  => 'a_score',
    'id'    => 'a_score',
    'value' => set_value('a_score'),
);

$status = array(
    'name'    => 'status',
    'id'      => 'status',
    'options' => array('' => '--- Select ---') + $this->League_Match_model->fetchStatuses(),
    'value'   => set_value('status'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($leagueMatch->id) ? $leagueMatch->id : '')); ?>
    <?php echo form_hidden('league_id', set_value('league_id', isset($league->id) ? $league->id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('league_match_league'), $league_id['name']); ?></td>
        <td><?php echo $league->short_name; ?></td>
        <td class="error"></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_match_date'), $date['name']); ?></td>
        <td><?php echo form_date($date['name'], set_value($date['name'], isset($leagueMatch->date) ? substr($leagueMatch->date, 0, 10) : '')); ?></td>
        <td class="error"><?php echo form_error($date['name']); ?><?php echo isset($errors[$date['name']]) ? $errors[$date['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_match_time'), $time['name']); ?></td>
        <td><?php echo form_time($time['name'], set_value($time['name'], isset($leagueMatch->date) ? substr($leagueMatch->date, 11, 5) : Configuration::get('usual_match_ko_time'))); ?></td>
        <td class="error"><?php echo form_error($time['name']); ?><?php echo isset($errors[$time['name']]) ? $errors[$time['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_match_home_team'), $h_opposition_id['name']); ?></td>
        <td><?php echo form_dropdown($h_opposition_id['name'], $h_opposition_id['options'], set_value($h_opposition_id['name'], isset($leagueMatch->h_opposition_id) ? $leagueMatch->h_opposition_id : '')); ?></td>
        <td class="error"><?php echo form_error($h_opposition_id['name']); ?><?php echo isset($errors[$h_opposition_id['name']]) ? $errors[$h_opposition_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_match_away_team'), $a_opposition_id['name']); ?></td>
        <td><?php echo form_dropdown($a_opposition_id['name'], $a_opposition_id['options'], set_value($a_opposition_id['name'], isset($leagueMatch->a_opposition_id) ? $leagueMatch->a_opposition_id : '')); ?></td>
        <td class="error"><?php echo form_error($a_opposition_id['name']); ?><?php echo isset($errors[$a_opposition_id['name']]) ? $errors[$a_opposition_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_match_home_score'), $h_score['name']); ?></td>
        <td><?php echo form_input($h_score['name'], set_value($h_score['name'], isset($leagueMatch->h_score) ? $leagueMatch->h_score : '')); ?></td>
        <td class="error"><?php echo form_error($h_score['name']); ?><?php echo isset($errors[$h_score['name']]) ? $errors[$h_score['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_match_away_score'), $a_score['name']); ?></td>
        <td><?php echo form_input($a_score['name'], set_value($a_score['name'], isset($leagueMatch->a_score) ? $leagueMatch->a_score : '')); ?></td>
        <td class="error"><?php echo form_error($a_score['name']); ?><?php echo isset($errors[$a_score['name']]) ? $errors[$a_score['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_match_status'), $status['name']); ?></td>
        <td><?php echo form_dropdown($status['name'], $status['options'], set_value($status['name'], isset($leagueMatch->status) ? $leagueMatch->status : '')); ?></td>
        <td class="error"><?php echo form_error($status['name']); ?><?php echo isset($errors[$status['name']]) ? $errors[$status['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>