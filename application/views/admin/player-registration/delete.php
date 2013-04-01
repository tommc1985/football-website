<?php echo sprintf($this->lang->line('player_registration_confirm_delete_question'), $playerRegistration->id); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/player-registration">No thanks</a></span>
<?php echo form_close(); ?>