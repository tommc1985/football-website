<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$league_id = array(
    'name'    => 'league_id',
    'id'      => 'league_id',
    'options' => array('' => '--- Select ---'),
    'value'   => set_value('league_id'),
);

$opposition_id = array(
    'name'    => 'opposition_id',
    'id'      => 'opposition_id',
    'options' => array('' => '--- Select ---'),
    'value'   => set_value('opposition_id'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($leagueRegistration->id) ? $leagueRegistration->id : '')); ?>
    <tr>
        <td><?php echo form_label('League', $league_id['name']); ?></td>
        <td><?php echo form_dropdown($league_id['name'], $league_id['options'], set_value($league_id['name'], isset($leagueRegistration->league_id) ? $leagueRegistration->league_id : '')); ?></td>
        <td class="error"><?php echo form_error($league_id['name']); ?><?php echo isset($errors[$league_id['name']]) ? $errors[$league_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Team', $opposition_id['name']); ?></td>
        <td><?php echo form_dropdown($opposition_id['name'], $opposition_id['options'], set_value($opposition_id['name'], isset($leagueRegistration->opposition_id) ? $leagueRegistration->opposition_id : '')); ?></td>
        <td class="error"><?php echo form_error($opposition_id['name']); ?><?php echo isset($errors[$opposition_id['name']]) ? $errors[$opposition_id['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>