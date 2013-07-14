<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('award_cannot_delete'), Award_helper::longName($award->id)); ?></p>
<?php
$this->load->view('admin/footer');