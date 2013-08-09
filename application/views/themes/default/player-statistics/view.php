<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('player_statistics_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("player_statistics_matches") . ')' : ''); ?></h2>

        <div class="row-fluid">
            <div class="span12">
<?php
echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal'));

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
    'value'   => set_value('threshold', $threshold),
);

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('player_statistics_show'),
    'class'   => 'btn',
); ?>

<fieldset>
        <legend><?php echo $this->lang->line('global_filters');?></legend>
        <div class="control-group">
            <?php echo form_label($this->lang->line('player_statistics_competition_type'), $inputType['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo form_dropdown($inputType['name'], $inputType['options'], $inputType['value'], "id='{$inputType['id']}'"); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo form_label($this->lang->line('player_statistics_threshold'), $inputThreshold['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo form_dropdown($inputThreshold['name'], $inputThreshold['options'], $inputThreshold['value'], "id='{$inputThreshold['id']}'"); ?>
                <?php
                echo form_submit($submit); ?>
            </div>
        </div>
</fieldset>

<?php
echo form_close(); ?>

<p><?php
echo $this->lang->line('player_statistics_threshold_current');

echo $threshold == 1 ? sprintf($this->lang->line('player_statistics_threshold_match'), $threshold) : sprintf($this->lang->line('player_statistics_threshold_matches'), $threshold); ?></p>

                <h3><?php echo $this->lang->line("player_statistics_statistics_menu"); ?></h3>
                <ul class="nav nav-tabs nav-stacked">
                <?php
                foreach ($this->Cache_Player_Statistics_model->methodMap as $statisticGroup => $method) { ?>
                    <li><a href="#<?php echo $statisticGroup; ?>"><?php echo $this->lang->line("player_statistics_{$statisticGroup}"); ?></a></li>
                <?php
                } ?>
                <?php
                foreach ($this->Cache_Player_Statistics_model->hungryMethodMap as $statisticGroup => $method) { ?>
                    <li><a href="#<?php echo $statisticGroup; ?>"><?php echo $this->lang->line("player_statistics_{$statisticGroup}"); ?></a></li>
                <?php
                } ?>
                <?php
                foreach ($this->Cache_Player_Statistics_model->otherMethodMap as $statisticGroup => $method) {
                    if (method_exists('Player_Statistics_helper', $method)) { ?>
                    <li<?php echo $season != 'all-time' ? ' class="disabled"' : ''; ?>><a href="#<?php echo $statisticGroup; ?>"><?php echo $this->lang->line("player_statistics_{$statisticGroup}"); ?><?php echo $season != 'all-time' ? ' (' . $this->lang->line("player_statistics_viewable_on_all_time_statistics") . ')' : ''; ?></a></li>
                <?php
                    }
                } ?>
                </ul>
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
        Player_Statistics_helper::$method($statistics, $season); ?>
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
    Player_Statistics_helper::$method($statistics, $season); ?>
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

<?php
if ($season == 'all-time') {
    $i = 0; ?>
        <div class="row-fluid">
    <?php
    foreach($this->Cache_Player_Statistics_model->otherMethodMap as $statisticGroup => $method) {
        if (method_exists('Player_Statistics_helper', $method)) { ?>
            <div class="span6">
                <?php
                Player_Statistics_helper::$method($statistics, $season); ?>
            </div>
        <?php
        }

        $i++;
    } ?>
        </div>
    <?php
} ?>
    </div>
</div>