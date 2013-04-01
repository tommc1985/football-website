<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('opposition_cannot_delete'), $opposition->name); ?></p>
<?php
$this->load->view('admin/footer');