<?php echo sprintf($this->lang->line('league_confirm_delete_question'), $league->name); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('league_confirm_delete_yes')); ?>
<span class=""><a href="/admin/league"><?php echo $this->lang->line('league_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>