<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$playerId = array(
    'name'    => 'player_id',
    'id'      => 'player_id',
    'options' => array('' => '--- Select ---') + $this->Player_model->fetchForDropdown(),
    'value'   => set_value('player_id'),
);

$awardId = array(
    'name'    => 'award_id',
    'id'      => 'award-id',
    'options' => array('' => '--- Select ---') + $this->Award_model->fetchForDropdown(),
    'value'   => set_value('award_id'),
);

$season = array(
    'name'    => 'season',
    'id'      => 'season',
    'options' => array('' => '--- Select ---') + $this->Season_model->fetchForDropdown(),
    'value'   => set_value('season'),
);

$placing = array(
    'name'    => 'placing',
    'id'      => 'placing',
    'options' => array('' => '--- Select ---') + $this->Player_To_Award_model->fetchPlacingForDropdown(),
    'value'   => set_value('placing'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($playerAward->id) ? $playerAward->id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('player_to_award_player'), $playerId['name']); ?></td>
        <td><?php echo form_dropdown($playerId['name'], $playerId['options'], set_value($playerId['name'], isset($playerAward->player_id) ? $playerAward->player_id : '')); ?></td>
        <td class="error"><?php echo form_error($playerId['name']); ?><?php echo isset($errors[$playerId['name']]) ? $errors[$playerId['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('player_to_award_award'), $awardId['name']); ?></td>
        <td><?php echo form_dropdown($awardId['name'], $awardId['options'], set_value($awardId['name'], isset($playerAward->player_id) ? $playerAward->player_id : '')); ?></td>
        <td class="error"><?php echo form_error($awardId['name']); ?><?php echo isset($errors[$awardId['name']]) ? $errors[$awardId['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('player_to_award_season'), $season['name']); ?></td>
        <td><?php echo form_dropdown($season['name'], $season['options'], set_value($season['name'], isset($playerAward->season) ? $playerAward->season : '')); ?></td>
        <td class="error"><?php echo form_error($season['name']); ?><?php echo isset($errors[$season['name']]) ? $errors[$season['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('player_to_award_placing'), $placing['name']); ?></td>
        <td><?php echo form_dropdown($placing['name'], $placing['options'], set_value($placing['name'], isset($playerAward->placing) ? $playerAward->placing : '')); ?></td>
        <td class="error"><?php echo form_error($placing['name']); ?><?php echo isset($errors[$placing['name']]) ? $errors[$placing['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>