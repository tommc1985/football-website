<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('match_result_details'); ?></h2>

        <div class="row-fluid">
            <div class="span12" itemscope itemtype="http://schema.org/SportsEvent">
                <?php $this->load->view('themes/default/match/_match_details.php'); ?>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line('match_goals'); ?></h3>
                <?php
                if ($match->h > 0) {
                    if (count($match->goals) > 0) { ?>

                <table class="width-100-percent table table-striped table-condensed">
                    <tbody>
                        <?php
                        foreach ($match->goals as $goal) { ?>
                        <tr>
                            <td class="width-10-percent"><?php echo "'{$goal->minute}"; ?></td>
                            <td><?php echo Goal_helper::scorer($goal); ?></td>
                        </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
                    <?php
                    } else {
                        echo $this->lang->line('match_awaiting_goal_data');
                    }
                } else {
                    echo $this->lang->line('match_no_goals_for_this_match');
                } ?>
            </div>

            <div class="span6">
                <h3><?php echo $this->lang->line('match_lineup'); ?></h3>

                <table class="width-100-percent table table-striped table-condensed">
                    <tbody>
                        <tr>
                            <td colspan="<?php echo Configuration::get('include_appearance_shirt_numbers') === true ? 3 : 2; ?>"><h4>Starters</h4></td>
                        </tr>
                <?php
                if (count($match->appearances) > 0) {
                    foreach ($match->appearances  as $appearance) {
                        if ($appearance->status == 'starter') { ?>
                        <tr>
                            <?php
                            if (Configuration::get('include_appearance_shirt_numbers') === true) { ?>
                            <td class="width-10-percent text-align-center"><?php echo $appearance->shirt; ?></td>
                            <?php
                            } ?>
                            <td><?php echo Player_helper::fullName($appearance->player_id); ?> <?php echo !is_null($appearance->off) ? ' <i class="icon-circle-arrow-left"></i> ' . '\'' . $appearance->off : ''; ?></td>
                            <td class="width-10-percent text-align-center"><?php echo Position_helper::abbreviation($appearance->position); ?></td>
                        </tr>
                    <?php
                        }
                    } ?>
                        <tr>
                            <td colspan="<?php echo Configuration::get('include_appearance_shirt_numbers') === true ? 3 : 2; ?>"><h4>Substitutes</h4></td>
                        </tr>
                    <?php
                    foreach ($match->appearances  as $appearance) {
                        if ($appearance->status != 'starter') { ?>
                        <tr>
                          <?php
                            if (Configuration::get('include_appearance_shirt_numbers') === true) { ?>
                            <td class="width-10-percent text-align-center"><?php echo $appearance->shirt; ?></td>
                            <?php
                            } ?>
                            <td><?php echo Player_helper::fullName($appearance->player_id); ?> <?php echo !is_null($appearance->on) ? '\'' . $appearance->on . ' <i class="icon-circle-arrow-right"></i>' : ''; ?> <?php echo !is_null($appearance->off) ? ' <i class="icon-circle-arrow-left"></i> ' . '\'' . $appearance->off : ''; ?></td>
                            <td class="width-10-percent text-align-center"><?php echo Position_helper::abbreviation($appearance->position); ?></td>
                        </tr>
                    <?php
                        }
                    }
                } else {
                    echo $this->lang->line('match_awaiting_appearance_data');
                } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line('match_cards'); ?></h3>

                <?php
                if (count($match->cards) > 0) { ?>
                <table class="width-100-percent table table-striped table-condensed">
                    <tbody>
                    <?php
                    foreach ($match->cards  as $card) { ?>
                        <tr>
                            <td class="width-10-percent"><?php echo "'{$card->minute}"; ?><br />
                                <?php echo $card->type; ?>
                            </td>
                            <td><?php echo Player_helper::fullName($card->player_id); ?><br />
                                <?php echo Card_helper::offence($card); ?>
                            </td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
                <?php
                } else {
                    echo $this->lang->line('match_no_cards_for_this_match');
                } ?>
            </div>

            <div class="span6">
                <h3><?php echo $this->lang->line('match_milestones'); ?></h3>

                <?php
                if (count($match->milestones) > 0) {
                    foreach ($match->milestones  as $milestone) { ?>
                        <p><?php echo Milestone_helper::player($milestone); ?></p>
                    <?php
                    }
                } else {
                    echo $this->lang->line('match_past_no_milestones_for_this_match');
                } ?>
            </div>
        </div>
    </div>
</div>