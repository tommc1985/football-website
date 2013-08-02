<?php
$this->load->model('Competition_model');

$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($competition->id) ? $competition->id : ''),
);

$name = array(
    'name'        => 'name',
    'id'          => 'name',
    'value'       => set_value('name', isset($competition->name) ? $competition->name : ''),
    'placeholder' => $this->lang->line('competition_name'),
    'class'       => 'input-xlarge',
);

$shortName = array(
    'name'        => 'short_name',
    'id'          => 'short-name',
    'value'       => set_value('short_name', isset($competition->short_name) ? $competition->short_name : ''),
    'placeholder' => $this->lang->line('competition_short_name'),
    'class'       => 'input-large',
);

$abbreviation = array(
    'name'        => 'abbreviation',
    'id'          => 'abbreviation',
    'value'       => set_value('abbreviation', isset($competition->abbreviation) ? $competition->abbreviation : ''),
    'placeholder' => $this->lang->line('competition_abbreviation'),
    'class'       => 'input-small',
);

$type = array(
    'name'       => 'type',
    'id'         => 'type',
    'options'    => array('' => '--- Select ---') + Competition_model::fetchTypes(),
    'value'      => set_value('type', isset($competition->type) ? $competition->type : ''),
    'attributes' => 'class="input-medium"',
);

$starts = array(
    'name'        => 'starts',
    'id'          => 'starts',
    'value'       => set_value('starts', isset($competition->starts) ? $competition->starts : ''),
    'placeholder' => $this->lang->line('competition_starts'),
    'class'       => 'input-mini',
);

$subs = array(
    'name'        => 'subs',
    'id'          => 'subs',
    'value'       => set_value('subs', isset($competition->subs) ? $competition->subs : ''),
    'placeholder' => $this->lang->line('competition_subs'),
    'class'       => 'input-mini',
);

$competitive = array(
    'name'       => 'competitive',
    'id'         => 'competitive',
    'options'    => array('' => '--- Select ---') + Competition_model::fetchCompetitive(),
    'value'      => set_value('type', isset($competition->competitive) ? $competition->competitive : ''),
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
        <legend><?php echo $this->lang->line('competition_competition_details');?></legend>
        <div class="control-group<?php echo form_error($name['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_name'), $name['id']); ?>
            <div class="controls">
                <?php echo form_input($name); ?>
                <?php
                if (form_error($name['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($name['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($shortName['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_short_name'), $shortName['id']); ?>
            <div class="controls">
                <?php echo form_input($shortName); ?>
                <?php
                if (form_error($shortName['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($shortName['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($abbreviation['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_abbreviation'), $abbreviation['id']); ?>
            <div class="controls">
                <?php echo form_input($abbreviation); ?>
                <?php
                if (form_error($abbreviation['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($abbreviation['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($type['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_type'), $type['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($type['name'], $type['options'], $type['value'], $type['attributes']); ?>
                <?php
                if (form_error($type['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($type['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($starts['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_starts'), $starts['id']); ?>
            <div class="controls">
                <?php echo form_input($starts); ?>
                <?php
                if (form_error($starts['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($starts['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($subs['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_subs'), $subs['id']); ?>
            <div class="controls">
                <?php echo form_input($subs); ?>
                <?php
                if (form_error($subs['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($subs['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($competitive['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('competition_competitive'), $competitive['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($competitive['name'], $competitive['options'], $competitive['value'], $competitive['attributes']); ?>
                <?php
                if (form_error($competitive['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($competitive['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>