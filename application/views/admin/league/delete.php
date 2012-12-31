Are you sure you want to delete <?php echo $league->name; ?>?

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/league">No thanks</a></span>
<?php echo form_close(); ?>