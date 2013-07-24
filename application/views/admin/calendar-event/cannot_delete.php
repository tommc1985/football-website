<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('calendar_event_cannot_delete'), $calendarEvent->name); ?></p>
<?php
$this->load->view('admin/footer');