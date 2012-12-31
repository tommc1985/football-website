<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$name = array(
    'name'    => 'name',
    'id'      => 'name',
    'value'   => set_value('name'),
);

$abbreviation = array(
    'name'    => 'abbreviation',
    'id'      => 'abbreviation',
    'value'   => set_value('abbreviation'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($competitionStage->id) ? $competitionStage->id : '')); ?>
    <tr>
        <td><?php echo form_label('Name', $name['name']); ?></td>
        <td><?php echo form_input($name['name'], set_value($name['name'], isset($competitionStage->name) ? $competitionStage->name : '')); ?></td>
        <td class="error"><?php echo form_error($name['name']); ?><?php echo isset($errors[$name['name']]) ? $errors[$name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Abbreviation', $abbreviation['name']); ?></td>
        <td><?php echo form_input($abbreviation['name'], set_value($abbreviation['name'], isset($competitionStage->abbreviation) ? $competitionStage->abbreviation : '')); ?></td>
        <td class="error"><?php echo form_error($abbreviation['name']); ?><?php echo isset($errors[$abbreviation['name']]) ? $errors[$abbreviation['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>