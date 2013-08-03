<?php
$this->load->model('League_model');
$this->load->model('League_Match_model');
$this->load->model('League_Registration_model');
$this->load->model('Opposition_model');

$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($leagueMatch->id) ? $leagueMatch->id : ''),
);

$leagueId = array(
    'name'    => 'league_id',
    'id'      => 'league-id',
    'value'   => $league->id,
);

$date = array(
    'name'  => 'date',
    'id'    => 'date',
    'value' => set_value('date', isset($leagueMatch->date) ? substr($leagueMatch->date, 0, 10) : ''),
    'class' => 'input-medium',
);

$time = array(
    'name'  => 'time',
    'id'    => 'time',
    'value' => set_value('time', isset($leagueMatch->date) ? substr($leagueMatch->date, 11, 5) : Configuration::get('usual_match_ko_time')),
    'class' => 'input-small',
);

$hOppositionId = array(
    'name'       => 'h_opposition_id',
    'id'         => 'h-opposition-id',
    'options'    => array('' => '--- Select ---') + $this->League_Registration_model->fetchForDropdown($league->id),
    'value'      => set_value('h_opposition_id', isset($leagueMatch->h_opposition_id) ? $leagueMatch->h_opposition_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$aOppositionId = array(
    'name'       => 'a_opposition_id',
    'id'         => 'a-opposition-id',
    'options'    => array('' => '--- Select ---') + $this->League_Registration_model->fetchForDropdown($league->id),
    'value'      => set_value('a_opposition_id', isset($leagueMatch->a_opposition_id) ? $leagueMatch->a_opposition_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$hScore = array(
    'name'        => 'h_score',
    'id'          => 'h-score',
    'value'       => set_value('h_score', isset($leagueMatch->h_score) ? $leagueMatch->h_score : ''),
    'placeholder' => $this->lang->line('league_match_home_score'),
    'class'       => 'input-mini',
);

$aScore = array(
    'name'        => 'a_score',
    'id'          => 'a-score',
    'value'       => set_value('a_score', isset($leagueMatch->a_score) ? $leagueMatch->a_score : ''),
    'placeholder' => $this->lang->line('league_match_away_score'),
    'class'       => 'input-mini',
);

$status = array(
    'name'    => 'status',
    'id'      => 'status',
    'options' => array('' => '--- Select ---') + $this->League_Match_model->fetchStatuses(),
    'value' => set_value('status', isset($leagueMatch->status) ? $leagueMatch->status : ''),
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
    <?php echo form_hidden($leagueId['name'], $leagueId['value']); ?>
    <fieldset>
        <legend><?php echo $this->lang->line('league_match_league_match_details');?></legend>
        <div class="control-group<?php echo form_error($leagueId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_match_league'), $leagueId['id']); ?>
            <div class="controls">
                <?php echo $league->short_name; ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($date['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_match_date'), $date['id']); ?>
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
            <?php echo form_label($this->lang->line('league_match_time'), $time['id']); ?>
            <div class="controls">
                <?php echo form_time($time); ?>
                <?php
                if (form_error($time['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($time['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($hOppositionId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_match_home_team'), $hOppositionId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($hOppositionId['name'], $hOppositionId['options'], $hOppositionId['value'], $hOppositionId['attributes']); ?>
                <?php
                if (form_error($hOppositionId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($hOppositionId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($aOppositionId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_match_away_team'), $aOppositionId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($aOppositionId['name'], $aOppositionId['options'], $aOppositionId['value'], $aOppositionId['attributes']); ?>
                <?php
                if (form_error($aOppositionId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($aOppositionId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($hScore['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_match_home_score'), $hScore['id']); ?>
            <div class="controls">
                <?php echo form_input($hScore); ?>
                <?php
                if (form_error($hScore['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($hScore['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($aScore['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_match_away_score'), $aScore['id']); ?>
            <div class="controls">
                <?php echo form_input($aScore); ?>
                <?php
                if (form_error($aScore['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($aScore['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($status['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_match_status'), $status['id']); ?>
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