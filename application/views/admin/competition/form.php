<?php
$this->load->model('Competition_model');

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

$short_name = array(
    'name'    => 'short_name',
    'id'      => 'short_name',
    'value'   => set_value('short_name'),
);

$abbreviation = array(
    'name'    => 'abbreviation',
    'id'      => 'abbreviation',
    'value'   => set_value('abbreviation'),
);

$type = array(
    'name'    => 'type',
    'id'      => 'type',
    'options' => array('' => '--- Select ---') + Competition_model::fetchTypes(),
    'value'   => set_value('type'),
);

$starts = array(
    'name'  => 'starts',
    'id'    => 'starts',
    'value' => set_value('starts'),
);

$subs = array(
    'name'  => 'subs',
    'id'    => 'subs',
    'value' => set_value('subs'),
);

$competitive = array(
    'name'  => 'competitive',
    'id'    => 'competitive',
    'options' => array('' => '--- Select ---') + Competition_model::fetchCompetitive(),
    'value' => set_value('competitive'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($competition->id) ? $competition->id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('competition_name'), $name['name']); ?></td>
        <td><?php echo form_input($name['name'], set_value($name['name'], isset($competition->name) ? $competition->name : '')); ?></td>
        <td class="error"><?php echo form_error($name['name']); ?><?php echo isset($errors[$name['name']]) ? $errors[$name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('competition_short_name'), $short_name['name']); ?></td>
        <td><?php echo form_input($short_name['name'], set_value($short_name['name'], isset($competition->short_name) ? $competition->short_name : '')); ?></td>
        <td class="error"><?php echo form_error($short_name['name']); ?><?php echo isset($errors[$short_name['name']]) ? $errors[$short_name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('competition_abbreviation'), $abbreviation['name']); ?></td>
        <td><?php echo form_input($abbreviation['name'], set_value($abbreviation['name'], isset($competition->abbreviation) ? $competition->abbreviation : '')); ?></td>
        <td class="error"><?php echo form_error($abbreviation['name']); ?><?php echo isset($errors[$abbreviation['name']]) ? $errors[$abbreviation['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('competition_type'), $type['name']); ?></td>
        <td><?php echo form_dropdown($type['name'], $type['options'], set_value($type['name'], isset($competition->type) ? $competition->type : '')); ?></td>
        <td class="error"><?php echo form_error($type['name']); ?><?php echo isset($errors[$type['name']]) ? $errors[$type['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('competition_starts'), $starts['name']); ?></td>
        <td><?php echo form_input($starts['name'], set_value($starts['name'], isset($competition->starts) ? $competition->starts : '')); ?></td>
        <td class="error"><?php echo form_error($starts['name']); ?><?php echo isset($errors[$starts['name']]) ? $errors[$starts['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('competition_subs'), $subs['name']); ?></td>
        <td><?php echo form_input($subs['name'], set_value($subs['name'], isset($competition->subs) ? $competition->subs : '')); ?></td>
        <td class="error"><?php echo form_error($subs['name']); ?><?php echo isset($errors[$subs['name']]) ? $errors[$subs['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('competition_competitive'), $competitive['name']); ?></td>
        <td><?php echo form_dropdown($competitive['name'], $competitive['options'], set_value($competitive['name'], isset($competition->competitive) ? $competition->competitive : '')); ?></td>
        <td class="error"><?php echo form_error($competitive['name']); ?><?php echo isset($errors[$competitive['name']]) ? $errors[$competitive['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>