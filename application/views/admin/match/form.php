<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($match->id) ? $match->id : ''),
);

$oppositionId = array(
    'name'       => 'opposition_id',
    'id'         => 'opposition-id',
    'options'    => array('' => '--- Select ---') + $this->Opposition_model->fetchForDropdown(),
    'value'      => set_value('opposition_id', isset($match->opposition_id) ? $match->opposition_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$competitionId = array(
    'name'       => 'competition_id',
    'id'         => 'competition-id',
    'options'    => array('' => '--- Select ---') + $this->Competition_model->fetchForDropdown(),
    'value'      => set_value('competition_id', isset($match->competition_id) ? $match->competition_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$competitionStageId = array(
    'name'       => 'competition_stage_id',
    'id'         => 'competition-stage-id',
    'options'    => array('' => '--- Select ---') + $this->Competition_Stage_model->fetchForDropdown(),
    'value'      => set_value('competition_stage_id', isset($match->competition_stage_id) ? $match->competition_stage_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$venue = array(
    'name'       => 'venue',
    'id'         => 'venue',
    'options'    => array('' => '--- Select ---') + $this->Match_model->fetchVenues(),
    'value'      => set_value('venue', isset($match->venue) ? $match->venue : ''),
    'attributes' => 'class="input-medium"',
);

$location = array(
    'name'  => 'location',
    'id'    => 'location',
    'value' => set_value('location', isset($match->location) ? $match->location : ''),
    'class' => 'input-xlarge',
);

$officialId = array(
    'name'       => 'official_id',
    'id'         => 'official-id',
    'options'    => array('0' => '--- Select ---') + $this->Official_model->fetchForDropdown(),
    'value'      => set_value('official_id', isset($match->official_id) ? $match->official_id : ''),
    'attributes' => 'class="input-large"',
);

$attendance = array(
    'name'  => 'attendance',
    'id'    => 'attendance',
    'value' => set_value('attendance', isset($match->attendance) ? $match->attendance : ''),
    'class' => 'input-mini',
);

$h = array(
    'name'  => 'h',
    'id'    => 'h',
    'value' => set_value('h', isset($match->h) ? $match->h : ''),
    'class' => 'input-mini',
);

$a = array(
    'name'  => 'a',
    'id'    => 'a',
    'value' => set_value('a', isset($match->a) ? $match->a : ''),
    'class' => 'input-mini',
);

$report = array(
    'name'  => 'report',
    'id'    => 'report',
    'value' => set_value('report', isset($match->report) ? $match->report : ''),
    'class' => 'input-xlarge',
);

$date = array(
    'name'  => 'date',
    'id'    => 'date',
    'value' => set_value('date', isset($match->date) ? substr($match->date, 0, 10) : ''),
    'class' => 'input-medium',
);

$time = array(
    'name'  => 'time',
    'id'    => 'time',
    'value' => set_value('time', isset($match->time) ? substr($match->date, 11, 5) : Configuration::get('usual_match_ko_time')),
    'class' => 'input-small',
);

$hET = array(
    'name'  => 'h_et',
    'id'    => 'h_et',
    'value' => set_value('h_et', isset($match->h_et) ? $match->h_et : ''),
);

$aET = array(
    'name'  => 'a_et',
    'id'    => 'a_et',
    'value' => set_value('a_et', isset($match->a_et) ? $match->a_et : ''),
);

$hPen = array(
    'name'  => 'h_pen',
    'id'    => 'h_pen',
    'value' => set_value('h_pen', isset($match->h_pen) ? $match->h_pen : ''),
);

$aPen = array(
    'name'  => 'a_pen',
    'id'    => 'a_pen',
    'value' => set_value('a_pen', isset($match->a_pen) ? $match->a_pen : ''),
);

$status = array(
    'name'       => 'status',
    'id'         => 'status',
    'options'    => array('' => '--- Select ---') + $this->Match_model->fetchStatuses(),
    'value'      => set_value('status', isset($match->status) ? $match->status : ''),
    'attributes' => 'class="input-medium"',
);

$submit = array(
    'name'  => 'submit',
    'class' => 'btn',
    'value' => $submitButtonText,
); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
    <?php echo form_hidden($id['name'], $id['value']); ?>
    <fieldset>
        <legend><?php echo $this->lang->line('match_match_details');?></legend>
        <div class="control-group<?php echo form_error($date['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_date'), $date['id']); ?>
            <div class="controls">
                <?php echo form_date($date); ?>
                <?php
                if (form_error($date['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($date['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($time['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_time'), $time['id']); ?>
            <div class="controls">
                <?php echo form_time($time); ?>
                <?php
                if (form_error($time['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($time['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($oppositionId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_opposition'), $oppositionId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($oppositionId['name'], $oppositionId['options'], $oppositionId['value'], $oppositionId['attributes']); ?>
                <?php
                if (form_error($oppositionId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($oppositionId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($competitionId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_competition'), $competitionId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($competitionId['name'], $competitionId['options'], $competitionId['value'], $competitionId['attributes']); ?>
                <?php
                if (form_error($competitionId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($competitionId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($competitionStageId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_stage'), $competitionStageId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($competitionStageId['name'], $competitionStageId['options'], $competitionStageId['value'], $competitionStageId['attributes']); ?>
                <?php
                if (form_error($competitionStageId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($competitionStageId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($venue['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_venue'), $venue['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($venue['name'], $venue['options'], $venue['value'], $venue['attributes']); ?>
                <?php
                if (form_error($venue['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($venue['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($location['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_location'), $location['id']); ?>
            <div class="controls">
                <?php echo form_input($location); ?>
                <?php
                if (form_error($location['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($location['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($officialId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_officialId'), $officialId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($officialId['name'], $officialId['options'], $officialId['value'], $officialId['attributes']); ?>
                <?php
                if (form_error($officialId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($officialId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php
        if (Configuration::get('include_match_attendances') === true) { ?>
        <div class="control-group<?php echo form_error($attendance['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_attendance'), $attendance['id']); ?>
            <div class="controls">
                <?php echo form_input($attendance); ?>
                <?php
                if (form_error($attendance['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($attendance['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php
        } ?>
        <div class="control-group<?php echo form_error($h['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_your_score'), $h['id']); ?>
            <div class="controls">
                <?php echo form_input($h); ?>
                <?php
                if (form_error($h['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($h['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($a['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_opposition_score'), $a['id']); ?>
            <div class="controls">
                <?php echo form_input($a); ?>
                <?php
                if (form_error($a['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($a['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($report['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_report'), $report['id']); ?>
            <div class="controls">
                <?php echo form_textarea($report); ?>
                <?php
                if (form_error($report['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($report['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($hET['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_your_score_et'), $hET['id']); ?>
            <div class="controls">
                <?php echo form_input($hET); ?>
                <?php
                if (form_error($hET['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($hET['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($aET['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_opposition_score_et'), $aET['id']); ?>
            <div class="controls">
                <?php echo form_input($aET); ?>
                <?php
                if (form_error($aET['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($aET['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($hPen['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_your_score_penalties'), $hPen['id']); ?>
            <div class="controls">
                <?php echo form_input($hPen); ?>
                <?php
                if (form_error($hPen['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($hPen['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>

        <div class="control-group<?php echo form_error($aPen['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_opposition_score_penalties'), $aPen['id']); ?>
            <div class="controls">
                <?php echo form_input($aPen); ?>
                <?php
                if (form_error($aPen['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($aPen['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($status['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('match_status'), $status['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($status['name'], $status['options'], $status['value'], $status['attributes']); ?>
                <?php
                if (form_error($status['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($status['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>