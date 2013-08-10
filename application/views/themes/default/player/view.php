<div class="row-fluid">
    <div class="span12">
        <h2><?php echo sprintf($this->lang->line('player_player_profile'), Player_helper::fullName($player, false)); ?></h2>

        <h3><?php echo $this->lang->line('player_player_details'); ?></h3>

        <dl itemscope itemtype="http://schema.org/Person" class="dl-horizontal">
          <dt><?php echo $this->lang->line('player_full_name'); ?>:</dt>
          <dd itemprop="name"><?php echo Player_helper::fullNameReverse($player, false); ?></dd>
          <dt><?php echo $this->lang->line('player_date_of_birth'); ?>:</dt>
          <dd><time itemprop="birthDate" datetime="<?php echo Utility_helper::formattedDate($player->dob, "c"); ?>"><?php echo Utility_helper::formattedDate($player->dob, "jS F Y"); ?></time></dd><?php
          if (Configuration::get('include_nationalities') === true) { ?>
          <dt><?php echo $this->lang->line('player_nationality'); ?>:</dt>
          <dd itemprop="nationality"><?php echo Nationality_helper::nationality($player->nationality_id); ?></dd>
          <?php
          } ?><?php
          if (Configuration::get('include_genders') === true) { ?>
          <dt><?php echo $this->lang->line('player_gender'); ?>:</dt>
          <dd itemprop="gender"><?php echo Player_helper::gender($player); ?></dd>
          <?php
          } ?>
          <dt><?php echo $this->lang->line('player_position_s'); ?>:</dt>
          <dd><?php echo Player_helper::positionsAbbreviated($player->positions); ?></dd>
          <dt><?php echo $this->lang->line('player_debut'); ?>:</dt>
          <dd><?php echo isset($player->debut['overall']) ? Player_helper::debut($player->debut['overall']) : $this->lang->line('global_n_a'); ?></dd>
          <dt><?php echo $this->lang->line('player_first_goal'); ?>:</dt>
          <dd><?php echo isset($player->firstGoal['overall']) ? Player_helper::firstGoal($player->firstGoal['overall']) : $this->lang->line('global_n_a'); ?></dd>
          <dt><?php echo $this->lang->line('player_time_between_debut_and_first_goal'); ?>:</dt>
          <dd><?php echo isset($player->timeBetweenDebutAndFirstGoal['overall']) ? Player_helper::timeBetweenDebutAndFirstGoal($player->timeBetweenDebutAndFirstGoal['overall']) : $this->lang->line('global_n_a'); ?></dd>
          <dt><?php echo $this->lang->line('player_games_between_debut_and_first_goal'); ?>:</dt>
          <dd><?php echo isset($player->gamesBetweenDebutAndFirstGoal['overall']) ? Player_helper::gamesBetweenDebutAndFirstGoal($player->gamesBetweenDebutAndFirstGoal['overall']) : $this->lang->line('global_n_a'); ?></dd>
          <dt><?php echo $this->lang->line('player_awards'); ?>:</dt>
          <dd><?php echo $player->awards ? Player_helper::awards($player->awards) : $this->lang->line('global_none'); ?></dd>
        </dl>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <h3><?php echo $this->lang->line('player_profile'); ?></h3>

        <div id="profile">
        <?php echo $player->profile; ?>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <h3><?php echo $this->lang->line('player_season_statistics'); ?></h3>

        <table class="no-more-tables table table-striped table-bordered table-condensed">
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
            foreach ($player->accumulatedStatistics as $season => $seasonStatistics) {
                if ($season != 'career') { ?>
                <tr class="season-<?php echo $season; ?>">
                    <td class="width-5-percent text-align-center expand" data-title="&nbsp;">&nbsp;</td>
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
                    foreach ($seasonStatistics as $competitionType => $statistics) {
                        if ($competitionType != 'overall') { ?>
                <tr class="season-breakdown-odd season-breakdown season-breakdown-<?php echo $season; ?>">
                    <td class="width-5-percent text-align-center expand">&nbsp;</td>
                    <td class="width-25-percent text-align-left" data-title="<?php echo $this->lang->line('player_competition'); ?>"><?php echo Competition_helper::type($competitionType); ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_appearances'); ?>"><?php echo $statistics->appearances; ?> (<?php echo $statistics->substitute_appearances; ?>)</td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_goals'); ?>"><?php echo $statistics->goals; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_assists'); ?>"><?php echo $statistics->assists; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_motms'); ?>"><?php echo $statistics->motms; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_yellows'); ?>"><?php echo $statistics->yellows; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_reds'); ?>"><?php echo $statistics->reds; ?></td>
                    <?php
                    if (Configuration::get('include_appearance_ratings') === true) { ?>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_average_rating'); ?>"><?php echo Player_helper::rating($statistics->average_rating); ?></td>
                    <?php
                    } ?>
                </tr>
            <?php
                        }
                    }
                }
            }

            if (isset($player->accumulatedStatistics['career'])) { ?>
                <tr class="season-career">
                    <td class="width-5-percent text-align-center expand">&nbsp;</td>
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
                foreach ($player->accumulatedStatistics['career'] as $competitionType => $statistics) {
                    if ($competitionType != 'overall') { ?>
                <tr class="season-breakdown-odd season-breakdown season-breakdown-<?php echo $season; ?>">
                    <td class="width-5-percent text-align-center expand">&nbsp;</td>
                    <td class="width-25-percent text-align-left" data-title="<?php echo $this->lang->line('player_competition'); ?>"><?php echo Competition_helper::type($competitionType); ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_appearances'); ?>"><?php echo $statistics->appearances; ?> (<?php echo $statistics->substitute_appearances; ?>)</td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_goals'); ?>"><?php echo $statistics->goals; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_assists'); ?>"><?php echo $statistics->assists; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_motms'); ?>"><?php echo $statistics->motms; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_yellows'); ?>"><?php echo $statistics->yellows; ?></td>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_reds'); ?>"><?php echo $statistics->reds; ?></td>
                    <?php
                    if (Configuration::get('include_appearance_ratings') === true) { ?>
                    <td class="width-10-percent text-align-center" data-title="<?php echo $this->lang->line('player_average_rating'); ?>"><?php echo Player_helper::rating($statistics->average_rating); ?></td>
                    <?php
                    } ?>
                </tr>
            <?php
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
    </div>
</div>