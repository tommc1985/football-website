<h2><?php echo $this->lang->line('match_result_details'); ?></h2>

<h3><?php echo $this->lang->line('match_details'); ?></h3>

<p><?php echo $this->lang->line('match_date'); ?> <?php echo Utility_helper::longDateTime($match->date); ?><br />
<?php echo $this->lang->line('match_score'); ?> <?php echo Match_helper::longScore($match); ?><br />
<?php echo $this->lang->line('match_venue'); ?> <?php echo Match_helper::longVenue($match) . ' ' . $this->lang->line('match_versus') . ' ' . Opposition_helper::name($match->opposition_id); ?><br />
<?php echo $this->lang->line('match_competition'); ?> <?php echo Match_helper::fullCompetitionNameCombined($match); ?><br />
<?php echo $this->lang->line('match_location'); ?> <?php echo $match->location; ?><br />
<?php echo $this->lang->line('match_official'); ?> <?php echo $match->official_id == 0 ? $this->lang->line('global_unknown') : Official_helper::initialSurname($match->official_id); ?></p>

<?php
if ($match->h > 0) { ?>
<h3><?php echo $this->lang->line('match_goals'); ?></h3>

<?php
    if (count($match->goals) > 0) {
        foreach ($match->goals as $goal) { ?>
        <div class="goal">
            <p><?php echo "'{$goal->minute}"; ?><br />
                <?php echo $this->lang->line('match_scorer'); ?> <?php echo Goal_helper::scorer($goal); ?><br />
                <?php echo $this->lang->line('match_assister'); ?> <?php echo Goal_helper::assister($goal); ?><br />
                <?php echo $this->lang->line('match_type'); ?> <?php echo Goal_helper::type($goal); ?><br />
                <?php echo $this->lang->line('match_body_part'); ?> <?php echo Goal_helper::bodyPart($goal); ?><br />
                <?php echo $this->lang->line('match_distance'); ?> <?php echo Goal_helper::distance($goal); ?><br />
                <?php echo $this->lang->line('match_rating'); ?> <?php echo Goal_helper::rating($goal); ?><br />
                <?php echo $this->lang->line('match_description'); ?> <?php echo $goal->description; ?><br /></p>
        </div>
        <?php }

    } else {
        echo $this->lang->line('match_awaiting_goal_data');
    }
} ?>

<h3><?php echo $this->lang->line('match_lineup'); ?></h3>

<h3><?php echo $this->lang->line('match_cards'); ?></h3>

<h3><?php echo $this->lang->line('match_milestones'); ?></h3>
