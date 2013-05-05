<h2><?php echo $this->lang->line('player_statistics_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("player_statistics_matches") . ')' : ''); ?></h2>

<?php
echo form_open($this->uri->uri_string());

$inputType = array(
    'name'    => 'type',
    'id'      => 'type',
    'options' => array('overall' => 'Overall') + Competition_model::fetchTypes(),
    'value'   => set_value('type', $type),
);

$inputSeason = array(
    'name'    => 'season',
    'id'      => 'season',
    'options' => array('all-time' => 'All Time') + $this->Season_model->fetchForDropdown(),
    'value'   => set_value('season', $season),
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
echo form_dropdown($inputType['name'], $inputType['options'], $inputType['value']);

echo form_label($this->lang->line('player_statistics_season'), $inputSeason['name']);
echo form_dropdown($inputSeason['name'], $inputSeason['options'], $inputSeason['value']);

echo form_label($this->lang->line('player_statistics_threshold'), $inputThreshold['name']);
echo form_dropdown($inputThreshold['name'], $inputThreshold['options'], $inputThreshold['value']);

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

<?php
foreach($this->Cache_Player_Statistics_model->methodMap as $statisticGroup => $method) {
    foreach ($venues as $venue) {
        Player_Statistics_helper::$method($statistics, $venue, $thresholdMatches);
    }
} ?>

<?php
foreach($this->Cache_Player_Statistics_model->hungryMethodMap as $statisticGroup => $method) {
    Player_Statistics_helper::$method($statistics, $thresholdMatches);
} ?>