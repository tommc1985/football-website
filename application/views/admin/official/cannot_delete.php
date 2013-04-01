<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('official_cannot_delete'), $official->id); ?></p>
<?php
$this->load->view('admin/footer');