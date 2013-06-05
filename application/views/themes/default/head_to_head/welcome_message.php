<div class="row-fluid">
    <div class="span12">

<h2><?php echo $this->lang->line('head_to_head_title'); ?><?php
echo $opposition ? ' - ' . Opposition_helper::name($opposition) : ''; ?></h2>

        <div class="row-fluid">
            <div class="span12">
<?php
echo form_open($this->uri->uri_string());

$inputOpposition = array(
    'name'    => 'opposition',
    'id'      => 'opposition',
    'options' => array('' => '--- Select ---') + $this->Opposition_model->fetchForDropdown(),
    'value'   => set_value('opposition', $opposition),
);

echo form_label($this->lang->line('head_to_head_competition_type'), $inputOpposition['name']);
echo form_dropdown($inputOpposition['name'], $inputOpposition['options'], $inputOpposition['value']); ?>

<?php
echo form_submit('submit', $this->lang->line('head_to_head_show'));
echo form_close(); ?>
            </div>
        </div>
<?php
if ($opposition) { ?>
        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line('head_to_head_accumulated_statistics'); ?></h3>
                <table class="no-more-tables">
                    <thead>
                        <tr>
                            <td></td>
                            <td><?php echo $this->lang->line("head_to_head_p"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_w"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_d"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_l"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_f"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_a"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_gd"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($accumulatedData as $venue => $data) { ?>
                        <tr>
                            <td><?php echo $this->lang->line("head_to_head_venue_{$venue}"); ?></td>
                            <td data-title="<?php echo $this->lang->line('head_to_head_played'); ?>"><?php echo $data->p; ?></td>
                            <td data-title="<?php echo $this->lang->line('head_to_head_won'); ?>"><?php echo $data->w; ?></td>
                            <td data-title="<?php echo $this->lang->line('head_to_head_drawn'); ?>"><?php echo $data->d; ?></td>
                            <td data-title="<?php echo $this->lang->line('head_to_head_lost'); ?>"><?php echo $data->l; ?></td>
                            <td data-title="<?php echo $this->lang->line('head_to_head_for'); ?>"><?php echo $data->f; ?></td>
                            <td data-title="<?php echo $this->lang->line('head_to_head_against'); ?>"><?php echo $data->a; ?></td>
                            <td data-title="<?php echo $this->lang->line('head_to_head_goal_difference'); ?>"><?php echo $data->gd; ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
            </div>

            <div class="span6">
                <h3><?php echo $this->lang->line('head_to_head_matches'); ?></h3>
                <table class="no-more-tables">
                    <thead>
                        <tr>
                            <td><?php echo $this->lang->line("match_date"); ?></td>
                            <td><?php echo $this->lang->line("match_competition"); ?></td>
                            <td><?php echo $this->lang->line("match_venue"); ?></td>
                            <td><?php echo $this->lang->line("match_score"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($matches) {
                        foreach ($matches as $match) { ?>
                            <tr>
                                <td data-title="<?php echo $this->lang->line("match_date"); ?>"><?php echo $match->date ? Utility_helper::formattedDate($match->date, "jS M 'y") : $this->lang->line('match_t_b_c'); ?></td>
                                <td data-title="<?php echo $this->lang->line("match_competition"); ?>"><?php echo Match_helper::fullCompetitionNameCombined($match); ?></td>
                                <td data-title="<?php echo $this->lang->line("match_venue"); ?>"><?php echo Match_helper::venue($match); ?></td>
                                <td data-title="<?php echo $this->lang->line("match_score"); ?>"><a href="/match/view/id/<?php echo $match->id; ?>" title=""><?php echo Match_helper::score($match); ?></a></td>
                            </tr>
                        <?php
                        }
                    } else { ?>
                            <tr>
                                <td colspan="4"><?php echo sprintf($this->lang->line('head_to_head_no_statistics'), Configuration::get('team_name'), Opposition_helper::name($opposition)); ?></td>
                            </tr>
                    <?php
                    } ?>
                </table>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line('head_to_head_top_scorers'); ?></h3>
                <table class="">
                    <thead>
                        <tr>
                            <td><?php echo $this->lang->line("head_to_head_player"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_goals"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($scorers) {
                        foreach ($scorers as $scorer) { ?>
                            <tr>
                                <td data-title="<?php echo $this->lang->line("head_to_head_player"); ?>"><?php echo Player_helper::fullNameReverse($scorer->player_id); ?></td>
                                <td data-title="<?php echo $this->lang->line("head_to_head_goals"); ?>"><?php echo $scorer->goals; ?></td>
                            </tr>
                        <?php
                        }
                    } else { ?>
                            <tr>
                                <td colspan="2"><?php echo sprintf($this->lang->line("head_to_head_no_scorers"), Opposition_helper::name($opposition)); ?></td>
                            </tr>
                    <?php
                    } ?>
                </table>
            </div>

            <div class="span6">
                <h3><?php echo $this->lang->line('head_to_head_top_assisters'); ?></h3>
                <table class="">
                    <thead>
                        <tr>
                            <td><?php echo $this->lang->line("head_to_head_player"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_assists"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($assisters) {
                        foreach ($assisters as $assister) { ?>
                            <tr>
                                <td data-title="<?php echo $this->lang->line("head_to_head_player"); ?>"><?php echo Player_helper::fullNameReverse($assister->player_id); ?></td>
                                <td data-title="<?php echo $this->lang->line("head_to_head_assists"); ?>"><?php echo $assister->assists; ?></td>
                            </tr>
                        <?php
                        }
                    } else { ?>
                            <tr>
                                <td colspan="2"><?php echo sprintf($this->lang->line("head_to_head_no_assisters"), Opposition_helper::name($opposition)); ?></td>
                            </tr>
                    <?php
                    } ?>
                </table>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line('head_to_head_top_point_gainers'); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <td><?php echo $this->lang->line("head_to_head_player"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_points"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($pointsGainers) {
                        foreach ($pointsGainers as $pointsGainer) { ?>
                            <tr>
                                <td><?php echo Player_helper::fullNameReverse($pointsGainer->player_id); ?></td>
                                <td><?php echo $pointsGainer->points; ?></td>
                            </tr>
                        <?php
                        }
                    } else { ?>
                            <tr>
                                <td colspan="2"><?php echo sprintf($this->lang->line("head_to_head_no_top_point_gainers"), Opposition_helper::name($opposition)); ?></td>
                            </tr>
                    <?php
                    } ?>
                </table>
            </div>

            <div class="span6">
                <h3><?php echo $this->lang->line('head_to_head_worst_discipline'); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <td><?php echo $this->lang->line("head_to_head_player"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_reds"); ?></td>
                            <td><?php echo $this->lang->line("head_to_head_yellows"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($offenders) {
                        foreach ($offenders as $offender) { ?>
                            <tr>
                                <td><?php echo Player_helper::fullNameReverse($offender->player_id); ?></td>
                                <td><?php echo $offender->reds; ?></td>
                                <td><?php echo $offender->yellows; ?></td>
                            </tr>
                        <?php
                        }
                    } else { ?>
                            <tr>
                                <td colspan="3"><?php echo sprintf($this->lang->line("head_to_head_no_worst_discipline"), Opposition_helper::name($opposition)); ?></td>
                            </tr>
                    <?php
                    } ?>
                </table>
            </div>
        </div>
<?php
} ?>
    </div>
</div>