<?php
$match_id = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id', $match->id),
);

$noMotm = true;
foreach ($playerCounts as $appearanceType => $playerCount) {
    $i = 0;
    while($i < $playerCount) {
        $id[$appearanceType][$i] = array(
            'name'  => "id[{$appearanceType}][{$i}]",
            'id'    => "id_{$appearanceType}_{$i}",
            'value' => set_value("id[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->id) ? $appearances[$appearanceType][$i]->id : ''),
        );

        $player_id[$appearanceType][$i] = array(
            'name'    => "player_id[{$appearanceType}][{$i}]",
            'id'      => "player_id_{$appearanceType}_{$i}",
            'options' => array('' => '--- Select ---') + $this->Player_Registration_model->fetchForDropdown($season),
            'value'   => set_value("player_id[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->player_id) ? $appearances[$appearanceType][$i]->player_id : ''),
        );

        $captain[$appearanceType][$i] = array(
            'name'    => "captain",
            'id'      => "captain_{$appearanceType}_{$i}",
            'checked' => isset($appearances[$appearanceType][$i]->captain) && $appearances[$appearanceType][$i]->captain == 1 ? true : false,
            'value'   => $i,
        );

        $rating[$appearanceType][$i] = array(
            'name'  => "rating[{$appearanceType}][{$i}]",
            'id'    => "rating_{$appearanceType}_{$i}",
            'value' => set_value("rating[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->rating) ?$appearances[$appearanceType][$i]->rating : ''),
        );

        $motm[$appearanceType][$i] = array(
            'name'    => "motm",
            'id'      => "motm_{$appearanceType}_{$i}",
            'checked' => isset($appearances[$appearanceType][$i]->motm) && $appearances[$appearanceType][$i]->motm == 1 ? true : false,
            'value'   => $i,
        );

        if (isset($appearances[$appearanceType][$i]->motm) && $appearances[$appearanceType][$i]->motm == 1) {
            $noMotm = false;
        }

        $injury[$appearanceType][$i] = array(
            'name'  => "injury[{$appearanceType}][{$i}]",
            'id'    => "injury_{$appearanceType}_{$i}",
            'checked' => isset($appearances[$appearanceType][$i]->injury) && $appearances[$appearanceType][$i]->injury == 1 ? true : false,
            'value' => $i,
        );

        $position[$appearanceType][$i] = array(
            'name'  => "position[{$appearanceType}][{$i}]",
            'id'    => "position_{$appearanceType}_{$i}",
            'options' => array('' => '---') + $this->Position_model->fetchForDropdown(),
            'value' => set_value("position[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->position) ?$appearances[$appearanceType][$i]->position : ''),
        );

        $order[$appearanceType][$i] = array(
            'name'  => "order[{$appearanceType}][{$i}]",
            'id'    => "order_{$appearanceType}_{$i}",
            'value' => ($i + 1),
        );

        $shirt[$appearanceType][$i] = array(
            'name'  => "shirt[{$appearanceType}][{$i}]",
            'id'    => "shirt_{$appearanceType}_{$i}",
            'value' => set_value("shirt[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->shirt) ?$appearances[$appearanceType][$i]->shirt : ''),
        );

        $on[$appearanceType][$i] = array(
            'name'  => "on[{$appearanceType}][{$i}]",
            'id'    => "on_{$appearanceType}_{$i}",
            'value' => set_value("on[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->on) ?$appearances[$appearanceType][$i]->on : ''),
        );

        $off[$appearanceType][$i] = array(
            'name'  => "off[{$appearanceType}][{$i}]",
            'id'    => "off_{$appearanceType}_{$i}",
            'value' => set_value("off[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->off) ?$appearances[$appearanceType][$i]->off : '' ),
        );

        $i++;
    }
}

$motm['no_motm'] = array(
    'name'    => "motm",
    'id'      => "motm_no_motm",
    'checked' => $noMotm,
    'value'   => 0,
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden($match_id['name'], $match_id['value']);
    echo form_error('captain', '<tr class="error"><td colspan="9">', '</td></tr>');
    echo form_error('match_id', '<tr class="error"><td colspan="9">', '</td></tr>'); ?>
    <tr>
        <td><?php echo form_label('Player', ''); ?></td>
        <td><?php echo form_label('Captain', ''); ?></td>
        <td><?php echo form_label('Rating', ''); ?></td>
        <td><?php echo form_label('MOTM', ''); ?></td>
        <td><?php echo form_label('Injury', ''); ?></td>
        <td><?php echo form_label('Position', ''); ?></td>
        <td><?php echo form_label('Shirt', ''); ?></td>
        <td><?php echo form_label('On', ''); ?></td>
        <td><?php echo form_label('Off', ''); ?></td>
    </tr>
    <?php
    foreach ($playerCounts as $appearanceType => $playerCount) {
        $i = 0;
        while($i < $playerCount) { ?>
    <tr>
        <td><?php echo form_hidden($id[$appearanceType][$i]['name'], $id[$appearanceType][$i]['value']); ?><?php echo form_dropdown($player_id[$appearanceType][$i]['name'], $player_id[$appearanceType][$i]['options'], $player_id[$appearanceType][$i]['value']); ?></td>
        <td><?php echo form_radio($captain[$appearanceType][$i]); ?></td>
        <td><?php echo form_input($rating[$appearanceType][$i]); ?></td>
        <td><?php echo form_radio($motm[$appearanceType][$i]); ?></td>
        <td><?php echo form_checkbox($injury[$appearanceType][$i]); ?></td>
        <td><?php echo form_dropdown($position[$appearanceType][$i]['name'], $position[$appearanceType][$i]['options'], $position[$appearanceType][$i]['value']); ?></td>
        <td><?php echo form_input($shirt[$appearanceType][$i]); ?></td>
        <td><?php echo form_input($on[$appearanceType][$i]); ?></td>
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
        }
    } ?>
</table>

<table>
    <tr>
        <td><?php echo form_label('No Man of the Match', $motm['no_motm']['id']); ?></td>
        <td><?php echo form_radio($motm['no_motm']); ?></td>
    </tr>
<?php
    echo form_error('motm', '<tr class="error"><td colspan="2">', '</td></tr>'); ?>
</table>

<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>