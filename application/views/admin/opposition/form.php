<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$name = array(
    'name'  => 'name',
    'id'    => 'name',
    'value' => set_value('name'),
    'maxlength' => $this->config->item('opposition_max_length', 'opposition'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($opposition->id) ? $opposition->id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('opposition_name'), $name['name']); ?></td>
        <td><?php echo form_input($name['name'], set_value($name['name'], isset($opposition->name) ? $opposition->name : '')); ?></td>
        <td class="error"><?php echo form_error($name['name']); ?><?php echo isset($errors[$name['name']]) ? $errors[$name['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>