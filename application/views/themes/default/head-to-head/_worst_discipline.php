<h3><?php echo $this->lang->line('head_to_head_worst_discipline'); ?></h3>
<table class="width-100-percent table table-striped table-condensed">
    <thead>
        <tr>
            <td class="width-70-percent"><?php echo $this->lang->line("head_to_head_player"); ?></td>
            <td class="width-15-percent text-align-center"><?php echo $this->lang->line("head_to_head_reds"); ?></td>
            <td class="width-15-percent text-align-center"><?php echo $this->lang->line("head_to_head_yellows"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($offenders) {
        foreach ($offenders as $offender) { ?>
            <tr itemscope itemtype="http://schema.org/Person">
                <td itemprop="name"><?php echo Player_helper::fullNameReverse($offender->player_id); ?></td>
                <td class="text-align-center"><?php echo $offender->reds; ?></td>
                <td class="text-align-center"><?php echo $offender->yellows; ?></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo sprintf($this->lang->line("head_to_head_no_worst_discipline"), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>