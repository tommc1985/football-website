<?php echo sprintf($this->lang->line('player_confirm_delete_question'), $player->id); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('player_confirm_delete_yes')); ?>
<span class=""><a href="/admin/player"><?php echo $this->lang->line('player_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>