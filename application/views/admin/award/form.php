<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($award->id) ? $award->id : ''),
);

$longName = array(
    'name'        => 'long_name',
    'id'          => 'long-name',
    'value'       => set_value('long_name', isset($award->long_name) ? $award->long_name : ''),
    'maxlength'   => $this->config->item('long_name_max_length', 'award'),
    'placeholder' => $this->lang->line('award_long_name'),
    'class'       => 'input-xlarge',
);

$shortName = array(
    'name'        => 'short_name',
    'id'          => 'short-name',
    'value'       => set_value('short_name', isset($award->short_name) ? $award->short_name : ''),
    'maxlength'   => $this->config->item('short_name_max_length', 'award'),
    'placeholder' => $this->lang->line('award_short_name'),
    'class'       => 'input-large',
);

$importance = array(
    'name'        => 'importance',
    'id'          => 'importance',
    'value'       => set_value('importance', isset($award->importance) ? $award->importance : ''),
    'placeholder' => $this->lang->line('award_importance'),
    'class'       => 'input-small',
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
        <legend><?php echo $this->lang->line('award_award_details');?></legend>
        <div class="control-group<?php echo form_error($longName['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('award_long_name'), $longName['id']); ?>
            <div class="controls">
                <?php echo form_input($longName); ?>
                <?php
                if (form_error($longName['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($longName['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>

        <div class="control-group<?php echo form_error($shortName['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('award_short_name'), $shortName['id']); ?>
            <div class="controls">
                <?php echo form_input($shortName); ?>
                <?php
                if (form_error($shortName['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($shortName['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>

        <div class="control-group<?php echo form_error($importance['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('award_importance'), $importance['id']); ?>
            <div class="controls">
                <?php echo form_input($importance); ?>
                <?php
                if (form_error($importance['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($importance['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php
echo form_close(); ?>