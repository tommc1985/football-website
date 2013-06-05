<h2><?php echo sprintf($this->lang->line('player_player_profile'), Player_helper::fullName($player)); ?></h2>

<h3><?php echo $this->lang->line('player_player_details'); ?></h3>

<dl>
  <dt class="span12"><?php echo $this->lang->line('player_full_name'); ?>:</dt>
  <dd class="span12"><?php echo Player_helper::fullNameReverse($player); ?></dd>
  <dt class="span12"><?php echo $this->lang->line('player_date_of_birth'); ?>:</dt>
  <dd class="span12"><?php echo Utility_helper::formattedDate($player->dob, "jS F Y"); ?></dd>
  <dt class="span12"><?php echo $this->lang->line('player_nationality'); ?>:</dt>
  <dd class="span12"><?php echo $player->nationality; ?></dd>
  <dt class="span12"><?php echo $this->lang->line('player_gender'); ?>:</dt>
  <dd class="span12"><?php echo $player->gender; ?></dd>
  <dt class="span12"><?php echo $this->lang->line('player_position_s'); ?>:</dt>
  <dd class="span12"><?php echo Player_helper::positionsAbbreviated($player->positions); ?></dd>
  <dt class="span12"><?php echo $this->lang->line('player_debut'); ?>:</dt>
  <dd class="span12"><?php echo isset($player->debut['overall']) ? Player_helper::debut($player->debut['overall']) : $this->lang->line('global_n_a'); ?></dd>
  <dt class="span12"><?php echo $this->lang->line('player_first_goal'); ?>:</dt>
  <dd class="span12"><?php echo isset($player->firstGoal['overall']) ? Player_helper::firstGoal($player->firstGoal['overall']) : $this->lang->line('global_n_a'); ?></dd>
  <dt class="span12"><?php echo $this->lang->line('player_time_between_debut_and_first_goal'); ?>:</dt>
  <dd class="span12"><?php echo isset($player->timeBetweenDebutAndFirstGoal['overall']) ? Player_helper::timeBetweenDebutAndFirstGoal($player->timeBetweenDebutAndFirstGoal['overall']) : $this->lang->line('global_n_a'); ?></dd>
  <dt class="span12"><?php echo $this->lang->line('player_games_between_debut_and_first_goal'); ?>:</dt>
  <dd class="span12"><?php echo isset($player->gamesBetweenDebutAndFirstGoal['overall']) ? Player_helper::gamesBetweenDebutAndFirstGoal($player->gamesBetweenDebutAndFirstGoal['overall']) : $this->lang->line('global_n_a'); ?></dd>
</dl>

<h3><?php echo $this->lang->line('player_profile'); ?></h3>

<div id="profile">
<?php echo $player->profile; ?>
</div>

<h3><?php echo $this->lang->line('player_season_statistics'); ?></h3>

<table class="no-more-tables">
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
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td class="ratings"><?php echo $this->lang->line('player_average_rating'); ?></td>
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
            <td data-title="&nbsp;" class="expand">&nbsp;</td>
            <td data-title="<?php echo $this->lang->line('player_season'); ?>" class="season"><?php echo Utility_helper::formattedSeason($season); ?></td>
            <td data-title="<?php echo $this->lang->line('player_appearances'); ?>" class="appearances"><?php echo $seasonStatistics['overall']->appearances; ?> (<?php echo $seasonStatistics['overall']->substitute_appearances; ?>)</td>
            <td data-title="<?php echo $this->lang->line('player_goals'); ?>" class="goals"><?php echo $seasonStatistics['overall']->goals; ?></td>
            <td data-title="<?php echo $this->lang->line('player_assists'); ?>" class="assists"><?php echo $seasonStatistics['overall']->assists; ?></td>
            <td data-title="<?php echo $this->lang->line('player_motms'); ?>" class="motms"><?php echo $seasonStatistics['overall']->motms; ?></td>
            <td data-title="<?php echo $this->lang->line('player_yellows'); ?>" class="yellows"><?php echo $seasonStatistics['overall']->yellows; ?></td>
            <td data-title="<?php echo $this->lang->line('player_reds'); ?>" class="reds"><?php echo $seasonStatistics['overall']->reds; ?></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td data-title="<?php echo $this->lang->line('player_average_rating'); ?>" class="ratings"><?php echo Player_helper::rating($seasonStatistics['overall']->average_rating); ?></td>
            <?php
            } ?>
        </tr>
        <?php
        }

        if (is_numeric($season)) {
            foreach ($seasonStatistics as $competitionType => $statistics) {
                if ($competitionType != 'overall') { ?>
        <tr class="season-breakdown-odd season-breakdown season-breakdown-<?php echo $season; ?>">
            <td class="expand">&nbsp;</td>
            <td data-title="<?php echo $this->lang->line('player_competition'); ?>" class="type"><?php echo Competition_helper::type($competitionType); ?></td>
            <td data-title="<?php echo $this->lang->line('player_appearances'); ?>" class="appearances"><?php echo $statistics->appearances; ?> (<?php echo $statistics->substitute_appearances; ?>)</td>
            <td data-title="<?php echo $this->lang->line('player_goals'); ?>" class="goals"><?php echo $statistics->goals; ?></td>
            <td data-title="<?php echo $this->lang->line('player_assists'); ?>" class="assists"><?php echo $statistics->assists; ?></td>
            <td data-title="<?php echo $this->lang->line('player_motms'); ?>" class="motms"><?php echo $statistics->motms; ?></td>
            <td data-title="<?php echo $this->lang->line('player_yellows'); ?>" class="yellows"><?php echo $statistics->yellows; ?></td>
            <td data-title="<?php echo $this->lang->line('player_reds'); ?>" class="reds"><?php echo $statistics->reds; ?></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td data-title="<?php echo $this->lang->line('player_average_rating'); ?>" class="ratings"><?php echo Player_helper::rating($statistics->average_rating); ?></td>
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
            <td class="expand">&nbsp;</td>
            <td class="season">Career</td>
            <td data-title="<?php echo $this->lang->line('player_appearances'); ?>" class="appearances"><?php echo $player->accumulatedStatistics['career']['overall']->appearances; ?> (<?php echo $player->accumulatedStatistics['career']['overall']->substitute_appearances; ?>)</td>
            <td data-title="<?php echo $this->lang->line('player_goals'); ?>" class="goals"><?php echo $player->accumulatedStatistics['career']['overall']->goals; ?></td>
            <td data-title="<?php echo $this->lang->line('player_assists'); ?>" class="assists"><?php echo $player->accumulatedStatistics['career']['overall']->assists; ?></td>
            <td data-title="<?php echo $this->lang->line('player_motms'); ?>" class="motms"><?php echo $player->accumulatedStatistics['career']['overall']->motms; ?></td>
            <td data-title="<?php echo $this->lang->line('player_yellows'); ?>" class="yellows"><?php echo $player->accumulatedStatistics['career']['overall']->yellows; ?></td>
            <td data-title="<?php echo $this->lang->line('player_reds'); ?>" class="reds"><?php echo $player->accumulatedStatistics['career']['overall']->reds; ?></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td data-title="<?php echo $this->lang->line('player_average_rating'); ?>" class="ratings"><?php echo Player_helper::rating($player->accumulatedStatistics['career']['overall']->average_rating); ?></td>
            <?php
            } ?>
        </tr>
        <?php
        foreach ($player->accumulatedStatistics['career'] as $competitionType => $statistics) {
            if ($competitionType != 'overall') { ?>
        <tr class="season-breakdown-odd season-breakdown season-breakdown-<?php echo $season; ?>">
            <td class="expand">&nbsp;</td>
            <td data-title="<?php echo $this->lang->line('player_competition'); ?>" class="type"><?php echo Competition_helper::type($competitionType); ?></td>
            <td data-title="<?php echo $this->lang->line('player_appearances'); ?>" class="appearances"><?php echo $statistics->appearances; ?> (<?php echo $statistics->substitute_appearances; ?>)</td>
            <td data-title="<?php echo $this->lang->line('player_goals'); ?>" class="goals"><?php echo $statistics->goals; ?></td>
            <td data-title="<?php echo $this->lang->line('player_assists'); ?>" class="assists"><?php echo $statistics->assists; ?></td>
            <td data-title="<?php echo $this->lang->line('player_motms'); ?>" class="motms"><?php echo $statistics->motms; ?></td>
            <td data-title="<?php echo $this->lang->line('player_yellows'); ?>" class="yellows"><?php echo $statistics->yellows; ?></td>
            <td data-title="<?php echo $this->lang->line('player_reds'); ?>" class="reds"><?php echo $statistics->reds; ?></td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td data-title="<?php echo $this->lang->line('player_average_rating'); ?>" class="ratings"><?php echo Player_helper::rating($statistics->average_rating); ?></td>
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