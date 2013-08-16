<h3><?php echo $this->lang->line('head_to_head_top_point_gainers'); ?></h3>

<p><?php echo sprintf($this->lang->line('head_to_head_top_point_gainers_explanation'), Configuration::get('team_name'), Opposition_helper::name($opposition)); ?></p>

<table class="width-100-percent table table-striped table-condensed">
    <thead>
        <tr>
            <td class="width-80-percent"><?php echo $this->lang->line("head_to_head_player"); ?></td>
            <td class="width-20-percent text-align-center"><?php echo $this->lang->line("head_to_head_points"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($pointsGainers) {
        foreach ($pointsGainers as $pointsGainer) { ?>
            <tr itemscope itemtype="http://schema.org/Person">
                <td itemprop="name"><?php echo Player_helper::fullNameReverse($pointsGainer->player_id); ?></td>
                <td class="text-align-center"><?php echo $pointsGainer->points; ?></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="2"><?php echo sprintf($this->lang->line("head_to_head_no_top_point_gainers"), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>