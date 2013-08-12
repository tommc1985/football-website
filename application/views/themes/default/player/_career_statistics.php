<h3><?php echo $this->lang->line('player_career_statistics'); ?></h3>

<table class="no-more-tables table table-bordered table-condensed" id="player-career-statistics">
    <thead>
        <tr>
            <td class="width-5-percent text-align-center expand">&nbsp;</td>
            <td class="width-25-percent text-align-left season">&nbsp;</td>
            <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/app-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_apps'); ?>"></td>
            <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/goal-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_goals'); ?>"></td>
            <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/assist-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_assists'); ?>"></td>
            <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/motm-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_motms'); ?>"></td>
            <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/yellow-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_yellows'); ?>"></td>
            <td class="width-10-percent text-align-center"><img src="<?php echo site_url('assets/themes/default/img/icons/red-16x16.png'); ?>" alt="<?php echo $this->lang->line('player_reds'); ?>"></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td class="width-10-percent text-align-center"><?php echo $this->lang->line('player_average_rating'); ?></td>
            <?php
            } ?>
        </tr>
    </thead>
    <tbody>
    <?php
if (count($player->accumulatedStatistics) > 0) {
    $i = 0;
    foreach ($player->accumulatedStatistics as $season => $seasonStatistics) {
        if ($season != 'career') { ?>
        <tr class="season-row-<?php echo ($i % 2) == 1 ? 'odd' : 'even'; ?> season-<?php echo $season; ?> season">
            <td class="width-5-percent text-align-center" data-title="&nbsp;"><a href="#" class="expand" id="<?php echo $season; ?>-expand" data-season="<?php echo $season; ?>" style="display: none;"><i class="icon-plus-sign-alt"></i></a><a href="#" class="collapse" id="<?php echo $season; ?>-collapse" data-season="<?php echo $season; ?>" style="display: none;"><i class="icon-minus-sign-alt"></i></a></td>
            <td class="width-25-percent text-align-left" data-title="<?php echo $this->lang->line('player_season'); ?>"><?php echo Utility_helper::formattedSeason($season); ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_appearances'); ?>"><?php echo $seasonStatistics['overall']->appearances; ?> (<?php echo $seasonStatistics['overall']->substitute_appearances; ?>)</td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_goals'); ?>"><?php echo $seasonStatistics['overall']->goals; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_assists'); ?>"><?php echo $seasonStatistics['overall']->assists; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_motms'); ?>"><?php echo $seasonStatistics['overall']->motms; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_yellows'); ?>"><?php echo $seasonStatistics['overall']->yellows; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_reds'); ?>"><?php echo $seasonStatistics['overall']->reds; ?></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_average_rating'); ?>"><?php echo Player_helper::rating($seasonStatistics['overall']->average_rating); ?></td>
            <?php
            } ?>
        </tr>
        <?php
        }

        if (is_numeric($season)) {
            $j = 0;
            foreach (Competition_model::fetchTypes() as $competitionType => $competitionTypeFriendly) {
                if (isset($seasonStatistics[$competitionType])) { ?>
        <tr class="season-row-<?php echo ($j % 2) == 1 ? 'odd' : 'even'; ?> season-breakdown season-breakdown-<?php echo $season; ?>">
            <td class="width-5-percent text-align-center expand">&nbsp;</td>
            <td class="width-25-percent text-align-left" data-title="<?php echo $this->lang->line('player_competition'); ?>"><?php echo Competition_helper::type($competitionType); ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_appearances'); ?>"><?php echo $seasonStatistics[$competitionType]->appearances; ?> (<?php echo $seasonStatistics[$competitionType]->substitute_appearances; ?>)</td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_goals'); ?>"><?php echo $seasonStatistics[$competitionType]->goals; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_assists'); ?>"><?php echo $seasonStatistics[$competitionType]->assists; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_motms'); ?>"><?php echo $seasonStatistics[$competitionType]->motms; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_yellows'); ?>"><?php echo $seasonStatistics[$competitionType]->yellows; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_reds'); ?>"><?php echo $seasonStatistics[$competitionType]->reds; ?></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_average_rating'); ?>"><?php echo Player_helper::rating($seasonStatistics[$competitionType]->average_rating); ?></td>
            <?php
            } ?>
        </tr>
    <?php
                    $j++;
                }
            }
        }

        $i++;
    }

    if (isset($player->accumulatedStatistics['career'])) { ?>
        <tr class="season-career season">
            <td class="width-5-percent text-align-center"><a href="#" class="expand" id="career-expand" data-season="career" style="display: none;"><i class="icon-plus-sign-alt"></i></a><a href="#" class="collapse" id="career-collapse" data-season="career" style="display: none;"><i class="icon-minus-sign-alt"></i></a></td>
            <td class="width-25-percent text-align-left season">Career</td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_appearances'); ?>"><?php echo $player->accumulatedStatistics['career']['overall']->appearances; ?> (<?php echo $player->accumulatedStatistics['career']['overall']->substitute_appearances; ?>)</td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_goals'); ?>"><?php echo $player->accumulatedStatistics['career']['overall']->goals; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_assists'); ?>"><?php echo $player->accumulatedStatistics['career']['overall']->assists; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_motms'); ?>"><?php echo $player->accumulatedStatistics['career']['overall']->motms; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_yellows'); ?>"><?php echo $player->accumulatedStatistics['career']['overall']->yellows; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_reds'); ?>"><?php echo $player->accumulatedStatistics['career']['overall']->reds; ?></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_average_rating'); ?>"><?php echo Player_helper::rating($player->accumulatedStatistics['career']['overall']->average_rating); ?></td>
            <?php
            } ?>
        </tr>
        <?php
        $j = 0;
        foreach (Competition_model::fetchTypes() as $competitionType => $competitionTypeFriendly) {
            if (isset($player->accumulatedStatistics['career'][$competitionType])) { ?>
        <tr class="season-row-<?php echo ($j % 2) == 1 ? 'odd' : 'even'; ?> season-breakdown season-breakdown-career">
            <td class="width-5-percent text-align-center expand">&nbsp;</td>
            <td class="width-25-percent text-align-left" data-title="<?php echo $this->lang->line('player_competition'); ?>"><?php echo Competition_helper::type($competitionType); ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_appearances'); ?>"><?php echo $player->accumulatedStatistics['career'][$competitionType]->appearances; ?> (<?php echo $player->accumulatedStatistics['career'][$competitionType]->substitute_appearances; ?>)</td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_goals'); ?>"><?php echo $player->accumulatedStatistics['career'][$competitionType]->goals; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_assists'); ?>"><?php echo $player->accumulatedStatistics['career'][$competitionType]->assists; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_motms'); ?>"><?php echo $player->accumulatedStatistics['career'][$competitionType]->motms; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_yellows'); ?>"><?php echo $player->accumulatedStatistics['career'][$competitionType]->yellows; ?></td>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_reds'); ?>"><?php echo $player->accumulatedStatistics['career'][$competitionType]->reds; ?></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_average_rating'); ?>"><?php echo Player_helper::rating($player->accumulatedStatistics['career'][$competitionType]->average_rating); ?></td>
            <?php
            } ?>
        </tr>
    <?php
                $j++;
            }
        }
    }
} else { ?>
        <tr class="">
            <td colspan="<?php echo Configuration::get('include_appearance_ratings') === true ? 9 : 8; ?>"><?php echo sprintf($this->lang->line('player_no_career_data_found'), Player_helper::fullName($player)); ?></td>
        </tr>
    <?php
} ?>

    </tbody>
</table>