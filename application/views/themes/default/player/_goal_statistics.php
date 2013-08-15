<h4><?php echo $this->lang->line('player_goal_statistics'); ?> - <?php echo Utility_helper::formattedSeason($season); ?></h4>

<?php

Chart_helper::init();

$data = array(
    'id' => 'testing-chart',
    'chartType' => 'bar',
    'data' => array(
        'labels' => array("Thomas", "Eric", "James", "David"),
        'datasets' => array(
            array(
                'legend' => 'Right Foot',
                'dataset' => array(1,2,3,4),
            ),
        ),
    ),
    'options' => array(
        'scaleOverride' => 'false',
        'scaleSteps' => 4 ,
        'scaleStepWidth' => 1,
    ),
);

?>

<h5><?php echo $this->lang->line('player_goals_by_assister'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php

$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['by_assister']);
$byAssister = array(
    'id' => 'by_assister',
    'chartType' => 'bar',
    'data' => $data,
    'options' => array(
        'scaleOverride' => 'true',
        'scaleSteps' => Chart_helper::scaleSteps($data['maxValue']),
        'scaleStepWidth' => Chart_helper::scaleWidth($data['maxValue']),
    ),
);
Chart_helper::buildChart($byAssister['id'], $byAssister['chartType'], $byAssister['data'], $byAssister['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_assister'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_goals'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($player->goalStatisticsBySeason['by_assister'] as $dataRow) { ?>
                <tr>
                    <td><?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_goals_by_type'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['by_goal_type']);
$byTypeChart = array(
    'id' => 'by-goal-type',
    'chartType' => 'radar',
    'data' => $data,
    'options' => array(),
);
Chart_helper::buildChart($byTypeChart['id'], $byTypeChart['chartType'], $byTypeChart['data'], $byTypeChart['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_goal_type'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_goals'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($player->goalStatisticsBySeason['by_goal_type'] as $dataRow) { ?>
                <tr>
                    <td><?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_goals_by_body_part'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['by_body_part']);
$byBodyPart = array(
    'id' => 'by-body-part',
    'chartType' => 'doughnut',
    'data' => $data,
    'options' => array(),
);
Chart_helper::buildChart($byBodyPart['id'], $byBodyPart['chartType'], $byBodyPart['data'], $byBodyPart['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_body_part'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_goals'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($player->goalStatisticsBySeason['by_body_part'] as $dataRow) {
                    $colour = Chart_helper::fetchColour($i); ?>
                <tr>
                    <td><span class="legend-identifier" style="border-color: <?php echo $colour['strokeColor']; ?>; background-color: <?php echo $colour['fillColor']; ?>"></span> <?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                    $i++;
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_goals_by_distance'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['by_distance']);
$byDistance = array(
    'id' => 'by-distance',
    'chartType' => 'pie',
    'data' => $data,
    'options' => array(),
);
Chart_helper::buildChart($byDistance['id'], $byDistance['chartType'], $byDistance['data'], $byDistance['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_distance'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_goals'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($player->goalStatisticsBySeason['by_distance'] as $dataRow) {
                    $colour = Chart_helper::fetchColour($i); ?>
                <tr>
                    <td><span class="legend-identifier" style="border-color: <?php echo $colour['strokeColor']; ?>; background-color: <?php echo $colour['fillColor']; ?>"></span> <?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                    $i++;
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_goals_by_minute_interval'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['by_minute_interval']);
$byMinuteInterval = array(
    'id' => 'by-minute-interval',
    'chartType' => 'bar',
    'data' => $data,
    'options' => array(
        'scaleOverride' => 'true',
        'scaleSteps' => Chart_helper::scaleSteps($data['maxValue']),
        'scaleStepWidth' => Chart_helper::scaleWidth($data['maxValue']),
    ),
);
Chart_helper::buildChart($byMinuteInterval['id'], $byMinuteInterval['chartType'], $byMinuteInterval['data'], $byMinuteInterval['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_minute_interval'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_goals'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($player->goalStatisticsBySeason['by_minute_interval'] as $dataRow) { ?>
                <tr>
                    <td><?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_assists_by_scorer'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['by_scorer']);
$byScorer = array(
    'id' => 'by-scorer',
    'chartType' => 'bar',
    'data' => $data,
    'options' => array(
        'scaleOverride' => 'true',
        'scaleSteps' => Chart_helper::scaleSteps($data['maxValue']),
        'scaleStepWidth' => Chart_helper::scaleWidth($data['maxValue']),
    ),
);
Chart_helper::buildChart($byScorer['id'], $byScorer['chartType'], $byScorer['data'], $byScorer['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_scorer'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_goals'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($player->goalStatisticsBySeason['by_scorer'] as $dataRow) { ?>
                <tr>
                    <td><?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_assists_by_type'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['assist_by_goal_type']);
$byTypeChart = array(
    'id' => 'assist-by-goal-type',
    'chartType' => 'radar',
    'data' => $data,
    'options' => array(),
);
Chart_helper::buildChart($byTypeChart['id'], $byTypeChart['chartType'], $byTypeChart['data'], $byTypeChart['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_goal_type'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_assists'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($player->goalStatisticsBySeason['assist_by_goal_type'] as $dataRow) { ?>
                <tr>
                    <td><?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_assist_by_body_part'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['assist_by_body_part']);
$byBodyPart = array(
    'id' => 'assist-by-body-part',
    'chartType' => 'doughnut',
    'data' => $data,
    'options' => array(),
);
Chart_helper::buildChart($byBodyPart['id'], $byBodyPart['chartType'], $byBodyPart['data'], $byBodyPart['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_body_part'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_assists'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($player->goalStatisticsBySeason['assist_by_body_part'] as $dataRow) {
                    $colour = Chart_helper::fetchColour($i); ?>
                <tr>
                    <td><span class="legend-identifier" style="border-color: <?php echo $colour['strokeColor']; ?>; background-color: <?php echo $colour['fillColor']; ?>"></span> <?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                    $i++;
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_assists_by_distance'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['assist_by_distance']);
$assistByDistance = array(
    'id' => 'assist-by-distance',
    'chartType' => 'pie',
    'data' => $data,
    'options' => array(),
);
Chart_helper::buildChart($assistByDistance['id'], $assistByDistance['chartType'], $assistByDistance['data'], $assistByDistance['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_distance'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_assists'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($player->goalStatisticsBySeason['assist_by_distance'] as $dataRow) {
                    $colour = Chart_helper::fetchColour($i); ?>
                <tr>
                    <td><span class="legend-identifier" style="border-color: <?php echo $colour['strokeColor']; ?>; background-color: <?php echo $colour['fillColor']; ?>"></span> <?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                    $i++;
                } ?>
            </tbody>
        </table>
    </div>
</div>

<h5><?php echo $this->lang->line('player_assists_by_minute_interval'); ?></h5>
<div class="row-fluid">
    <div class="span8">
<?php
$data = Chart_helper::buildDatasetsFromLabelValueArray($player->goalStatisticsBySeason['assist_by_minute_interval']);
$byMinuteInterval = array(
    'id' => 'assist-by-minute-interval',
    'chartType' => 'bar',
    'data' => $data,
    'options' => array(
        'scaleOverride' => 'true',
        'scaleSteps' => Chart_helper::scaleSteps($data['maxValue']),
        'scaleStepWidth' => Chart_helper::scaleWidth($data['maxValue']),
    ),
);
Chart_helper::buildChart($byMinuteInterval['id'], $byMinuteInterval['chartType'], $byMinuteInterval['data'], $byMinuteInterval['options']); ?>
    </div>
    <div class="span4">
        <table class="table width-100-percent table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-80-percent"><?php echo $this->lang->line('player_minute_interval'); ?></td>
                    <td class="width-20-percent text-align-center"><?php echo $this->lang->line('player_assists'); ?></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($player->goalStatisticsBySeason['assist_by_minute_interval'] as $dataRow) { ?>
                <tr>
                    <td><?php echo $dataRow['label']; ?></td>
                    <td class="text-align-center"><?php echo $dataRow['value']; ?></td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>