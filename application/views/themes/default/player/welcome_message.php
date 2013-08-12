<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('player_squad_list'); ?></h2>

        <table class="no-more-tables width-100-percent table table-striped table-condensed">
            <thead>
                <tr>
                    <td><?php echo $this->lang->line('player_player'); ?> <?php echo Player_helper::orderByLink($baseURL, 'name', $orderBy, $order); ?></td>
                    <td class="width-15-percent text-align-center"><?php echo $this->lang->line('player_d_o_b'); ?> <?php echo Player_helper::orderByLink($baseURL, 'dob', $orderBy, $order); ?></td>
                    <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/app-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_apps'); ?>"> <?php echo Player_helper::orderByLink($baseURL, 'appearances', $orderBy, $order); ?></td>
                    <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/goal-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_goals'); ?>"> <?php echo Player_helper::orderByLink($baseURL, 'goals', $orderBy, $order); ?></td>
                    <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/assist-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_assists'); ?>"> <?php echo Player_helper::orderByLink($baseURL, 'assists', $orderBy, $order); ?></td>
                    <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/motm-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_motms'); ?>"> <?php echo Player_helper::orderByLink($baseURL, 'motms', $orderBy, $order); ?></td>
                    <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/yellow-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_yellows'); ?>"> <?php echo Player_helper::orderByLink($baseURL, 'yellows', $orderBy, $order); ?></td>
                    <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/red-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_reds'); ?>"> <?php echo Player_helper::orderByLink($baseURL, 'reds', $orderBy, $order); ?></td>
                    <?php
                    if (Configuration::get('include_appearance_ratings') === true) { ?>
                    <td class="width-10-percent text-align-center"> <img src="<?php echo site_url('assets/themes/default/img/icons/sort-none.png'); ?>" alt="<?php echo $this->lang->line('sort_none'); ?>"><?php echo $this->lang->line('player_rating'); ?> <?php echo Player_helper::orderByLink($baseURL, 'ratings', $orderBy, $order); ?></td>
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
                    <td data-title="<?php echo $this->lang->line('player_d_o_b'); ?>" class="text-align-center"><time itemprop="birthDate" datetime="<?php echo Utility_helper::formattedDate($player->dob, "c"); ?>"><?php echo Utility_helper::formattedDate($player->dob, "jS M Y"); ?></time></td>
                    <td data-title="<?php echo $this->lang->line('player_apps'); ?>" class="text-align-center"><?php echo $player->appearances; ?> (<?php echo $player->substitute_appearances; ?>)</td>
                    <td data-title="<?php echo $this->lang->line('player_goals'); ?>" class="text-align-center"><?php echo $player->goals; ?></td>
                    <td data-title="<?php echo $this->lang->line('player_assists'); ?>" class="text-align-center"><?php echo $player->assists; ?></td>
                    <td data-title="<?php echo $this->lang->line('player_motms'); ?>" class="text-align-center"><?php echo $player->motms; ?></td>
                    <td data-title="<?php echo $this->lang->line('player_yellows'); ?>" class="text-align-center"><?php echo $player->yellows; ?></td>
                    <td data-title="<?php echo $this->lang->line('player_reds'); ?>" class="text-align-center"><?php echo $player->reds; ?></td>
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