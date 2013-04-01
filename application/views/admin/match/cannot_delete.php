<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('match_cannot_delete'), $match->id); ?></p>
<?php
$this->load->view('admin/footer');