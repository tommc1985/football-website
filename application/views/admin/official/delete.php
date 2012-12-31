Are you sure you want to delete <?php echo $official->first_name?> <?php echo $official->surname?>?

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/official">No thanks</a></span>
<?php echo form_close(); ?>