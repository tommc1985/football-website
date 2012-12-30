Are you sure you want to delete the match scheduled for <?php echo $match->date; ?>?

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/match">No thanks</a></span>
<?php echo form_close(); ?>