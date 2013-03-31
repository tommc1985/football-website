<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$competition_id = array(
    'name'    => 'competition_id',
    'id'      => 'competition_id',
    'options' => array('' => '--- Select ---') + $this->Competition_model->fetchForDropdown(),
    'value'   => set_value('competition_id'),
);

$season = array(
    'name'    => 'season',
    'id'      => 'season',
    'options' => array('' => '--- Select ---') + $this->Season_model->fetchForDropdown(),
    'value'   => set_value('season'),
);

$name = array(
    'name'    => 'name',
    'id'      => 'name',
    'value'   => set_value('name'),
);

$short_name = array(
    'name'    => 'short_name',
    'id'      => 'short_name',
    'value'   => set_value('short_name'),
);

$abbreviation = array(
    'name'    => 'abbreviation',
    'id'      => 'abbreviation',
    'value'   => set_value('abbreviation'),
);

$points_for_win = array(
    'name'    => 'points_for_win',
    'id'      => 'points_for_win',
    'value'   => set_value('points_for_win'),
);

$points_for_draw = array(
    'name'    => 'points_for_draw',
    'id'      => 'points_for_draw',
    'value'   => set_value('points_for_draw'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($league->id) ? $league->id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('league_competition'), $competition_id['name']); ?></td>
        <td><?php echo form_dropdown($competition_id['name'], $competition_id['options'], set_value($competition_id['name'], isset($league->competition_id) ? $league->competition_id : '')); ?></td>
        <td class="error"><?php echo form_error($competition_id['name']); ?><?php echo isset($errors[$competition_id['name']]) ? $errors[$competition_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_season'), $season['name']); ?></td>
        <td><?php echo form_dropdown($season['name'], $season['options'], set_value($season['name'], isset($league->season) ? $league->season : '')); ?></td>
        <td class="error"><?php echo form_error($season['name']); ?><?php echo isset($errors[$season['name']]) ? $errors[$season['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_name'), $name['name']); ?></td>
        <td><?php echo form_input($name['name'], set_value($name['name'], isset($league->name) ? $league->name : '')); ?></td>
        <td class="error"><?php echo form_error($name['name']); ?><?php echo isset($errors[$name['name']]) ? $errors[$name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_short_name'), $short_name['name']); ?></td>
        <td><?php echo form_input($short_name['name'], set_value($short_name['name'], isset($league->short_name) ? $league->short_name : '')); ?></td>
        <td class="error"><?php echo form_error($short_name['name']); ?><?php echo isset($errors[$short_name['name']]) ? $errors[$short_name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_abbreviation'), $abbreviation['name']); ?></td>
        <td><?php echo form_input($abbreviation['name'], set_value($abbreviation['name'], isset($league->abbreviation) ? $league->abbreviation : '')); ?></td>
        <td class="error"><?php echo form_error($abbreviation['name']); ?><?php echo isset($errors[$abbreviation['name']]) ? $errors[$abbreviation['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('league_points_for_win'), $points_for_win['name']); ?></td>
        <td><?php echo form_input($points_for_win['name'], set_value($points_for_win['name'], isset($league->points_for_win) ? $league->points_for_win : '')); ?></td>
        <td class="error"><?php echo form_error($points_for_win['name']); ?><?php echo isset($errors[$points_for_win['name']]) ? $errors[$points_for_win['name']] : ''; ?></td>
    </tr>
        <td><?php echo form_label($this->lang->line('league_points_for_draw'), $points_for_draw['name']); ?></td>
        <td><?php echo form_input($points_for_draw['name'], set_value($points_for_draw['name'], isset($league->points_for_draw) ? $league->points_for_draw : '')); ?></td>
        <td class="error"><?php echo form_error($points_for_draw['name']); ?><?php echo isset($errors[$points_for_draw['name']]) ? $errors[$points_for_draw['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>