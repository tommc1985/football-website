<div class="row-fluid">
    <div class="span12">

        <h2><?php echo $this->lang->line('club_statistics_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("club_statistics_matches") . ')' : ''); ?></h2>

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

$submit = array(
    'name'    => 'submit',
    'id'      => 'submit',
    'value'   => $this->lang->line('club_statistics_show'),
    'class'   => 'btn',
); ?>

<fieldset>
        <legend><?php echo $this->lang->line('global_filters');?></legend>
        <div class="control-group">
            <div class="controls">
                <?php echo form_label($this->lang->line('club_statistics_competition_type'), $inputType['id']); ?>
                <?php echo form_dropdown($inputType['name'], $inputType['options'], $inputType['value'], "id='{$inputType['id']}'"); ?>
            </div>
        </div>
        <?php
        echo form_submit($submit); ?>
</fieldset>
<?php
echo form_close(); ?>
            </div>
        </div>

<?php
$i = 0;
foreach ($this->Cache_Club_Statistics_model->methodMap as $statisticGroup => $method) {
    foreach ($venues as $venue) {
        if (0 == $i % 2) { ?>
        <div class="row-fluid">
        <?php
        } ?>
            <div class="span6">
                <?php
                Club_Statistics_helper::$method($statistics, $venue); ?>
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
foreach ($this->Cache_Club_Statistics_model->hungryMethodMap as $statisticGroup => $method) {
    if (0 == $i % 2) { ?>
        <div class="row-fluid">
    <?php
    } ?>
            <div class="span6">
                        <?php
                Club_Statistics_helper::$method($statistics); ?>
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