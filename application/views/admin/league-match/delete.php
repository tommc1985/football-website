Are you sure you want to delete the League Match scheduled for <?php echo $leagueMatch->date; ?>?

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/league-match">No thanks</a></span>
<?php echo form_close(); ?>