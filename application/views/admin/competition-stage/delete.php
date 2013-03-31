<?php echo sprintf($this->lang->line('competition_stage_confirm_delete_question'), $competitionStage->name); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('competition_stage_confirm_delete_yes')); ?>
<span class=""><a href="/admin/competition-stage"><?php echo $this->lang->line('competition_stage_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>