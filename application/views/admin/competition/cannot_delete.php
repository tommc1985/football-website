<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('competition_cannot_delete'), $competition->name); ?></p>
<?php
$this->load->view('admin/footer');