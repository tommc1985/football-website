<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$opposition_id = array(
    'name'    => 'opposition_id',
    'id'      => 'opposition_id',
    'options' => array('' => '--- Select ---') + $this->Opposition_model->fetchForDropdown(),
    'value'   => set_value('opposition_id'),
);

$competition_id = array(
    'name'    => 'competition_id',
    'id'      => 'competition_id',
    'options' => array('' => '--- Select ---') + $this->Competition_model->fetchForDropdown(),
    'value'   => set_value('competition_id'),
);

$competition_stage_id = array(
    'name'    => 'competition_stage_id',
    'id'      => 'competition_stage_id',
    'options' => array('' => '--- Select ---') + $this->Competition_Stage_model->fetchForDropdown(),
    'value'   => set_value('competition_stage_id'),
);

$venue = array(
    'name'    => 'venue',
    'id'      => 'venue',
    'options' => array('' => '--- Select ---') + $this->Match_model->fetchVenues(),
    'value'   => set_value('venue'),
);

$location = array(
    'name'  => 'location',
    'id'    => 'location',
    'value' => set_value('location'),
);

$official_id = array(
    'name'    => 'official_id',
    'id'      => 'official_id',
    'options' => array('0' => '--- Select ---') + $this->Official_model->fetchForDropdown(),
    'value'   => set_value('official_id'),
);

$attendance = array(
    'name'  => 'attendance',
    'id'    => 'attendance',
    'value' => set_value('attendance'),
);

$h = array(
    'name'  => 'h',
    'id'    => 'h',
    'value' => set_value('h'),
);

$a = array(
    'name'  => 'a',
    'id'    => 'a',
    'value' => set_value('a'),
);

$report = array(
    'name'  => 'report',
    'id'    => 'report',
    'value' => set_value('report'),
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

$h_et = array(
    'name'  => 'h_et',
    'id'    => 'h_et',
    'value' => set_value('h_et'),
);

$a_et = array(
    'name'  => 'a_et',
    'id'    => 'a_et',
    'value' => set_value('a_et'),
);

$h_pen = array(
    'name'  => 'h_pen',
    'id'    => 'h_pen',
    'value' => set_value('h_pen'),
);

$a_pen = array(
    'name'  => 'a_pen',
    'id'    => 'a_pen',
    'value' => set_value('a_pen'),
);

$status = array(
    'name'    => 'status',
    'id'      => 'status',
    'options' => array('' => '--- Select ---') + $this->Match_model->fetchStatuses(),
    'value'   => set_value('status'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($match->id) ? $match->id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('match_date'), $date['name']); ?></td>
        <td><?php echo form_date($date['name'], set_value($date['name'], isset($match->date) ? substr($match->date, 0, 10) : '')); ?></td>
        <td class="error"><?php echo form_error($date['name']); ?><?php echo isset($errors[$date['name']]) ? $errors[$date['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_time'), $time['name']); ?></td>
        <td><?php echo form_time($time['name'], set_value($time['name'], isset($match->date) ? substr($match->date, 11, 5) : Configuration::get('usual_match_ko_time'))); ?></td>
        <td class="error"><?php echo form_error($time['name']); ?><?php echo isset($errors[$time['name']]) ? $errors[$time['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_opposition'), $opposition_id['name']); ?></td>
        <td><?php echo form_dropdown($opposition_id['name'], $opposition_id['options'], set_value($opposition_id['name'], isset($match->opposition_id) ? $match->opposition_id : '')); ?></td>
        <td class="error"><?php echo form_error($opposition_id['name']); ?><?php echo isset($errors[$opposition_id['name']]) ? $errors[$opposition_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_competition'), $competition_id['name']); ?></td>
        <td><?php echo form_dropdown($competition_id['name'], $competition_id['options'], set_value($competition_id['name'], isset($match->competition_id) ? $match->competition_id : '')); ?></td>
        <td class="error"><?php echo form_error($competition_id['name']); ?><?php echo isset($errors[$competition_id['name']]) ? $errors[$competition_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_stage'), $competition_stage_id['name']); ?></td>
        <td><?php echo form_dropdown($competition_stage_id['name'], $competition_stage_id['options'], set_value($competition_stage_id['name'], isset($match->competition_stage_id) ? $match->competition_stage_id : '')); ?></td>
        <td class="error"><?php echo form_error($competition_stage_id['name']); ?><?php echo isset($errors[$competition_stage_id['name']]) ? $errors[$competition_stage_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_venue'), $venue['name']); ?></td>
        <td><?php echo form_dropdown($venue['name'], $venue['options'], set_value($venue['name'], isset($match->venue) ? $match->venue : '')); ?></td>
        <td class="error"><?php echo form_error($venue['name']); ?><?php echo isset($errors[$venue['name']]) ? $errors[$venue['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_location'), $location['name']); ?></td>
        <td><?php echo form_input($location['name'], set_value($location['name'], isset($match->location) ? $match->location : '')); ?></td>
        <td class="error"><?php echo form_error($location['name']); ?><?php echo isset($errors[$location['name']]) ? $errors[$location['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_official'), $official_id['name']); ?></td>
        <td><?php echo form_dropdown($official_id['name'], $official_id['options'], set_value($official_id['name'], isset($match->official_id) ? $match->official_id : '')); ?></td>
        <td class="error"><?php echo form_error($official_id['name']); ?><?php echo isset($errors[$official_id['name']]) ? $errors[$official_id['name']] : ''; ?></td>
    </tr><?php
    if (Configuration::get('include_match_attendances') === true) { ?>
    <tr>
        <td><?php echo form_label($this->lang->line('match_attendance'), $attendance['name']); ?></td>
        <td><?php echo form_input($attendance['name'], set_value($attendance['name'], isset($match->attendance) ? $match->attendance : '')); ?></td>
        <td class="error"><?php echo form_error($attendance['name']); ?><?php echo isset($errors[$attendance['name']]) ? $errors[$attendance['name']] : ''; ?></td>
    </tr><?php
    } ?>
    <tr>
        <td><?php echo form_label($this->lang->line('match_your_score'), $h['name']); ?></td>
        <td><?php echo form_input($h['name'], set_value($h['name'], isset($match->h) ? $match->h : '')); ?></td>
        <td class="error"><?php echo form_error($h['name']); ?><?php echo isset($errors[$h['name']]) ? $errors[$h['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_opposition_score'), $a['name']); ?></td>
        <td><?php echo form_input($a['name'], set_value($a['name'], isset($match->a) ? $match->a : '')); ?></td>
        <td class="error"><?php echo form_error($a['name']); ?><?php echo isset($errors[$a['name']]) ? $errors[$a['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_report'), $report['name']); ?></td>
        <td><?php echo form_textarea($report['name'], set_value($report['name'], isset($match->report) ? $match->report : '')); ?></td>
        <td class="error"><?php echo form_error($report['name']); ?><?php echo isset($errors[$report['name']]) ? $errors[$report['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_your_score_et'), $h_et['name']); ?></td>
        <td><?php echo form_input($h_et['name'], set_value($h_et['name'], isset($match->h_et) ? $match->h_et : '')); ?></td>
        <td class="error"><?php echo form_error($h_et['name']); ?><?php echo isset($errors[$h_et['name']]) ? $errors[$h_et['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_opposition_score_et'), $a_et['name']); ?></td>
        <td><?php echo form_input($a_et['name'], set_value($a_et['name'], isset($match->a_et) ? $match->a_et : '')); ?></td>
        <td class="error"><?php echo form_error($a_et['name']); ?><?php echo isset($errors[$a_et['name']]) ? $errors[$a_et['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_your_score_penalties'), $h_pen['name']); ?></td>
        <td><?php echo form_input($h_pen['name'], set_value($h_pen['name'], isset($match->h_pen) ? $match->h_pen : '')); ?></td>
        <td class="error"><?php echo form_error($h_pen['name']); ?><?php echo isset($errors[$h_pen['name']]) ? $errors[$h_pen['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_opposition_score_penalties'), $a_pen['name']); ?></td>
        <td><?php echo form_input($a_pen['name'], set_value($a_pen['name'], isset($match->a_pen) ? $match->a_pen : '')); ?></td>
        <td class="error"><?php echo form_error($a_pen['name']); ?><?php echo isset($errors[$a_pen['name']]) ? $errors[$a_pen['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('match_status'), $status['name']); ?></td>
        <td><?php echo form_dropdown($status['name'], $status['options'], set_value($status['name'], isset($match->status) ? $match->status : '')); ?></td>
        <td class="error"><?php echo form_error($status['name']); ?><?php echo isset($errors[$status['name']]) ? $errors[$status['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>