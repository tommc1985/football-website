<div class="row-fluid">
    <div class="span12">

        <h2><?php echo $this->lang->line('fantasy_football_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("fantasy_football_matches") . ')' : ''); ?></h2>

        <p><?php echo $this->lang->line('fantasy_football_explanation'); ?></p>

    <?php
    echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal', 'id' => 'fantasy-football-form'));
echo form_hidden('season', $season); ?>

        <div class="row-fluid">
            <div class="span10 offset1">
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
            <div class="span10 offset1">
                <h3><?php echo $this->lang->line("fantasy_football_leaderboard"); ?></h3>

                <p><?php echo $this->lang->line('fantasy_football_leaderboard_explanation'); ?></p>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span10 offset1">
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
    'id'      => 'leaderboard-submit',
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
            <div class="span10 offset1" id="leaderboard-wrapper">
                <?php $this->load->view("themes/{$theme}/fantasy-football/_leaderboard"); ?>
            </div>


        </div>

        <div class="row-fluid">
            <div class="span10 offset1">
                <h3><?php echo $this->lang->line("fantasy_football_best_lineup"); ?></h3>

                <p><?php echo $this->lang->line('fantasy_football_best_lineup_explanation'); ?></p>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span10 offset1">
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
    'id'      => 'best-lineup-submit',
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

        <div id="best-lineup-wrapper">
            <?php $this->load->view("themes/{$theme}/fantasy-football/_best_lineup"); ?>
        </div>

        <div class="row-fluid">
            <div class="span10 offset1">
                <h3><?php echo $this->lang->line('fantasy_football_scoring_system'); ?></h3>

                <p><?php echo $this->lang->line('fantasy_football_scoring_system_explanation'); ?></p>
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
                            <td data-title="<?php echo $this->lang->line('fantasy_football_criteria'); ?>"><?php echo $this->lang->line('fantasy_football_clean_sheet_by_goalkeeper'); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('fantasy_football_points'); ?>"><?php echo Configuration::get('clean_sheet_by_goalkeeper_points'); ?></td>
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
    <?php
    echo form_close(); ?>

    </div>
</div>