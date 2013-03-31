<?php echo sprintf($this->lang->line('league_match_confirm_delete_question'), $leagueMatch->date); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('league_match_confirm_delete_yes')); ?>
<span class=""><a href="/admin/league-match"><?php echo $this->lang->line('league_match_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>