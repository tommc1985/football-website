<?php
$match_id = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id', $match->id),
);

$i = 0;
while($i < $cardCount) {
    $id[$i] = array(
        'name'  => "id[{$i}]",
        'id'    => "id_{$i}",
        'value' => set_value("id[{$i}]", isset($cards[$i]->id) ? $cards[$i]->id : ''),
    );

    $minute[$i] = array(
        'name'    => "minute[{$i}]",
        'id'      => "minute_{$i}",
        'options' => array('' => '--- Select ---') + Match_model::fetchMinutes(),
        'value'   => set_value("minute[{$i}]", isset($cards[$i]->minute) ? $cards[$i]->minute : ''),
    );

    $playerId[$i] = array(
        'name'  => "player_id[{$i}]",
        'id'    => "player_id{$i}",
        'options' => array('' => '--- Select ---') + $this->Appearance_model->fetchForDropdown($match->id),
        'value' => set_value("player_id[{$i}]", isset($cards[$i]->player_id) ? $cards[$i]->player_id : ''),
    );

    $offence[$i] = array(
        'name'  => "offence[{$i}]",
        'id'    => "offence{$i}",
        'options' => array('' => '--- Select ---') + Card_model::fetchOffencesForDropdown(),
        'value' => set_value("offence[{$i}]", isset($cards[$i]->offence) ? $cards[$i]->offence : ''),
    );

    $i++;
}

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden($match_id['name'], $match_id['value']);
    echo form_error('match_id', '<tr class="error"><td colspan="3">', '</td></tr>'); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('card_minute'), ''); ?></td>
        <td><?php echo form_label($this->lang->line('card_player'), ''); ?></td>
        <td><?php echo form_label($this->lang->line('card_offence'), ''); ?></td>
    </tr>
    <?php
    $i = 0;
    while($i < $cardCount) { ?>
    <tr>
        <td><?php echo form_hidden($id[$i]['name'], $id[$i]['value']); ?><?php echo form_dropdown($minute[$i]['name'], $minute[$i]['options'], $minute[$i]['value']); ?></td>
        <td><?php echo form_dropdown($playerId[$i]['name'], $playerId[$i]['options'], $playerId[$i]['value']); ?></td>
        <td><?php echo form_dropdown($offence[$i]['name'], $offence[$i]['options'], $offence[$i]['value']); ?></td>
    </tr>
    <?php
        echo form_error($id[$i]['name'], '<tr class="error"><td colspan="3">', '</td></tr>');
        echo form_error($minute[$i]['name'], '<tr class="error"><td colspan="3">', '</td></tr>');
        echo form_error($playerId[$i]['name'], '<tr class="error"><td colspan="3">', '</td></tr>');
        echo form_error($offence[$i]['name'], '<tr class="error"><td colspan="3">', '</td></tr>');

        $i++;
    } ?>
</table>

<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>