<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$first_name = array(
    'name'  => 'first_name',
    'id'    => 'first-name',
    'value' => set_value('first_name'),
    'maxlength' => $this->config->item('first_name_max_length', 'official'),
);

$surname = array(
    'name'  => 'surname',
    'id'    => 'surname',
    'value' => set_value('surname'),
    'maxlength' => $this->config->item('surname_max_length', 'official'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($official->id) ? $official->id : '')); ?>
    <tr>
        <td><?php echo form_label('First Name', 'first_name'); ?></td>
        <td><?php echo form_input('first_name', set_value('first_name', isset($official->first_name) ? $official->first_name : '')); ?></td>
        <td class="error"><?php echo form_error($first_name['name']); ?><?php echo isset($errors[$first_name['name']]) ? $errors[$first_name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Surname', 'surname'); ?></td>
        <td><?php echo form_input('surname', set_value('surname', isset($official->surname) ? $official->surname : '')); ?></td>
        <td class="error"><?php echo form_error($surname['name']); ?><?php echo isset($errors[$surname['name']]) ? $errors[$surname['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>