<h4><?php echo $this->lang->line('fantasy_football_leaderboard'); ?></h4>
<table class="no-more-tables width-100-percent table table-striped table-condensed">
    <thead>
        <tr>
            <td class="width-40-percent"><?php echo $this->lang->line('fantasy_football_player'); ?></td>
            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_appearances'); ?></td>
            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_average'); ?></td>
            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_points'); ?></td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($fantasyFootballData) {
            foreach ($fantasyFootballData as $player) { ?>
        <tr itemscope itemtype="http://schema.org/Person">
            <td itemprop="name" data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($player->player_id); ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_appearances'); ?>"><?php echo $player->appearances; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_average'); ?>"><?php echo $player->points_per_game; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo $player->total_points; ?></td>
        </tr>
        <?php
            }
        } else { ?>
        <tr>
            <td colspan="4"><?php echo $this->lang->line('fantasy_football_no_data'); ?></td>
        </tr>
        <?php
        } ?>
    </tbody>
</table>