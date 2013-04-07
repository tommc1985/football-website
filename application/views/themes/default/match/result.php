<h2><?php echo $this->lang->line('match_result_details'); ?></h2>

<h3><?php echo $this->lang->line('match_details'); ?></h3>

<p><?php echo Utility_helper::longDateTime($match->date); ?><br />
<?php echo Match_helper::longScore($match); ?><br />
<?php echo Match_helper::longVenue($match) . ' ' . $this->lang->line('match_versus') . ' ' . Opposition_helper::name($match->opposition_id); ?><br />
<?php echo Match_helper::fullCompetitionNameCombined($match); ?><br />
<?php echo $match->location; ?><br />
<?php echo Official_helper::initialSurname($match->official_id); ?></p>

<h3><?php echo $this->lang->line('match_goals'); ?></h3>

<?php
if (count($match->goals) > 0) { ?>

<?php
} else {
    echo $this->lang->line('match_no_goals_scored');
} ?>

<h3><?php echo $this->lang->line('match_lineup'); ?></h3>

<h3><?php echo $this->lang->line('match_cards'); ?></h3>

<h3><?php echo $this->lang->line('match_milestones'); ?></h3>

