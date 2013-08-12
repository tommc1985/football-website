<div class="row-fluid">
    <div class="span12">

<h2><?php echo $this->lang->line('head_to_head_title'); ?><?php
echo $opposition ? ' - ' . Opposition_helper::name($opposition) : ''; ?></h2>

        <div class="row-fluid">
            <div class="span12">

<?php
echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>

<?php
$inputOpposition = array(
    'name'    => 'opposition',
    'id'      => 'opposition',
    'options' => array('' => '--- Select ---') + $this->Opposition_model->fetchForDropdown(),
    'value'   => set_value('opposition', $opposition),
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('head_to_head_show'),
    'class'   => 'btn',
); ?>
                <fieldset>
                    <legend><?php echo $this->lang->line('global_filters');?></legend>
                        <div class="control-group">
                            <?php echo form_label($this->lang->line('head_to_head_opposition'), $inputOpposition['id'], array('class'  => 'control-label')); ?>
                            <div class="controls">
                                <?php echo form_dropdown($inputOpposition['name'], $inputOpposition['options'], $inputOpposition['value'], "id='{$inputOpposition['id']}'"); ?>
                                <?php
                                echo form_submit($submit); ?>
                            </div>
                        </div>
                </fieldset>
<?php
echo form_close(); ?>
            </div>
        </div>
<?php
if ($opposition) { ?>
        <div class="row-fluid">
            <div class="span6">
                <?php $this->load->view("themes/{$theme}/head-to-head/_accumulated_statistics"); ?>
            </div>

            <div class="span6">
                <?php $this->load->view("themes/{$theme}/head-to-head/_matches"); ?>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <?php $this->load->view("themes/{$theme}/head-to-head/_top_scorers"); ?>
            </div>

            <div class="span6">
                <?php $this->load->view("themes/{$theme}/head-to-head/_top_assisters"); ?>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <?php $this->load->view("themes/{$theme}/head-to-head/_top_point_gainers"); ?>
            </div>

            <div class="span6">
                <?php $this->load->view("themes/{$theme}/head-to-head/_worst_discipline"); ?>
            </div>
        </div>
<?php
} ?>
    </div>
</div>