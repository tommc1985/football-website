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
    'maxlength' => $this->config->item('first_name_max_length', 'player'),
);

$surname = array(
    'name'  => 'surname',
    'id'    => 'surname',
    'value' => set_value('surname'),
    'maxlength' => $this->config->item('surname_max_length', 'player'),
);

$dob = array(
    'name'  => 'dob',
    'id'    => 'dob',
    'value' => set_value('dob'),
);

$nationality = array(
    'name'  => 'nationality',
    'id'    => 'nationality',
    'value' => set_value('nationality'),
);

$profile = array(
    'name'  => 'profile',
    'id'    => 'profile',
    'value' => set_value('profile'),
);

$current = array(
    'name'  => 'current',
    'id'    => 'current',
    'value' => set_value('current'),
);

$image_id = array(
    'name'  => 'image_id',
    'id'    => 'image_id',
    'value' => set_value('image_id'),
);

$gender = array(
    'name'    => 'gender',
    'id'      => 'gender',
    'options' => $this->config->item('gender_options', 'player'),
    'value'   => set_value('image_id'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($player->id) ? $player->id : '')); ?>
    <?php echo form_hidden('nationality', set_value('nationality', isset($player->nationality) ? $player->nationality : '')); ?>
    <?php echo form_hidden('current', set_value('current', isset($player->current) ? $player->current : '')); ?>
    <?php echo form_hidden('image_id', set_value('image_id', isset($player->image_id) ? $player->image_id : '')); ?>
    <tr>
        <td><?php echo form_label('First Name', 'first_name'); ?></td>
        <td><?php echo form_input('first_name', set_value('first_name', isset($player->first_name) ? $player->first_name : '')); ?></td>
        <td class="error"><?php echo form_error($first_name['name']); ?><?php echo isset($errors[$first_name['name']]) ? $errors[$first_name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Surname', 'surname'); ?></td>
        <td><?php echo form_input('surname', set_value('surname', isset($player->surname) ? $player->surname : '')); ?></td>
        <td class="error"><?php echo form_error($surname['name']); ?><?php echo isset($errors[$surname['name']]) ? $errors[$surname['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Date of Birth', 'dob'); ?></td>
        <td><?php echo form_date('dob', set_value('dob', isset($player->dob) ? $player->dob : '')); ?></td>
        <td class="error"><?php echo form_error($dob['name']); ?><?php echo isset($errors[$dob['name']]) ? $errors[$dob['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Gender', 'gender'); ?></td>
        <td><?php echo form_dropdown('gender', $gender['options'], set_value('gender', isset($player->gender) ? $player->gender : '')); ?></td>
        <td class="error"><?php echo form_error($gender['name']); ?><?php echo isset($errors[$gender['name']]) ? $errors[$gender['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label('Profile', 'profile'); ?></td>
        <td><?php echo form_textarea('profile', set_value('profile', isset($player->profile) ? $player->profile : '')); ?></td>
        <td class="error"><?php echo form_error($profile['name']); ?><?php echo isset($errors[$profile['name']]) ? $errors[$profile['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>