<?php
$this->load->model('Player_model');
$this->load->model('Nationality_model');

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

$nationalityId = array(
    'name'  => 'nationality_id',
    'id'    => 'nationality-id',
    'options' => array('' => '--- Select ---') + $this->Nationality_model->fetchForDropdown(),
    'value' => set_value('nationality_id'),
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
    'options' => array('' => '--- Select ---') + Player_model::fetchGenders(),
    'value'   => set_value('image_id'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($player->id) ? $player->id : '')); ?>
    <?php echo form_hidden($current['name'], set_value($current['name'], isset($player->current) ? $player->current : '')); ?>
    <?php echo form_hidden($image_id['name'], set_value($image_id['name'], isset($player->image_id) ? $player->image_id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('player_first_name'), $first_name['name']); ?></td>
        <td><?php echo form_input($first_name['name'], set_value($first_name['name'], isset($player->first_name) ? $player->first_name : '')); ?></td>
        <td class="error"><?php echo form_error($first_name['name']); ?><?php echo isset($errors[$first_name['name']]) ? $errors[$first_name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('player_surname'), $surname['name']); ?></td>
        <td><?php echo form_input($surname['name'], set_value($surname['name'], isset($player->surname) ? $player->surname : '')); ?></td>
        <td class="error"><?php echo form_error($surname['name']); ?><?php echo isset($errors[$surname['name']]) ? $errors[$surname['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('player_dob'), $dob['name']); ?></td>
        <td><?php echo form_date($dob['name'], set_value($dob['name'], isset($player->dob) ? $player->dob : '')); ?></td>
        <td class="error"><?php echo form_error($dob['name']); ?><?php echo isset($errors[$dob['name']]) ? $errors[$dob['name']] : ''; ?></td>
    </tr><?php
    if (Configuration::get('include_genders') === true) { ?>
    <tr>
        <td><?php echo form_label($this->lang->line('player_gender'), $gender['name']); ?></td>
        <td><?php echo form_dropdown($gender['name'], $gender['options'], set_value($gender['name'], isset($player->gender) ? $player->gender : '')); ?></td>
        <td class="error"><?php echo form_error($gender['name']); ?><?php echo isset($errors[$gender['name']]) ? $errors[$gender['name']] : ''; ?></td>
    </tr>
    <?php
    } ?><?php
    if (Configuration::get('include_nationalities') === true) { ?>
    <tr>
        <td><?php echo form_label($this->lang->line('player_nationality'), $nationalityId['name']); ?></td>
        <td><?php echo form_dropdown($nationalityId['name'], $nationalityId['options'], set_value($nationalityId['name'], isset($player->nationality_id) ? $player->nationality_id : '')); ?></td>
        <td class="error"><?php echo form_error($nationalityId['name']); ?><?php echo isset($errors[$nationalityId['name']]) ? $errors[$nationalityId['name']] : ''; ?></td>
    </tr>
    <?php
    } ?>
    <tr>
        <td><?php echo form_label($this->lang->line('player_profile'), $profile['name']); ?></td>
        <td><?php echo form_textarea($profile['name'], set_value($profile['name'], isset($player->profile) ? $player->profile : '')); ?></td>
        <td class="error"><?php echo form_error($profile['name']); ?><?php echo isset($errors[$profile['name']]) ? $errors[$profile['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>