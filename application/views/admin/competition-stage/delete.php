Are you sure you want to delete <?php echo $competitionStage->name; ?>?

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', 'Yes please'); ?>
<span class=""><a href="/admin/competition-stage">No thanks</a></span>
<?php echo form_close(); ?>