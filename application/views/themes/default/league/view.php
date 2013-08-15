<div class="row-fluid">
    <div class="span12">
<?php
echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal', 'id' => 'league-form')); ?>
        <h2><?php echo $this->lang->line('league_title'); ?> - <?php echo League_helper::name($id); ?></h2><?php
$inputType = array(
    'name'       => 'type',
    'id'         => 'type',
    'options'    => array(
        'overall' => 'Home &amp; Away',
        'home'    => 'Home',
        'away'    => 'Away'
    ),
    'value'      => set_value('type', $type),
    'attributes' => 'class="input-medium"',
);

$inputDateUntil = array(
    'name'       => 'date-until',
    'id'         => 'date-until',
    'options'    => array('overall' => 'Today') + $dropdownDates,
    'value'      => set_value('date-until', $dateUntil),
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('league_refresh'),
    'class'   => 'btn',
); ?>

<fieldset>
        <legend><?php echo $this->lang->line('global_filters');?></legend>
        <div class="control-group">
            <?php echo form_label($this->lang->line('league_type'), $inputType['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo form_dropdown($inputType['name'], $inputType['options'], $inputType['value'], "id='{$inputType['id']}' {$inputType['attributes']}"); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo form_label($this->lang->line('league_include_date_upto'), $inputDateUntil['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo form_dropdown($inputDateUntil['name'], $inputDateUntil['options'], $inputDateUntil['value'], "id='{$inputDateUntil['id']}'"); ?>
                <?php
                echo form_submit($submit); ?>
            </div>
        </div>
</fieldset>

        <div class="row-fluid">
            <div class="span10 offset1 text-align-center">
                <button id="league-table-button" class="btn span4 active"><?php echo $this->lang->line('league_league_table'); ?></button>
                <button id="alternative-league-table-button" class="btn span4"><?php echo $this->lang->line('league_alternative_league_table'); ?></button>
                <button id="position-progress-button" class="btn span4"><?php echo $this->lang->line('league_position_progress'); ?></button>
            </div>
        </div>

        <div class="row-fluid league-table-wrapper">
            <div class="span10 offset1">
                <h3><?php echo $this->lang->line('league_league_table'); ?><?php echo $type != 'overall' ? " (" . $this->lang->line("league_{$type}_matches_only") . ")" : ''; ?></h3>
                <?php
                if ($dateUntil != 'overall') { ?>
                <h4><?php echo sprintf($this->lang->line('league_as_of'), $dateUntil != 'overall' ? Utility_helper::shortDate($dateUntil) : Utility_helper::shortDate(time())); ?></h4>
                <?php
                } ?>
                <?php
                if ($standings) { ?>
                <table class="no-more-tables width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-60-percent">&nbsp;</td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_p"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_w"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_d"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_l"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_f"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_a"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_gd"); ?></td>
                            <td class="width-5-percent text-align-center"><?php echo $this->lang->line("league_pts"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($standings as $standing) { ?>
                        <tr itemscope itemtype="http://schema.org/SportsTeam">
                            <td itemprop="name" data-title="<?php echo $this->lang->line('league_team'); ?>"><?php echo Opposition_helper::name($standing->opposition_id); ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_played'); ?>"><?php echo $standing->played; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_won'); ?>"><?php echo $standing->won; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_drawn'); ?>"><?php echo $standing->drawn; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_lost'); ?>"><?php echo $standing->lost; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_for'); ?>"><?php echo $standing->gf; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_against'); ?>"><?php echo $standing->ga; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_goal_difference'); ?>"><?php echo $standing->gd; ?></td>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_points'); ?>"><?php echo $standing->points; ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
                <?php
                } else { ?>
                <p><?php echo $this->lang->line("league_no_data"); ?></p>
                <?php
                } ?>
            </div>
        </div>

        <div class="row-fluid alternative-league-table-wrapper">
            <div class="span10 offset1">
                <h3><?php echo $this->lang->line('league_alternative_league_table'); ?><?php echo $type != 'overall' ? " (" . $this->lang->line("league_{$type}_matches_only") . ")" : ''; ?></h3>
                <?php
                if ($dateUntil != 'overall') { ?>
                <h4><?php echo sprintf($this->lang->line('league_as_of'), $dateUntil != 'overall' ? Utility_helper::shortDate($dateUntil) : Utility_helper::shortDate(time())); ?></h4>
                <?php
                } ?>
                <?php
                if ($standings) { ?>
                <table class="width-100-percent table table-striped table-condensed">
                    <thead>
                        <tr>
                            <td class="width-15-percent text-align-center"><?php echo $this->lang->line("league_points"); ?></td>
                            <td class="width-85-percent"><?php echo $this->lang->line("league_team"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = $alternativeTable->maxPoints;
                    while ($i >= 0) { ?>
                        <tr>
                            <td class="text-align-center" data-title="<?php echo $this->lang->line('league_points'); ?>"><?php echo $i; ?></td>
                            <td data-title="<?php echo $this->lang->line('league_team'); ?>"><?php echo isset($alternativeTable->standings[$i]) ? implode($alternativeTable->standings[$i], ", ") : ''; ?></td>
                        </tr>
                    <?php
                        $i--;
                    } ?>
                    </tbody>
                </table>
                <?php
                } else { ?>
                <p><?php echo $this->lang->line("league_no_data"); ?></p>
                <?php
                } ?>
            </div>
        </div>

        <div class="row-fluid position-progress-wrapper">
            <div class="span10 offset1">
                <h3><?php echo $this->lang->line('league_position_progress'); ?><?php echo $type != 'overall' ? " (" . $this->lang->line("league_{$type}_matches_only") . ")" : ''; ?></h3>
                <div class="row-fluid">
                    <div class="span8">
                <?php
                if ($dateUntil != 'overall') { ?>
                <h4><?php echo sprintf($this->lang->line('league_as_of'), $dateUntil != 'overall' ? Utility_helper::shortDate($dateUntil) : Utility_helper::shortDate(time())); ?></h4>
                <?php
                } ?>
                <?php Chart::init(); ?>
<?php
$positionProgressChart = array(
    'id'        => 'position-progress',
    'chartType' => 'line',
    'data'      => $positionProgress,
    'options'   => array(
        'scaleOverride'    => 'true',
        'scaleStartValue'  => $positionProgress['maxValue'],
        'scaleSteps'       => $positionProgress['maxValue'] - 1,
        'scaleStepWidth'   => -1,
        'fillColorOpacity' => 0,
    ),
);
$chart = new Chart();
$chart->buildChart($positionProgressChart['id'], $positionProgressChart['chartType'], $positionProgressChart['data'], $positionProgressChart['options']); ?>
<script type="text/javascript">
var oppositionCount = <?php echo count($standings); ?>
</script>
                    </div>
                    <div class="span4">
                        <table class="table width-100-percent table-striped table-condensed">
                            <thead>
                                <tr>
                                    <td class="width-100-percent"><?php echo $this->lang->line('player_opposition'); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($positionProgress['datasets'] as $index => $dataRow) {
                                    $colour = Chart::fetchColour($i); ?>
                                <tr>
                                    <td><span class="legend-identifier" data-index="<?php echo $i; ?>" data-enabled="true" style="border-color: <?php echo $colour['strokeColor']; ?>; background-color: <?php echo $colour['fillColor']; ?>"></span> <a href="#" class="legend-opposition" data-index="<?php echo $i; ?>"><?php echo $dataRow['legend']; ?></a></td>
                                </tr>
                                <?php
                                    $i++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo $this->lang->line("league_form"); ?></h3>
<?php
$inputFormMatchCount = array(
    'name'       => 'form-match-count',
    'id'         => 'form-match-count',
    'options'    => $this->League_Collated_Results_model->fetchMaxCountFormDropdown($id, $dateUntil, $type),
    'value'      => set_value('form-match-count', $formMatchCount),
    'attributes' => 'class="input-mini"',
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit-form-table',
    'value'   => $this->lang->line('league_refresh'),
    'class'   => 'btn',
); ?>

<fieldset>
        <div class="control-group">
            <?php echo form_label($this->lang->line('league_form_over_the_last'), $inputFormMatchCount['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <div class="input-append">
                <?php echo form_dropdown($inputFormMatchCount['name'], $inputFormMatchCount['options'], $inputFormMatchCount['value'], "id='{$inputFormMatchCount['id']}' {$inputFormMatchCount['attributes']}"); ?>
  <span class="add-on"><?php echo $this->lang->line('league_matches'); ?></span>
                </div>
                <?php
                echo form_submit($submit); ?>
            </div>
        </div>
</fieldset>

                <div id="form-table-wrapper">
                    <?php $this->load->view("themes/{$theme}/league/_form"); ?>
                </div>
            </div>
            <div class="span6">
                <h3><?php echo $this->lang->line("league_fixtures_and_results"); ?></h3>
<?php
$inputMatchDate = array(
    'name'       => 'match-date',
    'id'         => 'match-date',
    'options'    => $dropdownDates,
    'value'      => set_value('match-date', $matchDate),
    'attributes' => 'class="input-medium"',
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit-fixtures-and-results',
    'value'   => $this->lang->line('league_refresh'),
    'class'   => 'btn',
); ?>

<fieldset>
        <div class="control-group">
            <?php echo form_label($this->lang->line('league_date'), $inputMatchDate['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo form_dropdown($inputMatchDate['name'], $inputMatchDate['options'], $inputMatchDate['value'], "id='{$inputMatchDate['id']}' {$inputMatchDate['attributes']}"); ?>
                <?php
                echo form_submit($submit); ?>
            </div>
        </div>
</fieldset>
                <div id="fixtures-and-results-wrapper">
                    <?php $this->load->view("themes/{$theme}/league/_fixtures_and_results"); ?>
                </div>
            </div>
        </div>
<?php
echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">
var leagueId = '<?php echo $id; ?>';
</script>