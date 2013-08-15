<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('player_goal_statistics_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("player_goal_statistics_matches") . ')' : ''); ?></h2>

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

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('player_goal_statistics_show'),
    'class'   => 'btn',
); ?>

<fieldset>
        <legend><?php echo $this->lang->line('global_filters');?></legend>
        <div class="control-group">
            <?php echo form_label($this->lang->line('player_goal_statistics_competition_type'), $inputType['id'], array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo form_dropdown($inputType['name'], $inputType['options'], $inputType['value'], "id='{$inputType['id']}'"); ?>
                <?php
                echo form_submit($submit); ?>
            </div>
        </div>
</fieldset>

<?php
echo form_close(); ?>

                <h3><?php echo $this->lang->line("player_goal_statistics_statistics_menu"); ?></h3>
                <ul class="nav nav-tabs nav-stacked">
                    <li><a href="#by_scorer"><?php echo $this->lang->line("player_goal_statistics_by_scorer"); ?></a></li>
                    <?php
                    foreach($goalTypes as $goalType => $goalTypeFriendly) {
                        if ($goalType != 0) { ?>
                    <li><a href="#by_goal_type_<?php echo $goalType; ?>"><?php echo $this->lang->line("player_goal_statistics_by_goal_type_{$goalType}"); ?></a></li>
                    <?php
                        }
                    }

                    foreach($bodyParts as $bodyPart => $bodyPartFriendly) { ?>
                    <li><a href="#by_body_part_<?php echo $bodyPart; ?>"><?php echo $this->lang->line("player_goal_statistics_by_body_part_{$bodyPart}"); ?></a></li>
                    <?php
                    }

                    foreach($distances as $distance => $distanceFriendly) { ?>
                    <li><a href="#by_distance_<?php echo $distance; ?>"><?php echo $this->lang->line("player_goal_statistics_by_distance_{$distance}"); ?></a></li>
                    <?php
                    }

                    foreach($minuteIntervals as $minuteInterval => $minuteIntervalFriendly) { ?>
                    <li><a href="#by_minute_interval_<?php echo $minuteInterval; ?>"><?php echo $this->lang->line("player_goal_statistics_by_minute_interval_{$minuteInterval}"); ?></a></li>
                    <?php
                    }

                    foreach($goalTypes as $goalType => $goalTypeFriendly) { ?>
                    <li><a href="#assist_by_goal_type_<?php echo $goalType; ?>"><?php echo $this->lang->line("player_goal_statistics_assist_by_goal_type_{$goalType}"); ?></a></li>
                    <?php
                    } ?>
                </ul>
            </div>
        </div>


        <div class="row-fluid">
            <div class="span6">
                <?php
                Player_Goal_Statistics_helper::scoringCombination($statistics); ?>
            </div>
            <div class="span6"></div>
        </div><?php
$i = 0;
foreach($goalTypes as $goalType => $goalTypeFriendly) {
    if ($goalType != 0) {
        if (0 == $i % 2) { ?>
        <div class="row-fluid">
        <?php
        } ?>
            <div class="span6">
        <?php
        Player_Goal_Statistics_helper::scorerByGoalType($statistics, $goalType); ?>
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
}

$i = 0;
foreach($bodyParts as $bodyPart => $bodyPartFriendly) {
    if (0 == $i % 2) { ?>
        <div class="row-fluid">
    <?php
    } ?>
            <div class="span6">
                <?php
        Player_Goal_Statistics_helper::scorerByBodyPart($statistics, $bodyPart); ?>
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
}

$i = 0;
foreach($distances as $distance => $distanceFriendly) {
    if (0 == $i % 2) { ?>
        <div class="row-fluid">
    <?php
    } ?>
            <div class="span6">
                <?php
        Player_Goal_Statistics_helper::scorerByDistance($statistics, $distance); ?>
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
}

$i = 0;
foreach($minuteIntervals as $minuteIntervals => $minuteIntervalsFriendly) {
    if (0 == $i % 2) { ?>
        <div class="row-fluid">
    <?php
    } ?>
            <div class="span6">
                <?php
        Player_Goal_Statistics_helper::scorerByMinuteInterval($statistics, $minuteIntervals); ?>
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
}

$i = 0;
foreach($goalTypes as $goalType => $goalTypeFriendly) {
    if (0 == $i % 2) { ?>
        <div class="row-fluid">
    <?php
    } ?>
            <div class="span6">
        <?php
        Player_Goal_Statistics_helper::assistByGoalType($statistics, $goalType); ?>
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