<div class="row-fluid">
    <div class="span12">

    <?php
    echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>

        <h2><?php echo $this->lang->line('fantasy_football_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("fantasy_football_matches") . ')' : ''); ?></h2>

        <div class="row-fluid">
            <div class="span12">
<?php
$inputType = array(
    'name'    => 'type',
    'id'      => 'type',
    'options' => array('overall' => 'Overall') + Competition_model::fetchTypes(),
    'value'   => set_value('type', $type),
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('fantasy_football_show'),
    'class'   => 'btn',
); ?>
                <fieldset>
                    <legend><?php echo $this->lang->line('global_filters');?></legend>
                        <div class="control-group">
                            <?php echo form_label($this->lang->line('fantasy_football_competition_type'), $inputType['id'], array('class'  => 'control-label')); ?>
                            <div class="controls">
                                <?php echo form_dropdown($inputType['name'], $inputType['options'], $inputType['value'], "id='{$inputType['id']}'"); ?>
                                <?php
                                echo form_submit($submit); ?>
                            </div>
                        </div>
                </fieldset>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <h3><?php echo $this->lang->line("fantasy_football_leaderboard"); ?></h3>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
<?php
$inputPosition = array(
    'name'    => 'position',
    'id'      => 'position',
    'options' => array('all' => $this->lang->line('fantasy_football_all')) + $this->Position_model->fetchForDropdown(),
    'value'   => set_value('position', $position),
);

$inputOrderBy = array(
    'name'    => 'order_by',
    'id'      => 'order_by',
    'options' => $this->Fantasy_Football_model->fetchOrderByForDropdown(),
    'value'   => set_value('order_by', $orderBy),
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('fantasy_football_refresh'),
    'class'   => 'btn',
); ?>
                <fieldset>
                    <legend><?php echo $this->lang->line('global_filters');?></legend>
                        <div class="control-group">
                            <?php echo form_label($this->lang->line('fantasy_football_position'), $inputPosition['id'], array('class'  => 'control-label')); ?>
                            <div class="controls">
                                <?php echo form_dropdown($inputPosition['name'], $inputPosition['options'], $inputPosition['value'], "id='{$inputPosition['id']}'"); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo form_label($this->lang->line('fantasy_football_order_by'), $inputOrderBy['id'], array('class'  => 'control-label')); ?>
                            <div class="controls">
                                <?php echo form_dropdown($inputOrderBy['name'], $inputOrderBy['options'], $inputOrderBy['value'], "id='{$inputOrderBy['id']}'"); ?>
                                <?php
                                echo form_submit($submit); ?>
                            </div>
                        </div>
                </fieldset>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span7">
                <h4><?php echo $this->lang->line('fantasy_football_leaderboard'); ?></h4>
                <table class="no-more-tables width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-40-percent"><?php echo $this->lang->line('fantasy_football_player'); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_appearances'); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_average'); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_points'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($fantasyFootballData) {
                            foreach ($fantasyFootballData as $player) { ?>
                        <tr itemscope itemtype="http://schema.org/Person">
                            <td itemprop="name" data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($player->player_id); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_appearances'); ?>"><?php echo $player->appearances; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_average'); ?>"><?php echo $player->points_per_game; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo $player->total_points; ?></td>
                        </tr>
                        <?php
                            }
                        } else { ?>
                        <tr>
                            <td colspan="4"><?php echo $this->lang->line('fantasy_football_no_data'); ?></td>
                        </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>

            <div class="span5">
                <h4><?php echo $this->lang->line('fantasy_football_scoring_system'); ?></h4>
                <table class="width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-85-percent"><?php echo $this->lang->line('fantasy_football_criteria'); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_points'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_starting_appearance'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('starting_appearance_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_substitute_appearance'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('substitute_appearance_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_clean_sheet_by_defender'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('clean_sheet_by_defender_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_clean_sheet_by_midfielder'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('clean_sheet_by_midfielder_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_assist_by_goalkeeper'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('assist_by_goalkeeper_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_assist_by_defender'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('assist_by_defender_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_assist_by_midfielder'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('assist_by_midfielder_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_assist_by_striker'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('assist_by_striker_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_goal_by_goalkeeper'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('goal_by_goalkeeper_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_goal_by_defender'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('goal_by_defender_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_goal_by_midfielder'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('goal_by_midfielder_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_goal_by_striker'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('goal_by_striker_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_man_of_the_match'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('man_of_the_match_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_yellow_card'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('yellow_card_points'); ?></td>
                        </tr>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_red_card'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('red_card_points'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <h3><?php echo $this->lang->line("fantasy_football_best_lineup"); ?></h3>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
<?php
$inputMeasurement = array(
    'name'    => 'measurement',
    'id'      => 'measurement',
    'options' => $this->Fantasy_Football_model->fetchMeasurementForDropdown(),
    'value'   => set_value('measurement', $measurement),
);

$inputFormation = array(
    'name'    => 'formation',
    'id'      => 'formation',
    'options' => $this->Fantasy_Football_model->fetchFormationsForDropdown(),
    'value'   => set_value('formation', $formation),
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('fantasy_football_refresh'),
    'class'   => 'btn',
); ?>
                <fieldset>
                    <legend><?php echo $this->lang->line('global_filters');?></legend>
                        <div class="control-group">
                            <?php echo form_label($this->lang->line('fantasy_football_measurement'), $inputMeasurement['id'], array('class'  => 'control-label')); ?>
                            <div class="controls">
                                <?php echo form_dropdown($inputMeasurement['name'], $inputMeasurement['options'], $inputMeasurement['value'], "id='{$inputMeasurement['id']}'"); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo form_label($this->lang->line('fantasy_football_formation'), $inputFormation['id'], array('class'  => 'control-label')); ?>
                            <div class="controls">
                                <?php echo form_dropdown($inputFormation['name'], $inputFormation['options'], $inputFormation['value'], "id='{$inputFormation['id']}'"); ?>
                                <?php
                                echo form_submit($submit); ?>
                            </div>
                        </div>
                </fieldset>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span5 offset1">
                <h4><?php echo $this->lang->line('fantasy_football_formation'); ?></h4>
                <div id="stadium">
                    <div id="pitch" class="formation-<?php echo $formation; ?>">
                    <?php
                    if ($bestLineup !== false) {
                        foreach ($formationInfo['positions'] as $position) {
                            if (strpos($position, 'sub') === false) { ?>
                        <div itemscope itemtype="http://schema.org/Person" class="position <?php echo $position; ?>">
                            <span class="marker"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></span>
                            <span itemprop="name" class="player"><?php echo Player_helper::initialSurname($bestLineup[$position]->player_id, false); ?></span>
                            <span class="points"><?php echo $bestLineup[$position]->value; ?></span>
                        </div>
                    <?php
                            }
                        } ?>
                    </div>
                    <div id="dugout">
                    <?php
                        foreach ($formationInfo['positions'] as $position) {
                            if (strpos($position, 'sub') !== false) { ?>
                        <div itemscope itemtype="http://schema.org/Person" class="position <?php echo Fantasy_Football_helper::fetchSimplePosition($position); ?>">
                            <span class="marker"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></span>
                            <span itemprop="name" class="player"><?php echo Player_helper::initialSurname($bestLineup[$position]->player_id, false); ?></span>
                            <span class="points"><?php echo $bestLineup[$position]->value; ?></span>
                        </div>
                    <?php
                            }
                        }
                    } ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>

            <div class="span6">
                <h4><?php echo $this->lang->line('fantasy_football_lineup'); ?></h4>
                <table class="width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-85-percent"><?php echo $this->lang->line('fantasy_football_player'); ?></td>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line('fantasy_football_position'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($bestLineup !== false) {
                            foreach ($formationInfo['positions'] as $position) { ?>
                        <tr itemscope itemtype="http://schema.org/Person">
                            <td itemprop="name" data-title="<?php echo $this->lang->line('fantasy_football_player'); ?>"><?php echo Player_helper::fullNameReverse($bestLineup[$position]->player_id); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_position'); ?>"><?php echo Fantasy_Football_helper::fetchSimplePosition($position, true); ?></td>
                        </tr>
                        <?php
                            }
                        } else { ?>
                        <tr>
                            <td colspan="2"><?php echo $this->lang->line('fantasy_football_no_data'); ?></td>
                        </tr>
                        <?php
                        }  ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <?php
    echo form_close(); ?>
</div>