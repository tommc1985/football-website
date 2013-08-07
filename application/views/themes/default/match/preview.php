<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('match_match_preview'); ?></h2>

        <div class="row-fluid">
            <div class="span6" itemscope itemtype="http://schema.org/SportsEvent">
                <?php $this->load->view('themes/default/match/match_details.php'); ?>
            </div>
            <div class="span6">
                &nbsp;
            </div>
        </div>

        <?php
        if (Match_helper::isNextMatch($match->id)) { ?>

        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line('match_factfile'); ?></h3>

                <?php
                if (count($match->factfile) > 0) { ?>
                    <p><?php echo Factfile_helper::generateAndDisplay($match, $match->factfile); ?></p>
                <?php
                } else {
                    echo $this->lang->line('factfile_no_factfile_for_this_match');
                } ?>
            </div>
            <div class="span6">
                <h3><?php echo $this->lang->line('match_possible_milestones'); ?></h3>

                <?php
                if (count($match->milestones) > 0) {
                    foreach ($match->milestones  as $milestone) {
                        $formattedMilestone = Milestone_helper::player($milestone, false);

                        if ($formattedMilestone) { ?>
                            <p><?php echo $formattedMilestone; ?></p>
                        <?php
                        } ?>
                    <?php
                    }
                } else {
                    echo $this->lang->line('match_future_no_milestones_for_this_match');
                } ?>
            </div>
        </div>

        <?php
        } ?>

    </div>
</div>