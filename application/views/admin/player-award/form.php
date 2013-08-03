<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($playerAward->id) ? $playerAward->id : ''),
);

$playerId = array(
    'name'    => 'player_id',
    'id'      => 'player_id',
    'options' => array('' => '--- Select ---') + $this->Player_model->fetchForDropdown(),
    'value'   => set_value('player_id', isset($playerAward->player_id) ? $playerAward->player_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$awardId = array(
    'name'    => 'award_id',
    'id'      => 'award-id',
    'options' => array('' => '--- Select ---') + $this->Award_model->fetchForDropdown(),
    'value'   => set_value('award_id', isset($playerAward->award_id) ? $playerAward->award_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$season = array(
    'name'    => 'season',
    'id'      => 'season',
    'options' => array('' => '--- Select ---') + $this->Season_model->fetchForDropdown(),
    'value'   => set_value('season', isset($playerAward->season) ? $playerAward->season : ''),
    'attributes' => 'class="input-medium"',
);

$placing = array(
    'name'    => 'placing',
    'id'      => 'placing',
    'options' => array('' => '--- Select ---') + $this->Player_To_Award_model->fetchPlacingForDropdown(),
    'value'   => set_value('placing', isset($playerAward->placing) ? $playerAward->placing : ''),
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
        <legend><?php echo $this->lang->line('player_to_award_player_to_award_details');?></legend>
        <div class="control-group<?php echo form_error($playerId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_to_award_player'), $playerId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($playerId['name'], $playerId['options'], $playerId['value'], $playerId['attributes']); ?>
                <?php
                if (form_error($playerId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($playerId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($awardId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_to_award_award'), $awardId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($awardId['name'], $awardId['options'], $awardId['value'], $awardId['attributes']); ?>
                <?php
                if (form_error($awardId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($awardId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($season['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_to_award_season'), $season['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($season['name'], $season['options'], $season['value'], $season['attributes']); ?>
                <?php
                if (form_error($season['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($season['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($placing['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('player_to_award_placing'), $placing['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($placing['name'], $placing['options'], $placing['value'], $placing['attributes']); ?>
                <?php
                if (form_error($placing['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($placing['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>