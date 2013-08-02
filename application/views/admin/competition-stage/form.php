<?php
$this->load->model('Competition_Stage_model');

$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($competitionStage->id) ? $competitionStage->id : ''),
);

$name = array(
    'name'        => 'name',
    'id'          => 'name',
    'value'       => set_value('name', isset($competitionStage->name) ? $competitionStage->name : ''),
    'placeholder' => $this->lang->line('competition_stage_name'),
    'class'       => 'input-xlarge',
);

$abbreviation = array(
    'name'        => 'abbreviation',
    'id'          => 'abbreviation',
    'value'       => set_value('abbreviation', isset($competitionStage->abbreviation) ? $competitionStage->abbreviation : ''),
    'placeholder' => $this->lang->line('competition_stage_abbreviation'),
    'class'       => 'input-small',
);

$submit = array(
    'name'  => 'submit',
    'class' => 'btn',
    'value' => $submitButtonText,
);

echo form_open($this->uri->uri_string()); ?>
    <?php echo form_hidden($id['name'], $id['value']); ?>
    <fieldset>
        <legend><?php echo $this->lang->line('competition_stage_competition_stage_details');?></legend>
        <div class="control-group<?php echo form_error($name['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_stage_name'), $name['id']); ?>
            <div class="controls">
                <?php echo form_input($name); ?>
                <?php
                if (form_error($name['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($name['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($abbreviation['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_stage_abbreviation'), $abbreviation['id']); ?>
            <div class="controls">
                <?php echo form_input($abbreviation); ?>
                <?php
                if (form_error($abbreviation['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($abbreviation['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>