<?php echo sprintf($this->lang->line('calendar_event_confirm_delete_question'), $calendarEvent->name); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
<?php echo form_submit('confirm_delete', $this->lang->line('calendar_event_confirm_delete_yes')); ?>
<span class=""><a href="/admin/calendar-event"><?php echo $this->lang->line('calendar_event_confirm_delete_no'); ?></a></span>
<?php echo form_close(); ?>