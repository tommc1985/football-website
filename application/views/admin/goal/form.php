<?php
$match_id = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id', $match->id),
);

$i = 0;
while($i < $goalCount) {
    $id[$i] = array(
        'name'  => "id[{$i}]",
        'id'    => "id_{$i}",
        'value' => set_value("id[{$i}]", isset($goals[$i]->id) ? $goals[$i]->id : ''),
    );

    $i++;
}

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden($match_id['name'], $match_id['value']);
    echo form_error('match_id', '<tr class="error"><td colspan="9">', '</td></tr>'); ?>
    <tr>
        <td><?php echo form_label('Minute', ''); ?></td>
        <td><?php echo form_label('Scorer', ''); ?></td>
        <td><?php echo form_label('Assister', ''); ?></td>
        <td><?php echo form_label('Type', ''); ?></td>
        <td><?php echo form_label('Body Part', ''); ?></td>
        <td><?php echo form_label('Distance', ''); ?></td>
        <td><?php echo form_label('Rating', ''); ?></td>
        <td><?php echo form_label('Description', ''); ?></td>
    </tr>
    <?php
    $i = 0;
    while($i < $goalCount) { ?>
    <tr>
        <td><?php echo form_hidden($id[$appearanceType][$i]['name'], $id[$appearanceType][$i]['value']); ?><?php echo form_dropdown($player_id[$appearanceType][$i]['name'], $player_id[$appearanceType][$i]['options'], $player_id[$appearanceType][$i]['value']); ?></td>
        <td><?php echo $appearanceType == 'starts' ? form_radio($captain[$appearanceType][$i]) : ''; ?></td>
        <td><?php echo form_input($rating[$appearanceType][$i]); ?></td>
        <td><?php echo form_radio($motm[$appearanceType][$i]); ?></td>
        <td><?php echo form_checkbox($injury[$appearanceType][$i]); ?></td>
        <td><?php echo form_dropdown($position[$appearanceType][$i]['name'], $position[$appearanceType][$i]['options'], $position[$appearanceType][$i]['value']); ?></td>
        <td><?php echo form_input($shirt[$appearanceType][$i]); ?></td>
        <td><?php echo $appearanceType == 'starts' ? '' : form_input($on[$appearanceType][$i]); ?></td>
        <td><?php echo form_input($off[$appearanceType][$i]); ?></td>
    </tr>
    <?php
        echo form_error($id[$appearanceType][$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($player_id[$appearanceType][$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($rating[$appearanceType][$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($injury[$appearanceType][$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($position[$appearanceType][$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($shirt[$appearanceType][$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($on[$appearanceType][$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($off[$appearanceType][$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        $i++;
    } ?>
</table>

<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>