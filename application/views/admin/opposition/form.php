<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($opposition->id) ? $opposition->id : ''),
);

$name = array(
    'name'        => 'name',
    'id'          => 'name',
    'value'       => set_value('name', isset($opposition->name) ? $opposition->name : ''),
    'maxlength'   => $this->config->item('opposition_max_length', 'opposition'),
    'placeholder' => $this->lang->line('opposition_name'),
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
        <legend><?php echo $this->lang->line('opposition_opposition_details');?></legend>
        <div class="control-group<?php echo form_error($name['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('opposition_name'), $name['id']); ?>
            <div class="controls">
                <?php echo form_input($name); ?>
                <?php
                if (form_error($name['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($name['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>