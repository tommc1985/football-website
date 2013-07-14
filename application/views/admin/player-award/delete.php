<?php echo sprintf($this->lang->line('player_to_award_confirm_delete_question'), $playerAward->id); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/player-award">No thanks</a></span>
<?php echo form_close(); ?>