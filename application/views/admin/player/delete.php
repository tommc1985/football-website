Are you sure you want to delete <?php echo $player->first_name?> <?php echo $player->surname?>?

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/player">No thanks</a></span>
<?php echo form_close(); ?>