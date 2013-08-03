<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($official->id) ? $official->id : ''),
);

$firstName = array(
    'name'        => 'first_name',
    'id'          => 'first-name',
    'value'       => set_value('first_name', isset($official->first_name) ? $official->first_name : ''),
    'maxlength'   => $this->config->item('first_name_max_length', 'official'),
    'placeholder' => $this->lang->line('official_first_name'),
    'class'       => 'input-xlarge',
);

$surname = array(
    'name'        => 'surname',
    'id'          => 'surname',
    'value'       => set_value('surname', isset($official->surname) ? $official->surname : ''),
    'maxlength'   => $this->config->item('surname_max_length', 'official'),
    'placeholder' => $this->lang->line('official_surname'),
    'class'       => 'input-xlarge',
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
        <legend><?php echo $this->lang->line('official_official_details');?></legend>
        <div class="control-group<?php echo form_error($firstName['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('official_first_name'), $firstName['id']); ?>
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
            <?php echo form_label($this->lang->line('official_surname'), $surname['id']); ?>
            <div class="controls">
                <?php echo form_input($surname); ?>
                <?php
                if (form_error($surname['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($surname['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>