<div class="row-fluid">
    <div class="span12">

<h2><?php echo $this->lang->line('head_to_head_title'); ?><?php
echo $opposition ? ' - ' . Opposition_helper::name($opposition) : ''; ?></h2>

        <div class="row-fluid">
            <div class="span12">

<?php
echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>

<?php
$inputOpposition = array(
    'name'    => 'opposition',
    'id'      => 'opposition',
    'options' => array('' => '--- Select ---') + $this->Opposition_model->fetchForDropdown(),
    'value'   => set_value('opposition', $opposition),
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('head_to_head_show'),
    'class'   => 'btn',
); ?>
                <fieldset>
                    <legend><?php echo $this->lang->line('global_filters');?></legend>
                        <div class="control-group">
                            <?php echo form_label($this->lang->line('head_to_head_opposition'), $inputOpposition['id'], array('class'  => 'control-label')); ?>
                            <div class="controls">
                                <?php echo form_dropdown($inputOpposition['name'], $inputOpposition['options'], $inputOpposition['value'], "id='{$inputOpposition['id']}'"); ?>
                                <?php
                                echo form_submit($submit); ?>
                            </div>
                        </div>
                </fieldset>
<?php
echo form_close(); ?>
            </div>
        </div>
<?php
if ($opposition) { ?>
        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line('head_to_head_accumulated_statistics'); ?></h3>
                <table class="no-more-tables width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-30-percent"></td>
                            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_p"); ?></td>
                            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_w"); ?></td>
                            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_d"); ?></td>
                            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_l"); ?></td>
                            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_f"); ?></td>
                            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_a"); ?></td>
                            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("head_to_head_gd"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($accumulatedData as $venue => $data) { ?>
                        <tr>
                            <td><?php echo $this->lang->line("head_to_head_venue_{$venue}"); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_played'); ?>"><?php echo $data->p; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_won'); ?>"><?php echo $data->w; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_drawn'); ?>"><?php echo $data->d; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_lost'); ?>"><?php echo $data->l; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_for'); ?>"><?php echo $data->f; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_against'); ?>"><?php echo $data->a; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('head_to_head_goal_difference'); ?>"><?php echo $data->gd; ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
            </div>

            <div class="span6">
                <h3><?php echo $this->lang->line('head_to_head_matches'); ?></h3>
                <table class="no-more-tables width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-25-percent"><?php echo $this->lang->line("match_date"); ?></td>
                            <td class="width-50-percent"><?php echo $this->lang->line("match_competition"); ?></td>
                            <td class="width-10-percent text-align-center"><?php echo $this->lang->line("match_venue"); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line("match_score"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($matches) {
                        foreach ($matches as $match) { ?>
                            <tr itemscope itemtype="http://schema.org/SportsEvent">
                                <td data-title="<?php echo $this->lang->line("match_date"); ?>"><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($match->date, "c"); ?>"><?php echo $match->date ? Utility_helper::formattedDate($match->date, "jS M 'y") : $this->lang->line('match_t_b_c'); ?></time></td>
                                <td data-title="<?php echo $this->lang->line("match_competition"); ?>"><?php echo Match_helper::shortCompetitionNameCombined($match); ?></td>
                                <td class="text-align-center" data-title="<?php echo $this->lang->line("match_venue"); ?>"><?php echo Match_helper::venue($match); ?></td>
                                <td class="text-align-center" data-title="<?php echo $this->lang->line("match_score"); ?>"><a href="<?php echo site_url("match/view/id/{$match->id}"); ?>" title=""><?php echo Match_helper::score($match); ?></a></td>
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
                <table class="width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-80-percent"><?php echo $this->lang->line("head_to_head_player"); ?></td>
                            <td class="width-20-percent text-align-center"><?php echo $this->lang->line("head_to_head_goals"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($scorers) {
                        foreach ($scorers as $scorer) { ?>
                            <tr itemscope itemtype="http://schema.org/Person">
                                <td itemprop="name" data-title="<?php echo $this->lang->line("head_to_head_player"); ?>"><?php echo Player_helper::fullNameReverse($scorer->player_id); ?></td>
                                <td class="text-align-center" data-title="<?php echo $this->lang->line("head_to_head_goals"); ?>"><?php echo $scorer->goals; ?></td>
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
                <table class="width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-80-percent"><?php echo $this->lang->line("head_to_head_player"); ?></td>
                            <td class="width-20-percent text-align-center"><?php echo $this->lang->line("head_to_head_assists"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($assisters) {
                        foreach ($assisters as $assister) { ?>
                            <tr itemscope itemtype="http://schema.org/Person">
                                <td itemprop="name" data-title="<?php echo $this->lang->line("head_to_head_player"); ?>"><?php echo Player_helper::fullNameReverse($assister->player_id); ?></td>
                                <td class="text-align-center" data-title="<?php echo $this->lang->line("head_to_head_assists"); ?>"><?php echo $assister->assists; ?></td>
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
                <table class="width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-80-percent"><?php echo $this->lang->line("head_to_head_player"); ?></td>
                            <td class="width-20-percent text-align-center"><?php echo $this->lang->line("head_to_head_points"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($pointsGainers) {
                        foreach ($pointsGainers as $pointsGainer) { ?>
                            <tr itemscope itemtype="http://schema.org/Person">
                                <td itemprop="name"><?php echo Player_helper::fullNameReverse($pointsGainer->player_id); ?></td>
                                <td class="text-align-center"><?php echo $pointsGainer->points; ?></td>
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
                <table class="width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-70-percent"><?php echo $this->lang->line("head_to_head_player"); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line("head_to_head_reds"); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line("head_to_head_yellows"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($offenders) {
                        foreach ($offenders as $offender) { ?>
                            <tr itemscope itemtype="http://schema.org/Person">
                                <td itemprop="name"><?php echo Player_helper::fullNameReverse($offender->player_id); ?></td>
                                <td class="text-align-center"><?php echo $offender->reds; ?></td>
                                <td class="text-align-center"><?php echo $offender->yellows; ?></td>
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