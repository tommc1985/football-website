<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('league_cannot_delete'), $league->name); ?></p>
<?php
$this->load->view('admin/footer');