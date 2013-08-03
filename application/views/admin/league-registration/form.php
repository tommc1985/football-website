<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($leagueRegistration->id) ? $leagueRegistration->id : ''),
);

$leagueId = array(
    'name'       => 'league_id',
    'id'         => 'league_id',
    'options'    => array('' => '--- Select ---') + $this->League_model->fetchForDropdown(),
    'value'      => set_value('league_id', isset($leagueRegistration->league_id) ? $leagueRegistration->league_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$oppositionId = array(
    'name'       => 'opposition_id',
    'id'         => 'opposition_id',
    'options'    => array('' => '--- Select ---') + $this->Opposition_model->fetchForDropdown(),
    'value'      => set_value('opposition_id', isset($leagueRegistration->opposition_id) ? $leagueRegistration->opposition_id : ''),
    'attributes' => 'class="input-xlarge"',
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
        <legend><?php echo $this->lang->line('league_registration_league_registration_details');?></legend>
        <div class="control-group<?php echo form_error($leagueId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_registration_league'), $leagueId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($leagueId['name'], $leagueId['options'], $leagueId['value'], $leagueId['attributes']); ?>
                <?php
                if (form_error($leagueId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($leagueId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($oppositionId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_registration_team'), $oppositionId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($oppositionId['name'], $oppositionId['options'], $oppositionId['value'], $oppositionId['attributes']); ?>
                <?php
                if (form_error($oppositionId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($oppositionId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>