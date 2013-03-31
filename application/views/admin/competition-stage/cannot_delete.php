<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('competition_stage_cannot_delete'), $competitionStage->name); ?></p>
<?php
$this->load->view('admin/footer');