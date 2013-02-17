<?php
$match_id = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id'),
);

foreach ($playerCounts as $appearanceType => $playerCount) {
    $i = 0;
    while($i < $playerCount) {
        $id[$appearanceType][$i] = array(
            'name'  => "id[{$appearanceType}][{$i}]",
            'id'    => "id_{$appearanceType}_{$i}",
            'value' => set_value("id[{$appearanceType}][{$i}]"),
        );

        $player_id[$appearanceType][$i] = array(
            'name'    => "player_id[{$appearanceType}][{$i}]",
            'id'      => "player_id_{$appearanceType}_{$i}",
            'options' => array('' => '--- Select ---') + $this->Player_model->fetchForDropdown(),
            'value'   => set_value("player_id[{$appearanceType}][{$i}]"),
        );

        $captain[$appearanceType][$i] = array(
            'name'    => "captain",
            'id'      => "captain_{$appearanceType}_{$i}",
            'value'   => $i,
        );

        $rating[$appearanceType][$i] = array(
            'name'  => "rating[{$appearanceType}][{$i}]",
            'id'    => "rating_{$appearanceType}_{$i}",
            'value' => set_value("rating[{$appearanceType}][{$i}]"),
        );

        $motm[$appearanceType][$i] = array(
            'name'    => "motm",
            'id'      => "motm_{$appearanceType}_{$i}",
            'value'   => set_value("motm[{$appearanceType}][{$i}]"),
        );

        $injury[$appearanceType][$i] = array(
            'name'  => "injury[{$appearanceType}][{$i}]",
            'id'    => "injury_{$appearanceType}_{$i}",
            'value' => $i,
        );

        $position[$appearanceType][$i] = array(
            'name'  => "position[{$appearanceType}][{$i}]",
            'id'    => "position_{$appearanceType}_{$i}",
            'options' => array('' => '---') + $this->Position_model->fetchForDropdown(),
            'value' => set_value("position[{$appearanceType}][{$i}]"),
        );

        $order[$appearanceType][$i] = array(
            'name'  => "order[{$appearanceType}][{$i}]",
            'id'    => "order_{$appearanceType}_{$i}",
            'value' => ($i + 1),
        );

        $shirt[$appearanceType][$i] = array(
            'name'  => "shirt[{$appearanceType}][{$i}]",
            'id'    => "shirt_{$appearanceType}_{$i}",
            'value' => set_value("shirt[{$appearanceType}][{$i}]"),
        );

        $on[$appearanceType][$i] = array(
            'name'  => "on[{$appearanceType}][{$i}]",
            'id'    => "on_{$appearanceType}_{$i}",
            'value' => set_value("on[{$appearanceType}][{$i}]"),
        );

        $off[$appearanceType][$i] = array(
            'name'  => "off[{$appearanceType}][{$i}]",
            'id'    => "off_{$appearanceType}_{$i}",
            'value' => set_value("off[{$appearanceType}][{$i}]"),
        );

        $i++;
    }
}

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('match_id', set_value('match_id', isset($match->id) ? $match->id : '')); ?>
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
        <td><?php echo form_dropdown($player_id[$appearanceType][$i]['name'], $player_id[$appearanceType][$i]['options'], set_value($player_id[$appearanceType][$i]['value'])); ?></td>
        <td><?php echo form_radio($captain[$appearanceType][$i]); ?></td>
        <td><?php echo form_input($rating[$appearanceType][$i]); ?></td>
        <td><?php echo form_radio($motm[$appearanceType][$i]); ?></td>
        <td><?php echo form_checkbox($injury[$appearanceType][$i]); ?></td>
        <td><?php echo form_dropdown($position[$appearanceType][$i]['name'], $position[$appearanceType][$i]['options'], set_value($position[$appearanceType][$i]['value'])); ?></td>
        <td><?php echo form_input($shirt[$appearanceType][$i]); ?></td>
        <td><?php echo form_input($on[$appearanceType][$i]); ?></td>
        <td><?php echo form_input($off[$appearanceType][$i]); ?></td>
    </tr>
    <?php
            $i++;
        }
    } ?>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>