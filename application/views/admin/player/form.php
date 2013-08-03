<?php
$this->load->model('Player_model');
$this->load->model('Nationality_model');

$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($player->id) ? $player->id : ''),
);

$firstName = array(
    'name'        => 'first_name',
    'id'          => 'first-name',
    'value'       => set_value('first_name', isset($player->first_name) ? $player->first_name : ''),
    'maxlength'   => $this->config->item('first_name_max_length', 'player'),
    'placeholder' => $this->lang->line('player_first_name'),
    'class'       => 'input-large',
);

$surname = array(
    'name'        => 'surname',
    'id'          => 'surname',
    'value'       => set_value('surname', isset($player->surname) ? $player->surname : ''),
    'maxlength'   => $this->config->item('surname_max_length', 'player'),
    'placeholder' => $this->lang->line('player_surname'),
    'class'       => 'input-large',
);

$dob = array(
    'name'  => 'dob',
    'id'    => 'dob',
    'value' => set_value('dob', isset($player->dob) ? $player->dob : ''),
    'class' => 'input-medium',
);

$nationalityId = array(
    'name'  => 'nationality_id',
    'id'    => 'nationality-id',
    'options' => array('' => '--- Select ---') + $this->Nationality_model->fetchForDropdown(),
    'value' => set_value('nationality_id', isset($player->nationality_id) ? $player->nationality_id : ''),
    'attributes' => 'class="input-large"',
);

$profile = array(
    'name'  => 'profile',
    'id'    => 'profile',
    'value' => set_value('profile', isset($player->profile) ? $player->profile : ''),
    'placeholder' => $this->lang->line('player_profile'),
    'class' => 'input-xlarge',
);

$current = array(
    'name'  => 'current',
    'id'    => 'current',
    'value' => set_value('current', isset($player->current) ? $player->current : ''),
);

$imageId = array(
    'name'  => 'image_id',
    'id'    => 'image-id',
    'value' => set_value('image_id', isset($player->image_id) ? $player->image_id : ''),
);

$gender = array(
    'name'    => 'gender',
    'id'      => 'gender',
    'options' => array('' => '--- Select ---') + Player_model::fetchGenders(),
    'value'   => set_value('gender', isset($player->gender) ? $player->gender : ''),
    'attributes' => 'class="input-medium"',
);

$submit = array(
    'name'  => 'submit',
    'class' => 'btn',
    'value' => $submitButtonText,
); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
    <?php echo form_hidden($id['name'], $id['value']); ?>
    <?php echo form_hidden($current['name'], $current['value']); ?>
    <?php echo form_hidden($imageId['name'], $imageId['value']); ?>
    <fieldset>
        <legend><?php echo $this->lang->line('player_player_details');?></legend>
        <div class="control-group<?php echo form_error($firstName['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_first_name'), $firstName['id']); ?>
            <div class="controls">
                <?php echo form_input($firstName); ?>
                <?php
                if (form_error($firstName['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($firstName['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($surname['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_surname'), $surname['id']); ?>
            <div class="controls">
                <?php echo form_input($surname); ?>
                <?php
                if (form_error($surname['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($surname['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($dob['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_dob'), $dob['id']); ?>
            <div class="controls">
                <?php echo form_date($dob); ?>
                <?php
                if (form_error($dob['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($dob['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php
        if (Configuration::get('include_genders') === true) { ?>
        <div class="control-group<?php echo form_error($gender['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_gender'), $gender['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($gender['name'], $gender['options'], $gender['value'], $gender['attributes']); ?>
                <?php
                if (form_error($gender['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($gender['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php
        } ?><?php
        if (Configuration::get('include_nationalities') === true) { ?>
        <div class="control-group<?php echo form_error($nationalityId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_nationality'), $nationalityId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($nationalityId['name'], $nationalityId['options'], $nationalityId['value'], $nationalityId['attributes']); ?>
                <?php
                if (form_error($nationalityId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($nationalityId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php
        } ?>
        <div class="control-group<?php echo form_error($profile['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_profile'), $profile['id']); ?>
            <div class="controls">
                <?php echo form_textarea($profile); ?>
                <?php
                if (form_error($profile['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($profile['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>