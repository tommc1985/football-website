<h3><?php echo $this->lang->line('player_appearances'); ?> - <?php echo Utility_helper::formattedSeason($season); ?></h3>

<?php
if ($player->appearancesBySeason) { ?>
<table class="no-more-tables table table-striped table-condensed">
    <thead>
        <tr>
            <td class="width-15-perecent"><?php echo $this->lang->line('player_date'); ?></td>
            <td class="width-20-perecent"><?php echo $this->lang->line('player_opposition'); ?></td>
            <td class="width-20-perecent"><?php echo $this->lang->line('player_competition'); ?></td>
            <td class="width-5-perecent text-align-center"><?php echo $this->lang->line('player_venue'); ?></td>
            <td class="width-10-perecent text-align-center"><?php echo $this->lang->line('player_score'); ?></td>
            <td class="width-15-perecent text-align-center"><?php echo $this->lang->line('player_start_sub'); ?></td>
            <td class="width-10-perecent text-align-center"><?php echo $this->lang->line('player_position'); ?></td>
            <td class="width-5-perecent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/goal-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_goals'); ?>"></td>
            <td class="width-5-perecent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/assist-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_assists'); ?>"></td>
            <td class="width-5-perecent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/yellow-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_yellows'); ?>"><img src="<?php echo site_url('assets/themes/default/img/icons/red-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_reds'); ?>"></td>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($player->appearancesBySeason as $appearance) { ?>
        <tr itemscope itemtype="http://schema.org/SportsEvent">
            <td data-title="<?php echo $this->lang->line('player_date'); ?>"><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($appearance->date, "c"); ?>"><?php echo $appearance->date ? Utility_helper::formattedDate($appearance->date, "jS M 'y") : $this->lang->line('match_t_b_c'); ?></time></td>
            <td itemprop="name" itemscope itemtype="http://schema.org/SportsTeam" data-title="<?php echo $this->lang->line('player_opposition'); ?>"><span itemprop="name" itemprop="legalName"><?php echo Opposition_helper::name($appearance->opposition_id); ?></span></td>
            <td data-title="<?php echo $this->lang->line('player_competition'); ?>"><?php echo Match_helper::shortCompetitionNameCombined($appearance); ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('player_venue'); ?>"><?php echo Match_helper::venue($appearance); ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('player_score'); ?>"><a itemprop="url" href="<?php echo site_url('/match/view/id/' . $appearance->match_id); ?>"><?php echo "{$appearance->h} - {$appearance->a}"; ?></a></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('player_start_sub'); ?>"><?php echo Player_helper::appearanceStatus($appearance->status); ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('player_position'); ?>"><?php echo $appearance->position ? Position_helper::abbreviation($appearance->position) : '&nbsp;'; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('player_goals'); ?>"><?php echo $appearance->goals ? $appearance->goals : '&nbsp;'; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('player_assists'); ?>"><?php echo $appearance->assists ? $appearance->assists : '&nbsp;'; ?></td>
            <td class="text-align-center" data-title="<?php echo $this->lang->line('player_cards'); ?>">
                <?php
                if ($appearance->yellows > 0) { ?>
                <img src="<?php echo site_url('assets/themes/default/img/icons/yellow-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_yellows'); ?>">
                <?php
                } ?>&nbsp;
                <?php
                if ($appearance->reds > 0) { ?>
                <img src="<?php echo site_url('assets/themes/default/img/icons/red-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_reds'); ?>">
                <?php
                } ?>
            </td>
        </tr>
<?php
    } ?>
    </tbody>
</table>
<?php
} else { ?>

<?php
} ?>