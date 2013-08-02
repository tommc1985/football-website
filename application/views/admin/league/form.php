<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($league->id) ? $league->id : ''),
);

$competitionId = array(
    'name'       => 'competition_id',
    'id'         => 'competition_id',
    'options'    => array('' => '--- Select ---') + $this->Competition_model->fetchForDropdown(),
    'value'      => set_value('competition_id', isset($league->competition_id) ? $league->competition_id : ''),
    'attributes' => 'class="input-xlarge"',
);

$season = array(
    'name'       => 'season',
    'id'         => 'season',
    'options'    => array('' => '--- Select ---') + $this->Season_model->fetchForDropdown(),
    'value'      => set_value('season', isset($league->season) ? $league->season : ''),
    'attributes' => 'class="input-large"',
);

$name = array(
    'name'        => 'name',
    'id'          => 'name',
    'value'       => set_value('name', isset($league->name) ? $league->name : ''),
    'placeholder' => $this->lang->line('league_name'),
    'class'       => 'input-xlarge',
);

$shortName = array(
    'name'        => 'short_name',
    'id'          => 'short-name',
    'value'       => set_value('short_name', isset($league->short_name) ? $league->short_name : ''),
    'placeholder' => $this->lang->line('league_short_name'),
    'class'       => 'input-large',
);

$abbreviation = array(
    'name'        => 'abbreviation',
    'id'          => 'abbreviation',
    'value'       => set_value('abbreviation', isset($league->abbreviation) ? $league->abbreviation : ''),
    'placeholder' => $this->lang->line('league_abbreviation'),
    'class'       => 'input-small',
);

$pointsForWin = array(
    'name'        => 'points_for_win',
    'id'          => 'points-for-win',
    'value'       => set_value('points_for_win', isset($league->points_for_win) ? $league->points_for_win : ''),
    'placeholder' => $this->lang->line('league_points_for_win'),
    'class'       => 'input-mini',
);

$pointsForDraw = array(
    'name'        => 'points_for_draw',
    'id'          => 'points-for-draw',
    'value'       => set_value('points_for_draw', isset($league->points_for_draw) ? $league->points_for_draw : ''),
    'placeholder' => $this->lang->line('league_points_for_draw'),
    'class'       => 'input-mini',
);

$submit = array(
    'name'  => 'submit',
    'class' => 'btn',
    'value' => $submitButtonText,
);

echo form_open($this->uri->uri_string()); ?>
    <?php echo form_hidden($id['name'], $id['value']); ?>
    <fieldset>
        <legend><?php echo $this->lang->line('league_league_details');?></legend>
        <div class="control-group<?php echo form_error($competitionId['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_competition'), $competitionId['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($competitionId['name'], $competitionId['options'], $competitionId['value'], $competitionId['attributes']); ?>
                <?php
                if (form_error($competitionId['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($competitionId['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($season['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_season'), $season['id']); ?>
            <div class="controls">
                <?php echo form_dropdown($season['name'], $season['options'], $season['value'], $season['attributes']); ?>
                <?php
                if (form_error($season['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($season['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($name['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_name'), $name['id']); ?>
            <div class="controls">
                <?php echo form_input($name); ?>
                <?php
                if (form_error($name['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($name['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($shortName['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_short_name'), $shortName['id']); ?>
            <div class="controls">
                <?php echo form_input($shortName); ?>
                <?php
                if (form_error($shortName['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($shortName['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($abbreviation['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_abbreviation'), $abbreviation['id']); ?>
            <div class="controls">
                <?php echo form_input($abbreviation); ?>
                <?php
                if (form_error($abbreviation['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($abbreviation['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($pointsForWin['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_points_for_win'), $pointsForWin['id']); ?>
            <div class="controls">
                <?php echo form_input($pointsForWin); ?>
                <?php
                if (form_error($pointsForWin['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($pointsForWin['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($pointsForDraw['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('league_points_for_draw'), $pointsForDraw['id']); ?>
            <div class="controls">
                <?php echo form_input($pointsForDraw); ?>
                <?php
                if (form_error($pointsForDraw['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($pointsForDraw['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>