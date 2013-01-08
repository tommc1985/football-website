Are you sure you want to delete the Player Registration <?php echo $playerRegistration->id; ?>?

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/player-registration">No thanks</a></span>
<?php echo form_close(); ?>