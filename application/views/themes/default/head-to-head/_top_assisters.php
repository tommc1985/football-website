<h3><?php echo $this->lang->line('head_to_head_top_assisters'); ?></h3>

<p><?php echo sprintf($this->lang->line('head_to_head_top_assisters_explanation'), Configuration::get('team_name'), Opposition_helper::name($opposition)); ?></p>

<table class="width-100-percent table table-striped table-condensed">
    <thead>
        <tr>
            <td class="width-80-percent"><?php echo $this->lang->line("head_to_head_player"); ?></td>
            <td class="width-20-percent text-align-center"><?php echo $this->lang->line("head_to_head_assists"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($assisters) {
        foreach ($assisters as $assister) { ?>
            <tr itemscope itemtype="http://schema.org/Person">
                <td itemprop="name" data-title="<?php echo $this->lang->line("head_to_head_player"); ?>"><?php echo Player_helper::fullNameReverse($assister->player_id); ?></td>
                <td class="text-align-center" data-title="<?php echo $this->lang->line("head_to_head_assists"); ?>"><?php echo $assister->assists; ?></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="2"><?php echo sprintf($this->lang->line("head_to_head_no_assisters"), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>