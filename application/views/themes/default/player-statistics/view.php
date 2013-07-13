<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('player_statistics_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("player_statistics_matches") . ')' : ''); ?></h2>

        <div class="row-fluid">
            <div class="span12">
<?php
echo form_open($this->uri->uri_string());

$inputType = array(
    'name'    => 'type',
    'id'      => 'type',
    'options' => array('overall' => 'Overall') + Competition_model::fetchTypes(),
    'value'   => set_value('type', $type),
);

$inputThreshold = array(
    'name'    => 'threshold',
    'id'      => 'threshold',
    'options' => $this->Player_Statistics_model->fetchThresholdsForDropdown($matchCount),
    'value'   => set_value('threshold', $unit == 'percentage' ? $thresholdPercentage : $thresholdMatches),
);

$inputUnit = array(
    'name'    => 'unit',
    'id'      => 'unit',
    'options' => $this->Player_Statistics_model->fetchUnitsForDropdown(),
    'value'   => set_value('unit', $unit),
);

echo form_label($this->lang->line('player_statistics_competition_type'), $inputType['name']);
echo form_dropdown($inputType['name'], $inputType['options'], $inputType['value'], "id='{$inputType['id']}'");

echo form_label($this->lang->line('player_statistics_threshold'), $inputThreshold['name']);
echo form_dropdown($inputThreshold['name'], $inputThreshold['options'], $inputThreshold['value'], "id='{$inputThreshold['id']}'");

echo form_dropdown($inputUnit['name'], $inputUnit['options'], $inputUnit['value']); ?>

<?php
echo form_submit('submit', $this->lang->line('player_statistics_show'));
echo form_close(); ?>

<p><?php
echo $this->lang->line('player_statistics_threshold_current');
if ($unit == 'percentage') {
    echo sprintf($this->lang->line('player_statistics_threshold_percentage'), $thresholdPercentage);
} else {
    echo sprintf($this->lang->line('player_statistics_threshold_matches'), $thresholdMatches);
} ?></p>

            </div>
        </div>

<?php
$i = 0;
foreach($this->Cache_Player_Statistics_model->methodMap as $statisticGroup => $method) {
    foreach ($venues as $venue) {
        if (0 == $i % 2) { ?>
        <div class="row-fluid">
        <?php
        } ?>
            <div class="span6">
                <?php
        Player_Statistics_helper::$method($statistics, $venue, $thresholdMatches); ?>
            </div>
        <?php
        if (1 == $i % 2) { ?>
        </div>
        <?php
        }

        $i++;
    }
}

if (1 == $i % 2) { ?>
</div>
<?php
} ?>

<?php
$i = 0;
foreach($this->Cache_Player_Statistics_model->hungryMethodMap as $statisticGroup => $method) {
    if (0 == $i % 2) { ?>
        <div class="row-fluid">
    <?php
    } ?>
            <div class="span6">
                        <?php
    Player_Statistics_helper::$method($statistics, $thresholdMatches); ?>
            </div>
        <?php
    if (1 == $i % 2) { ?>
        </div>
    <?php
    }

    $i++;
}

if (1 == $i % 2) { ?>
        </div>
<?php
} ?>
    </div>
</div>