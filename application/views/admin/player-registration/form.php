<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$player_id = array(
    'name'    => 'player_id',
    'id'      => 'player_id',
    'options' => array('' => '--- Select ---') + $this->Player_model->fetchForDropdown(),
    'value'   => set_value('player_id'),
);

$season = array(
    'name'    => 'season',
    'id'      => 'season',
    'options' => array('' => '--- Select ---') + $this->Season_model->fetchForDropdown(),
    'value'   => set_value('season'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($playerRegistration->id) ? $playerRegistration->id : '')); ?>
    <tr>
        <td><?php echo form_label('Player', $player_id['name']); ?></td>
        <td><?php echo form_dropdown($player_id['name'], $player_id['options'], set_value($player_id['name'], isset($playerRegistration->player_id) ? $playerRegistration->player_id : '')); ?></td>
        <td class="error"><?php echo form_error($player_id['name']); ?><?php echo isset($errors[$player_id['name']]) ? $errors[$player_id['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Season', $season['name']); ?></td>
        <td><?php echo form_dropdown($season['name'], $season['options'], set_value($season['name'], isset($playerRegistration->season) ? $playerRegistration->season : '')); ?></td>
        <td class="error"><?php echo form_error($season['name']); ?><?php echo isset($errors[$season['name']]) ? $errors[$season['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>