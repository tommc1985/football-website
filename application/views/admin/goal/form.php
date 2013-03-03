<?php
$match_id = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id', $match->id),
);

$i = 0;
while($i < $match->h) {
    $id[$i] = array(
        'name'  => "id[{$i}]",
        'id'    => "id_{$i}",
        'value' => set_value("id[{$i}]", isset($goals[$i]->id) ? $goals[$i]->id : ''),
    );

    $minute[$i] = array(
        'name'    => "minute[{$i}]",
        'id'      => "minute{$i}",
        'options' => array('' => '--- Select ---') + Match_model::fetchMinutes(),
        'value'   => set_value("minute[{$i}]", isset($goals[$i]->minute) ? $goals[$i]->minute : ''),
    );

    $scorerId[$i] = array(
        'name'  => "scorer_id[{$i}]",
        'id'    => "scorer_id{$i}",
        'value' => set_value("scorer_id[{$i}]", isset($goals[$i]->scorer_id) ? $goals[$i]->scorer_id : ''),
    );

    $assistId[$i] = array(
        'name'  => "assist_id[{$i}]",
        'id'    => "assist_id{$i}",
        'value' => set_value("assist_id[{$i}]", isset($goals[$i]->assist_id) ? $goals[$i]->assist_id : ''),
    );

    $type[$i] = array(
        'name'  => "type[{$i}]",
        'id'    => "type{$i}",
        'options' => array('' => '--- Select ---') + Goal_model::fetchTypes(),
        'value' => set_value("type[{$i}]", isset($goals[$i]->type) ? $goals[$i]->type : ''),
    );

    $bodyPart[$i] = array(
        'name'  => "body_part[{$i}]",
        'id'    => "body_part{$i}",
        'options' => array('' => '--- Select ---') + Goal_model::fetchBodyParts(),
        'value' => set_value("body_part[{$i}]", isset($goals[$i]->body_part) ? $goals[$i]->body_part : ''),
    );

    $distance[$i] = array(
        'name'  => "distance[{$i}]",
        'id'    => "distance{$i}",
        'options' => array('' => '--- Select ---') + Goal_model::fetchDistances(),
        'value' => set_value("distance[{$i}]", isset($goals[$i]->distance) ? $goals[$i]->distance : ''),
    );

    $rating[$i] = array(
        'name'  => "rating[{$i}]",
        'id'    => "rating{$i}",
        'value' => set_value("rating[{$i}]", isset($goals[$i]->rating) ? $goals[$i]->rating : ''),
    );

    $description[$i] = array(
        'name'  => "description[{$i}]",
        'id'    => "description{$i}",
        'value' => set_value("description[{$i}]", isset($goals[$i]->description) ? $goals[$i]->description : ''),
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
    while($i < $match->h) { ?>
    <tr>
        <td><?php echo form_hidden($id[$i]['name'], $id[$i]['value']); ?><?php echo form_dropdown($minute[$i]['name'], $minute[$i]['options'], $minute[$i]['value']); ?></td>
        <td><?php echo form_input($scorerId[$i]); ?></td>
        <td><?php echo form_input($assistId[$i]); ?></td>
        <td><?php echo form_dropdown($type[$i]['name'], $type[$i]['options'], $type[$i]['value']); ?></td>
        <td><?php echo form_dropdown($bodyPart[$i]['name'], $bodyPart[$i]['options'], $bodyPart[$i]['value']); ?></td>
        <td><?php echo form_dropdown($distance[$i]['name'], $distance[$i]['options'], $distance[$i]['value']); ?></td>
        <td><?php echo form_input($rating[$i]); ?></td>
        <td><?php echo form_input($description[$i]); ?></td>
    </tr>
    <?php
        echo form_error($id[$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($scorerId[$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($assistId[$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($type[$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($bodyPart[$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($distance[$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($rating[$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');
        echo form_error($description[$i]['name'], '<tr class="error"><td colspan="9">', '</td></tr>');

        $i++;
    } ?>
</table>

<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>