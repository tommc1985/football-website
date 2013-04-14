<h2><?php echo $this->lang->line('match_match_preview'); ?></h2>

<h3><?php echo $this->lang->line('match_details'); ?></h3>

<p><?php echo $this->lang->line('match_date'); ?> <?php echo Utility_helper::longDateTime($match->date); ?><br />
<?php echo $this->lang->line('match_venue'); ?> <?php echo Match_helper::longVenue($match) . ' ' . $this->lang->line('match_versus') . ' ' . Opposition_helper::name($match->opposition_id); ?><br />
<?php echo $this->lang->line('match_competition'); ?> <?php echo Match_helper::fullCompetitionNameCombined($match); ?><br />
<?php echo $this->lang->line('match_location'); ?> <?php echo $match->location; ?><br />
<?php echo $this->lang->line('match_official'); ?> <?php echo $match->official_id == 0 ? $this->lang->line('global_unknown') : Official_helper::initialSurname($match->official_id); ?></p>

<h3><?php echo $this->lang->line('match_possible_milestones'); ?></h3>

<?php
if (count($match->milestones) > 0) {
    foreach ($match->milestones  as $milestone) { ?>
        <p><?php echo Milestone_helper::player($milestone, false); ?></p>
    <?php
    }
} else {
    echo $this->lang->line('match_future_no_milestones_for_this_match');
} ?>

<h3><?php echo $this->lang->line('match_factfile'); ?></h3>

