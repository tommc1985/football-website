<?php echo sprintf($this->lang->line('match_confirm_delete_question'), $match->id); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('match_confirm_delete_yes')); ?>
<span class=""><a href="/admin/match"><?php echo $this->lang->line('match_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>