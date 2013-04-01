<?php echo sprintf($this->lang->line('opposition_confirm_delete_question'), $opposition->name); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('opposition_confirm_delete_yes')); ?>
<span class=""><a href="/admin/opposition"><?php echo $this->lang->line('opposition_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>