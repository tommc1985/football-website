<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('player_squad_list'); ?></h2>

        <table class="no-more-tables">
            <thead>
                <tr>
                    <td><?php echo $this->lang->line('player_player'); ?></td>
                    <td><?php echo $this->lang->line('player_d_o_b'); ?></td>
                    <td><?php echo $this->lang->line('player_apps'); ?></td>
                    <td><?php echo $this->lang->line('player_goals'); ?></td>
                    <td><?php echo $this->lang->line('player_assists'); ?></td>
                    <td><?php echo $this->lang->line('player_motms'); ?></td>
                    <td><?php echo $this->lang->line('player_yellows'); ?></td>
                    <td><?php echo $this->lang->line('player_reds'); ?></td>
                    <?php
                    if (Configuration::get('include_appearance_ratings') === true) { ?>
                    <td><?php echo $this->lang->line('player_rating'); ?></td>
                    <?php
                    } ?>
                </tr>
            </thead>
        <?php
        if ($players) { ?>
            <tbody>
        <?php
            foreach ($players as $player) { ?>
                <tr itemscope itemtype="http://schema.org/Person">
                    <td itemprop="name" data-title="<?php echo $this->lang->line('player_player'); ?>"><?php echo Player_helper::fullNameReverse($player); ?></td>
                    <td data-title="<?php echo $this->lang->line('player_d_o_b'); ?>"><time itemprop="birthDate" datetime="<?php echo Utility_helper::formattedDate($player->dob, "c"); ?>"><?php echo Utility_helper::formattedDate($player->dob, "jS M Y"); ?></time></td>
                    <td data-title="<?php echo $this->lang->line('player_apps'); ?>"><?php echo $player->appearances; ?> (<?php echo $player->substitute_appearances; ?>)</td>
                    <td data-title="<?php echo $this->lang->line('player_goals'); ?>"><?php echo $player->goals; ?></td>
                    <td data-title="<?php echo $this->lang->line('player_assists'); ?>"><?php echo $player->assists; ?></td>
                    <td data-title="<?php echo $this->lang->line('player_motms'); ?>"><?php echo $player->motms; ?></td>
                    <td data-title="<?php echo $this->lang->line('player_yellows'); ?>"><?php echo $player->yellows; ?></td>
                    <td data-title="<?php echo $this->lang->line('player_reds'); ?>"><?php echo $player->reds; ?></td>
                    <?php
                    if (Configuration::get('include_appearance_ratings') === true) { ?>
                    <td data-title="<?php echo $this->lang->line('player_rating'); ?>"><?php echo Player_helper::rating($player->average_rating); ?></td>
                    <?php
                    } ?>
                </tr>
        <?php
            }
        } else { ?>
                <tr>
                    <td colspan="<?php echo Configuration::get('include_appearance_ratings') === true ? 9 : 8; ?>"><?php echo sprintf($this->lang->line('player_no_players_found'), Utility_helper::formattedSeason($season)); ?></td>
                </td>
        <?php
        } ?>
            </tbody>
        </table>
    </div>
</div>