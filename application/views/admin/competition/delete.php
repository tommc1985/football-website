<?php echo sprintf($this->lang->line('competition_confirm_delete_question'), $competition->name); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('competition_confirm_delete_yes')); ?>
<span class=""><a href="/admin/competition"><?php echo $this->lang->line('competition_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>