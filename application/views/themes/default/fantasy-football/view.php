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
                        if ($fantasyFootballData) {
                            foreach ($fantasyFootballData as $player) { ?>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($player->player_id); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_appearances'); ?>"><?php echo $player->appearances; ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points_per_game'); ?>"><?php echo $player->points_per_game; ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo $player->total_points; ?></td>
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
                        if ($bestLineup !== false) {
                            foreach ($formationInfo['positions'] as $position) { ?>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($bestLineup[$position]); ?></td>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_position'); ?>"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></td>
                        </tr>
                        <?php
                            }
                        } else { ?>
                        <tr>
                            <td colspan="2"><?php echo $this->lang->line('fantasy_football_no_data'); ?></td>
                        </tr>
                        <?php
                        }  ?>
                    </tbody>
                </table>
            </div>

            <div class="span4">
                <div id="stadium">
                    <div id="pitch" class="formation-<?php echo $formation; ?>">
                    <?php
                    if ($bestLineup !== false) {
                        foreach ($formationInfo['positions'] as $position) {
                            if (strpos($position, 'sub') === false) { ?>
                        <div class="position <?php echo $position; ?>">
                            <span class="marker"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></span>
                            <span class="player"><?php echo Player_helper::initialSurname($bestLineup[$position]); ?></span>
                            <span class="points">(<?php echo rand(5, 200); ?> pts)</span>
                        </div>
                    <?php
                            }
                        } ?>
                    </div>
                    <div id="dugout">
                    <?php
                        foreach ($formationInfo['positions'] as $position) {
                            if (strpos($position, 'sub') !== false) { ?>
                        <div class="position <?php echo $position; ?>">
                            <span class="marker"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></span>
                            <span class="player"><?php echo Player_helper::initialSurname($bestLineup[$position]); ?></span>
                            <span class="points">(<?php echo rand(5, 200); ?> pts)</span>
                        </div>
                    <?php
                            }
                        }
                    } ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>