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
        <h3><?php echo $this->lang->line('player_player_data'); ?></h3>

        <ul class="nav nav-pills">
            <li class="<?php echo $info == 'career-statistics' ? 'active' : ''; ?>"><a href="<?php echo $baseURL; ?>"><?php echo $this->lang->line('player_career_statistics'); ?></a></li>
            <li class="dropdown<?php echo $info == 'appearances' ? ' active' : ''; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $this->lang->line('player_appearances_by_season'); ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                <?php
                    foreach ((array('all-time' => 'All Time') + $this->Season_model->fetchForDropdown())  as $menuSeason => $menuSeasonFriendly) {
                        if (isset($player->accumulatedStatistics[$menuSeason]) || $menuSeason == 'all-time') { ?>
                        <li class="<?php echo $info == 'appearances' && $menuSeason == $season ? 'active' : ''; ?>"><a href="<?php echo "{$baseURL}/info/appearances/season/{$menuSeason}"; ?>"><?php echo $menuSeasonFriendly; ?></a></li>
                        <?php
                        }
                    } ?>
                </ul>
            </li>
            <li class="dropdown<?php echo $info == 'goal-statistics' ? ' active' : ''; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $this->lang->line('player_goal_statistics_by_season'); ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                <?php
                    foreach ((array('all-time' => 'All Time') + $this->Season_model->fetchForDropdown())  as $menuSeason => $menuSeasonFriendly) {
                        if (isset($player->accumulatedStatistics[$menuSeason]) || $menuSeason == 'all-time') { ?>
                        <li class="<?php echo $info == 'goal-statistics' && $menuSeason == $season ? 'active' : ''; ?>"><a href="<?php echo "{$baseURL}/info/goal-statistics/season/{$menuSeason}"; ?>"><?php echo $menuSeasonFriendly; ?></a></li>
                        <?php
                        }
                    } ?>
                </ul>
            </li>
            <li class="dropdown<?php echo $info == 'records' ? ' active' : ''; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $this->lang->line('player_records_by_season'); ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                <?php
                    foreach ((array('all-time' => 'All Time') + $this->Season_model->fetchForDropdown())  as $menuSeason => $menuSeasonFriendly) {
                        if (isset($player->accumulatedStatistics[$menuSeason]) || $menuSeason == 'all-time') { ?>
                        <li class="<?php echo $info == 'records' && $menuSeason == $season ? 'active' : ''; ?>"><a href="<?php echo "{$baseURL}/info/records/season/{$menuSeason}"; ?>"><?php echo $menuSeasonFriendly; ?></a></li>
                        <?php
                        }
                    } ?>
                </ul>
            </li>
        </ul>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
<?php
switch ($info) {
    case 'appearances':
        $this->load->view("themes/{$theme}/player/_appearances");
        break;
    case 'goal-statistics':
        $this->load->view("themes/{$theme}/player/_goal_statistics");
        break;
    case 'records':
        $this->load->view("themes/{$theme}/player/_records");
        break;
    default:
        $this->load->view("themes/{$theme}/player/_career_statistics");
} ?>
    </div>
</div>