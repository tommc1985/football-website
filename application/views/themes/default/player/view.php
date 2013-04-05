<h2><?php echo sprintf($this->lang->line('player_player_profile'), Player_helper::fullName($player)); ?></h2>

<h3><?php echo $this->lang->line('player_player_details'); ?></h3>

<dl>
  <dt><?php echo $this->lang->line('player_full_name'); ?>:</dt>
  <dd><?php echo Player_helper::fullNameReverse($player); ?></dd>
  <dt><?php echo $this->lang->line('player_date_of_birth'); ?>:</dt>
  <dd><?php echo Utility_helper::formattedDate($player->dob, "jS F Y"); ?></dd>
  <dt><?php echo $this->lang->line('player_nationality'); ?>:</dt>
  <dd><?php echo $player->nationality; ?></dd>
  <dt><?php echo $this->lang->line('player_gender'); ?>:</dt>
  <dd><?php echo $player->gender; ?></dd>
  <dt><?php echo $this->lang->line('player_position_s'); ?>:</dt>
  <dd>?</dd>
  <dt><?php echo $this->lang->line('player_debut'); ?>:</dt>
  <dd><?php echo isset($player->debut['overall']) ? Player_helper::debut($player->debut['overall']) : $this->lang->line('global_n_a'); ?></dd>
  <dt><?php echo $this->lang->line('player_first_goal'); ?>:</dt>
  <dd><?php echo isset($player->firstGoal['overall']) ? Player_helper::debut($player->firstGoal['overall']) : $this->lang->line('global_n_a'); ?></dd>
  <dt><?php echo $this->lang->line('player_time_between_debut_and_first_goal'); ?>:</dt>
  <dd><?php echo isset($player->timeBetweenDebutAndFirstGoal['overall']) ? Utility_helper::daysElapsed($player->timeBetweenDebutAndFirstGoal['overall']->days_elapsed) : $this->lang->line('global_n_a'); ?></dd>
  <dt><?php echo $this->lang->line('player_games_between_debut_and_first_goal'); ?>:</dt>
  <dd><?php echo isset($player->gamesBetweenDebutAndFirstGoal['overall']) ? Utility_helper::gamesElapsed($player->gamesBetweenDebutAndFirstGoal['overall']->games_elapsed) : $this->lang->line('global_n_a'); ?></dd>
</dl>

<h3><?php echo $this->lang->line('player_season_statistics'); ?></h3>

<div id="profile">
<?php echo $player->profile; ?>
</div>

<h3><?php echo $this->lang->line('player_player_details'); ?></h3>

<div id="career-stats">
    <table id="career-data" cellpadding="2" cellspacing="2">
            <thead>
                <tr>
                    <td class="expand">&nbsp;</td>
                    <td class="season">&nbsp;</td>
                    <td class="appearances"><?php echo $this->lang->line('player_appearances'); ?></td>
                    <td class="goals"><?php echo $this->lang->line('player_goals'); ?></td>
                    <td class="assists"><?php echo $this->lang->line('player_assists'); ?></td>
                    <td class="motms"><?php echo $this->lang->line('player_motms'); ?></td>
                    <td class="yellows"><?php echo $this->lang->line('player_yellows'); ?></td>
                    <td class="reds"><?php echo $this->lang->line('player_reds'); ?></td>
                    <td class="ratings"><?php echo $this->lang->line('player_average_rating'); ?></td>
                </tr>
            </thead>
            <tbody>
            <?php
            if (count($player->accumulatedStatistics) > 0) {
                foreach ($player->accumulatedStatistics as $season => $seasonStatistics) {
                    if ($season != 'career') { ?>
                <tr class="season-<?php echo $season; ?>">
                    <td class="expand"><a href="" id="<?php echo $season; ?>-expand" class="expand" style="display: inline;"><img src="/assets/frontend/images/icons/expand.png" alt="expand"></a><a href="" id="<?php echo $season; ?>-collapse" class="collapse" style="display: none;"><img src="/assets/frontend/images/icons/collapse.png" alt="collapse"></a></td>
                    <td class="season"><?php echo Utility_helper::formattedSeason($season); ?></td>
                    <td class="appearances"><?php echo $seasonStatistics['overall']->appearances; ?> (<?php echo $seasonStatistics['overall']->substitute_appearances; ?>)</td>
                    <td class="goals"><?php echo $seasonStatistics['overall']->goals; ?></td>
                    <td class="assists"><?php echo $seasonStatistics['overall']->assists; ?></td>
                    <td class="motms"><?php echo $seasonStatistics['overall']->motms; ?></td>
                    <td class="yellows"><?php echo $seasonStatistics['overall']->yellows; ?></td>
                    <td class="reds"><?php echo $seasonStatistics['overall']->reds; ?></td>
                    <td class="ratings"><?php echo Player_helper::rating($seasonStatistics['overall']->average_rating); ?></td>
                </tr>
                    <?php
                    }

                    if (is_numeric($season)) {
                        foreach ($seasonStatistics as $competitionType => $statistics) {
                            if ($competitionType != 'overall') { ?>
                <tr class="season-breakdown-odd season-breakdown season-breakdown-<?php echo $season; ?>">
                    <td class="expand">&nbsp;</td>
                    <td class="type"><?php echo Competition_helper::type($competitionType); ?></td>
                    <td class="appearances"><?php echo $statistics->appearances; ?> (<?php echo $statistics->substitute_appearances; ?>)</td>
                    <td class="goals"><?php echo $statistics->goals; ?></td>
                    <td class="assists"><?php echo $statistics->assists; ?></td>
                    <td class="motms"><?php echo $statistics->motms; ?></td>
                    <td class="yellows"><?php echo $statistics->yellows; ?></td>
                    <td class="reds"><?php echo $statistics->reds; ?></td>
                    <td class="ratings"><?php echo Player_helper::rating($statistics->average_rating); ?></td>
                </tr>
            <?php
                            }
                        }
                    }
                }

                if (isset($player->accumulatedStatistics['career'])) { ?>
                <tr class="season-career">
                    <td class="expand"><a href="" id="career-expand" class="expand" style="display: inline;"><img src="/assets/frontend/images/icons/expand.png" alt="expand"></a><a href="" id="career-collapse" class="collapse" style="display: none;"><img src="/assets/frontend/images/icons/collapse.png" alt="collapse"></a></td>
                    <td class="season">Career</td>
                    <td class="appearances"><?php echo $player->accumulatedStatistics['career']['overall']->appearances; ?> (<?php echo $player->accumulatedStatistics['career']['overall']->substitute_appearances; ?>)</td>
                    <td class="goals"><?php echo $player->accumulatedStatistics['career']['overall']->goals; ?></td>
                    <td class="assists"><?php echo $player->accumulatedStatistics['career']['overall']->assists; ?></td>
                    <td class="motms"><?php echo $player->accumulatedStatistics['career']['overall']->motms; ?></td>
                    <td class="yellows"><?php echo $player->accumulatedStatistics['career']['overall']->yellows; ?></td>
                    <td class="reds"><?php echo $player->accumulatedStatistics['career']['overall']->reds; ?></td>
                    <td class="ratings"><?php echo Player_helper::rating($player->accumulatedStatistics['career']['overall']->average_rating); ?></td>
                </tr>
                    <?php
                    foreach ($player->accumulatedStatistics['career'] as $competitionType => $statistics) {
                            if ($competitionType != 'overall') { ?>
                <tr class="season-breakdown-odd season-breakdown season-breakdown-<?php echo $season; ?>">
                    <td class="expand">&nbsp;</td>
                    <td class="type"><?php echo Competition_helper::type($competitionType); ?></td>
                    <td class="appearances"><?php echo $statistics->appearances; ?> (<?php echo $statistics->substitute_appearances; ?>)</td>
                    <td class="goals"><?php echo $statistics->goals; ?></td>
                    <td class="assists"><?php echo $statistics->assists; ?></td>
                    <td class="motms"><?php echo $statistics->motms; ?></td>
                    <td class="yellows"><?php echo $statistics->yellows; ?></td>
                    <td class="reds"><?php echo $statistics->reds; ?></td>
                    <td class="ratings"><?php echo Player_helper::rating($statistics->average_rating); ?></td>
                </tr>
            <?php
                            }
                        }
                }
            } else { ?>
                <tr class="">
                    <td colspan="9"><?php echo sprintf($this->lang->line('player_no_career_data_found'), Player_helper::fullName($player)); ?></td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
</div>