        <div class="row-fluid">
            <div class="span5 offset1">
                <h4><?php echo $this->lang->line('fantasy_football_formation'); ?> (<?php echo $formationInfo['name']; ?>)</h4>
                <div id="stadium">
                    <div id="pitch" class="formation-<?php echo $formation; ?>">
                    <?php
                    if ($bestLineup !== false) {
                        foreach ($formationInfo['positions'] as $position) {
                            if (strpos($position, 'sub') === false) { ?>
                        <div itemscope itemtype="http://schema.org/Person" class="position <?php echo $position; ?>">
                            <span class="marker"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></span>
                            <span itemprop="name" class="player"><?php echo Player_helper::initialSurname($bestLineup[$position]->player_id, false); ?></span>
                            <span class="points"><?php echo $bestLineup[$position]->value; ?></span>
                        </div>
                    <?php
                            }
                        } ?>
                    </div>
                    <div id="dugout">
                    <?php
                        foreach ($formationInfo['positions'] as $position) {
                            if (strpos($position, 'sub') !== false) { ?>
                        <div itemscope itemtype="http://schema.org/Person" class="position <?php echo Fantasy_Football_helper::fetchSimplePosition($position); ?>">
                            <span class="marker"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></span>
                            <span itemprop="name" class="player"><?php echo Player_helper::initialSurname($bestLineup[$position]->player_id, false); ?></span>
                            <span class="points"><?php echo $bestLineup[$position]->value; ?></span>
                        </div>
                    <?php
                            }
                        }
                    } ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>

            <div class="span6">
                <h4><?php echo $this->lang->line('fantasy_football_lineup'); ?> (<?php echo $formationInfo['name']; ?>)</h4>
                <table class="width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-85-percent"><?php echo $this->lang->line('fantasy_football_player'); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_position'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($bestLineup !== false) {
                            foreach ($formationInfo['positions'] as $position) { ?>
                        <tr itemscope itemtype="http://schema.org/Person">
                            <td itemprop="name" data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($bestLineup[$position]->player_id); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_position'); ?>"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></td>
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
        </div>