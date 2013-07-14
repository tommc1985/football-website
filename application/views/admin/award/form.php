<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$longName = array(
    'name'  => 'long_name',
    'id'    => 'long-name',
    'value' => set_value('long_name'),
    'maxlength' => $this->config->item('long_name_max_length', 'award'),
);

$shortName = array(
    'name'  => 'short_name',
    'id'    => 'short-name',
    'value' => set_value('short_name'),
    'maxlength' => $this->config->item('short_name_max_length', 'award'),
);

$importance = array(
    'name'  => 'importance',
    'id'    => 'importance',
    'value' => set_value('importance'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($award->id) ? $award->id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('award_long_name'), 'long_name'); ?></td>
        <td><?php echo form_input('long_name', set_value('long_name', isset($award->long_name) ? $award->long_name : '')); ?></td>
        <td class="error"><?php echo form_error($longName['name']); ?><?php echo isset($errors[$longName['name']]) ? $errors[$longName['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('award_short_name'), 'short_name'); ?></td>
        <td><?php echo form_input('short_name', set_value('short_name', isset($award->short_name) ? $award->short_name : '')); ?></td>
        <td class="error"><?php echo form_error($shortName['name']); ?><?php echo isset($errors[$shortName['name']]) ? $errors[$shortName['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('award_importance'), 'importance'); ?></td>
        <td><?php echo form_input('importance', set_value('importance', isset($award->importance) ? $award->importance : '')); ?></td>
        <td class="error"><?php echo form_error($importance['name']); ?><?php echo isset($errors[$importance['name']]) ? $errors[$importance['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>