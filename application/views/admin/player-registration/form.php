<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($playerRegistration->id) ? $playerRegistration->id : ''),
);

$playerId = array(
    'name'    => 'player_id',
    'id'      => 'player_id',
    'options' => array('' => '--- Select ---') + $this->Player_model->fetchForDropdown(),
    'value'   => set_value('player_id', isset($playerRegistration->player_id) ? $playerRegistration->player_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$season = array(
    'name'    => 'season',
    'id'      => 'season',
    'options' => array('' => '--- Select ---') + $this->Season_model->fetchForDropdown(),
    'value'   => set_value('season', isset($playerRegistration->season) ? $playerRegistration->season : ''),
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
    <fieldset>
        <legend><?php echo $this->lang->line('player_registration_player_registration_details');?></legend>
        <div class="control-group<?php echo form_error($playerId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_registration_player'), $playerId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($playerId['name'], $playerId['options'], $playerId['value'], $playerId['attributes']); ?>
                <?php
                if (form_error($playerId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($playerId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($season['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_registration_season'), $season['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($season['name'], $season['options'], $season['value'], $season['attributes']); ?>
                <?php
                if (form_error($season['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($season['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>