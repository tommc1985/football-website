<h3><?php echo $this->lang->line('head_to_head_accumulated_statistics'); ?></h3>
<table class="no-more-tables width-100-percent table table-striped table-condensed">
    <thead>
        <tr>
            <td class="width-30-percent"></td>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_p"); ?></td>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_w"); ?></td>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_d"); ?></td>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_l"); ?></td>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_f"); ?></td>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_a"); ?></td>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_gd"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($accumulatedData as $venue => $data) { ?>
        <tr>
            <td><?php echo $this->lang->line("head_to_head_venue_{$venue}"); ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_played'); ?>"><?php echo $data->p; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_won'); ?>"><?php echo $data->w; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_drawn'); ?>"><?php echo $data->d; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_lost'); ?>"><?php echo $data->l; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_for'); ?>"><?php echo $data->f; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_against'); ?>"><?php echo $data->a; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_goal_difference'); ?>"><?php echo $data->gd; ?></td>
        </tr>
    <?php
    } ?>
    </tbody>
</table>