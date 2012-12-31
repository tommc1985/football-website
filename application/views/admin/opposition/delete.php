Are you sure you want to delete <?php echo $opposition->name?>?

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/opposition">No thanks</a></span>
<?php echo form_close(); ?>