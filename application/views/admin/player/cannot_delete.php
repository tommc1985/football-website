<?php
$this->load->view('admin/header'); ?>
<p><?php echo sprintf($this->lang->line('player_cannot_delete'), $player->id); ?></p>
<?php
$this->load->view('admin/footer');