<?php echo sprintf($this->lang->line('award_confirm_delete_question'), Award_helper::longName($award->id)); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('award_confirm_delete_yes')); ?>
<span class=""><a href="/admin/award"><?php echo $this->lang->line('award_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>