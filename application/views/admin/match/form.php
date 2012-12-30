<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$opposition = array(
    'name'    => 'opposition',
    'id'      => 'opposition',
    'options' => array('' => '--- Select ---'),
    'value'   => set_value('opposition'),
);

$competition = array(
    'name'    => 'competition',
    'id'      => 'competition',
    'options' => array('' => '--- Select ---'),
    'value'   => set_value('competition'),
);

$stage = array(
    'name'    => 'stage',
    'id'      => 'stage',
    'options' => array('' => '--- Select ---'),
    'value'   => set_value('stage'),
);

$venue = array(
    'name'    => 'venue',
    'id'      => 'venue',
    'options' => array('' => '--- Select ---'),
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
    'options' => array('' => '--- Select ---'),
    'value'   => set_value('official_id'),
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
    'options' => array('' => '--- Select ---'),
    'value'   => set_value('status'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($match->id) ? $match->id : '')); ?>
    <tr>
        <td><?php echo form_label('Date', $date['name']); ?></td>
        <td><?php echo form_datetime($date['name'], set_value($date['name'], isset($match->date) ? $match->date : '')); ?></td>
        <td class="error"><?php echo form_error($date['name']); ?><?php echo isset($errors[$date['name']]) ? $errors[$date['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Opposition', $opposition['name']); ?></td>
        <td><?php echo form_dropdown($opposition['name'], $opposition['options'], set_value($opposition['name'], isset($match->opposition) ? $match->opposition : '')); ?></td>
        <td class="error"><?php echo form_error($opposition['name']); ?><?php echo isset($errors[$opposition['name']]) ? $errors[$opposition['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Competition', $competition['name']); ?></td>
        <td><?php echo form_dropdown($competition['name'], $competition['options'], set_value($competition['name'], isset($match->competition) ? $match->competition : '')); ?></td>
        <td class="error"><?php echo form_error($competition['name']); ?><?php echo isset($errors[$competition['name']]) ? $errors[$competition['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Stage', $stage['name']); ?></td>
        <td><?php echo form_dropdown($stage['name'], $stage['options'], set_value($stage['name'], isset($match->stage) ? $match->stage : '')); ?></td>
        <td class="error"><?php echo form_error($stage['name']); ?><?php echo isset($errors[$stage['name']]) ? $errors[$stage['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Venue', $venue['name']); ?></td>
        <td><?php echo form_dropdown($venue['name'], $venue['options'], set_value($venue['name'], isset($match->venue) ? $match->venue : '')); ?></td>
        <td class="error"><?php echo form_error($venue['name']); ?><?php echo isset($errors[$venue['name']]) ? $errors[$venue['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Location', $location['name']); ?></td>
        <td><?php echo form_input($location['name'], set_value($location['name'], isset($match->location) ? $match->location : '')); ?></td>
        <td class="error"><?php echo form_error($location['name']); ?><?php echo isset($errors[$location['name']]) ? $errors[$location['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Official', $official_id['name']); ?></td>
        <td><?php echo form_dropdown($official_id['name'], $official_id['options'], set_value($official_id['name'], isset($match->official_id) ? $match->official_id : '')); ?></td>
        <td class="error"><?php echo form_error($official_id['name']); ?><?php echo isset($errors[$official_id['name']]) ? $errors[$official_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Your Score', $h['name']); ?></td>
        <td><?php echo form_input($h['name'], set_value($h['name'], isset($match->h) ? $match->h : '')); ?></td>
        <td class="error"><?php echo form_error($h['name']); ?><?php echo isset($errors[$h['name']]) ? $errors[$h['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Opposition Score', $a['name']); ?></td>
        <td><?php echo form_input($a['name'], set_value($a['name'], isset($match->a) ? $match->a : '')); ?></td>
        <td class="error"><?php echo form_error($a['name']); ?><?php echo isset($errors[$a['name']]) ? $errors[$a['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Report', $report['name']); ?></td>
        <td><?php echo form_input($report['name'], set_value($report['name'], isset($match->report) ? $match->report : '')); ?></td>
        <td class="error"><?php echo form_error($report['name']); ?><?php echo isset($errors[$report['name']]) ? $errors[$report['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Your Score', $h_et['name']); ?></td>
        <td><?php echo form_input($h_et['name'], set_value($h_et['name'], isset($match->h_et) ? $match->h_et : '')); ?></td>
        <td class="error"><?php echo form_error($h_et['name']); ?><?php echo isset($errors[$h_et['name']]) ? $errors[$h_et['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Opposition Score', $a_et['name']); ?></td>
        <td><?php echo form_input($a_et['name'], set_value($a_et['name'], isset($match->a_et) ? $match->a_et : '')); ?></td>
        <td class="error"><?php echo form_error($a_et['name']); ?><?php echo isset($errors[$a_et['name']]) ? $errors[$a_et['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Your Penalty Score', $h_pen['name']); ?></td>
        <td><?php echo form_input($h_pen['name'], set_value($h_pen['name'], isset($match->h_pen) ? $match->h_pen : '')); ?></td>
        <td class="error"><?php echo form_error($h_pen['name']); ?><?php echo isset($errors[$h_pen['name']]) ? $errors[$h_pen['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Opposition Penalty Score', $a_pen['name']); ?></td>
        <td><?php echo form_input($a_pen['name'], set_value($a_pen['name'], isset($match->a_pen) ? $match->a_pen : '')); ?></td>
        <td class="error"><?php echo form_error($a_pen['name']); ?><?php echo isset($errors[$a_pen['name']]) ? $errors[$a_pen['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Status', $status['name']); ?></td>
        <td><?php echo form_dropdown($status['name'], $official_id['options'], set_value($status['name'], isset($match->status) ? $match->status : '')); ?></td>
        <td class="error"><?php echo form_error($status['name']); ?><?php echo isset($errors[$status['name']]) ? $errors[$status['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>