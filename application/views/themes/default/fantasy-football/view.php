<div class="row-fluid">
    <div class="span12">

    <h2><?php echo $this->lang->line('fantasy_football_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("fantasy_football_matches") . ')' : ''); ?></h2>

        <div class="row-fluid">
            <div class="span8">
                <table class="no-more-tables">
                    <thead>
                        <tr>
                            <td><?php echo $this->lang->line('fantasy_football_player'); ?></td>
                            <td><?php echo $this->lang->line('fantasy_football_appearances'); ?></td>
                            <td><?php echo $this->lang->line('fantasy_football_points_per_game'); ?></td>
                            <td><?php echo $this->lang->line('fantasy_football_points'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($fantasyFootballData as $player) { ?>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($player->player_id); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_appearances'); ?>"><?php echo $player->appearances; ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points_per_game'); ?>"><?php echo $player->points_per_game; ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo $player->total_points; ?></td>
                        </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>

            <div class="span4">
                <table class="no-more-tables">
                    <thead>
                        <tr>
                            <td><?php echo $this->lang->line('fantasy_football_player'); ?></td>
                            <td><?php echo $this->lang->line('fantasy_football_position'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($bestLineup as $position => $playerId) { ?>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($playerId); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_position'); ?>"><?php echo $position; ?></td>
                        </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>