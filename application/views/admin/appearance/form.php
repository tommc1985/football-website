<?php
$match_id = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id', $match->id),
);

$noMotm = true;
$injuries = is_null($this->input->post("injury")) ? array() : $this->input->post("injury");
foreach ($playerCounts as $appearanceType => $playerCount) {
    $i = 0;
    while($i < $playerCount) {
        $id[$appearanceType][$i] = array(
            'name'  => "id[{$appearanceType}][{$i}]",
            'id'    => "id_{$appearanceType}_{$i}",
            'value' => set_value("id[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->id) ? $appearances[$appearanceType][$i]->id : ''),
        );

        $playerId[$appearanceType][$i] = array(
            'name'    => "player_id[{$appearanceType}][{$i}]",
            'id'      => "player_id_{$appearanceType}_{$i}",
            'options' => array('' => '--- Select ---') + $this->Player_Registration_model->fetchForDropdown($season),
            'value'   => set_value("player_id[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->player_id) ? $appearances[$appearanceType][$i]->player_id : ''),
            'attributes' => 'class="input-xlarge"',
        );

        $captain[$appearanceType][$i] = array(
            'name'    => "captain",
            'id'      => "captain_{$appearanceType}_{$i}",
            'checked' => set_radio('captain', $i, isset($appearances[$appearanceType][$i]->captain) && $appearances[$appearanceType][$i]->captain == 1),
            'value'   => $i,
        );

        if (Configuration::get('include_appearance_ratings') === true) {
            $rating[$appearanceType][$i] = array(
                'name'    => "rating[{$appearanceType}][{$i}]",
                'id'      => "rating_{$appearanceType}_{$i}",
                'options' => array('' => '----') + Appearance_model::fetchRatings(),
                'value'   => set_value("rating[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->rating) ? $appearances[$appearanceType][$i]->rating : ''),
                'attributes' => 'class="input-small"',
            );
        }

        $motm[$appearanceType][$i] = array(
            'name'    => "motm",
            'id'      => "motm_{$appearanceType}_{$i}",
            'checked' => set_radio('motm', "{$appearanceType}_{$i}", isset($appearances[$appearanceType][$i]->motm) && $appearances[$appearanceType][$i]->motm == 1),
            'value'   => "{$appearanceType}_{$i}",
        );

        if (set_radio('motm', "{$appearanceType}_{$i}", isset($appearances[$appearanceType][$i]->motm) && $appearances[$appearanceType][$i]->motm == 1)) {
            $noMotm = false;
        }

        $injury[$appearanceType][$i] = array(
            'name'  => "injury[{$appearanceType}][{$i}]",
            'id'    => "injury_{$appearanceType}_{$i}",
            'checked' => (isset($injuries[$appearanceType]) && in_array($i, $injuries[$appearanceType])) || isset($appearances[$appearanceType][$i]->injury) && $appearances[$appearanceType][$i]->injury == 1 ? true : false,
            'value' => $i,
        );

        $position[$appearanceType][$i] = array(
            'name'  => "position[{$appearanceType}][{$i}]",
            'id'    => "position_{$appearanceType}_{$i}",
            'options' => array('' => '---') + $this->Position_model->fetchForDropdown(),
            'value' => set_value("position[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->position) ? $appearances[$appearanceType][$i]->position : ''),
            'attributes' => 'class="input-small"',
        );

        if (Configuration::get('include_appearance_shirt_numbers') === true) {
            $shirt[$appearanceType][$i] = array(
                'name'  => "shirt[{$appearanceType}][{$i}]",
                'id'    => "shirt_{$appearanceType}_{$i}",
                'options' => array('' => '----') + Appearance_model::fetchShirtNumbers(),
                'value' => set_value("shirt[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->shirt) ? $appearances[$appearanceType][$i]->shirt : ''),
                'maxlength' => 3,
                'attributes' => 'class="input-small"',
            );
        }

        $on[$appearanceType][$i] = array(
            'name'  => "on[{$appearanceType}][{$i}]",
            'id'    => "on_{$appearanceType}_{$i}",
            'options' => array('' => '----') + Match_model::fetchMinutes(),
            'value' => set_value("on[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->on) ? $appearances[$appearanceType][$i]->on : ''),
            'maxlength' => 3,
            'attributes' => 'class="input-small"',
        );

        $off[$appearanceType][$i] = array(
            'name'  => "off[{$appearanceType}][{$i}]",
            'id'    => "off_{$appearanceType}_{$i}",
            'options' => array('' => '----') + Match_model::fetchMinutes(),
            'value' => set_value("off[{$appearanceType}][{$i}]", isset($appearances[$appearanceType][$i]->off) ? $appearances[$appearanceType][$i]->off : '' ),
            'maxlength' => 3,
            'attributes' => 'class="input-small"',
        );

        $i++;
    }
}

$motm['no_motm'] = array(
    'name'    => "motm",
    'id'      => "motm_no_motm",
    'checked' => $noMotm,
    'value'   => 0,
);

$submit = array(
    'name'  => 'submit',
    'class' => 'btn',
    'value' => $submitButtonText,
);

echo form_open($this->uri->uri_string());
echo form_hidden($match_id['name'], $match_id['value']);

$columnCount = 7;
$columnCount = Configuration::get('include_appearance_ratings') === true ? $columnCount + 1 : $columnCount;
$columnCount = Configuration::get('include_appearance_shirt_numbers') === true ? $columnCount + 1 : $columnCount; ?>

<?php
foreach ($playerCounts as $appearanceType => $playerCount) { ?>
<h3><?php echo $this->lang->line("appearance_{$appearanceType}_appearances"); ?></h3>
<table class="no-more-tables table table-striped table-bordered">
    <thead>
        <tr>
            <th class="width-25-percent"><?php echo $this->lang->line('appearance_player'); ?></th>
            <th class="width-10-percent"><?php echo $this->lang->line('appearance_captain'); ?></th>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <th><?php echo $this->lang->line('appearance_rating'); ?></th>
            <?php
            } ?>
            <th class="width-10-percent"><?php echo $this->lang->line('appearance_motm'); ?></th>
            <th class="width-10-percent"><?php echo $this->lang->line('appearance_injury'); ?></th>
            <th class="width-15-percent"><?php echo $this->lang->line('appearance_position'); ?></th>
            <?php
            if (Configuration::get('include_appearance_shirt_numbers') === true) { ?>
            <th><?php echo $this->lang->line('appearance_shirt'); ?></th>
            <?php
            } ?>
            <th class="width-15-percent"><?php echo $this->lang->line('appearance_subbed_on'); ?></th>
            <th class="width-15-percent"><?php echo $this->lang->line('appearance_subbed_off'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($appearanceType == 'starts') {
            echo form_error('captain', '<tr><td colspan="' . $columnCount . '"><div class="control-group error"><div class="controls"><span class="help-inline">', '</span></div></div></td></tr>');
            echo form_error('match_id', '<tr><td colspan="' . $columnCount . '"><div class="control-group error"><div class="controls"><span class="help-inline">', '</span></div></div></td></tr>');

        }
            $i = 0;
            while($i < $playerCount) {
                $errorMessages = '';
                $errorMessages .= form_error($id[$appearanceType][$i]['name'], '<span class="help-inline">', '</span><br />');
                $errorMessages .= form_error($playerId[$appearanceType][$i]['name'], '<span class="help-inline">', '</span><br />');

                if (Configuration::get('include_appearance_ratings') === true) {
                    $errorMessages .= form_error($rating[$appearanceType][$i]['name'], '<span class="help-inline">', '</span><br />');
                }

                $errorMessages .= form_error($injury[$appearanceType][$i]['name'], '<span class="help-inline">', '</span><br />');
                $errorMessages .= form_error($position[$appearanceType][$i]['name'], '<span class="help-inline">', '</span><br />');

                if (Configuration::get('include_appearance_shirt_numbers') === true) {
                    $errorMessages .= form_error($shirt[$appearanceType][$i]['name'], '<span class="help-inline">', '</span><br />');
                }

                $errorMessages .= form_error($on[$appearanceType][$i]['name'], '<span class="help-inline">', '</span><br />');
                $errorMessages .= form_error($off[$appearanceType][$i]['name'], '<span class="help-inline">', '</span><br />');
             ?>
        <tr>
            <td data-title="<?php echo $this->lang->line('appearance_player'); ?>"><?php echo form_hidden($id[$appearanceType][$i]['name'], $id[$appearanceType][$i]['value']); ?>
                <div class="control-group<?php echo form_error($playerId[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($playerId[$appearanceType][$i]['name'], $playerId[$appearanceType][$i]['options'], $playerId[$appearanceType][$i]['value'], $playerId[$appearanceType][$i]['attributes']); ?>
                    </div>
                </div>
            </td>
            <td data-title="<?php echo $this->lang->line('appearance_captain'); ?>" class="text-align-center">
                <?php
                if ($appearanceType == 'starts') { ?>
                <div class="control-group<?php echo form_error($captain[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_radio($captain[$appearanceType][$i]); ?>
                    </div>
                </div>
                <?php
                } else { ?>
                &nbsp;
                <?php
                } ?>
            </td>
            <?php
            if (Configuration::get('include_appearance_ratings') === true) { ?>
            <td data-title="<?php echo $this->lang->line('appearance_rating'); ?>">
                <div class="control-group<?php echo form_error($rating[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($rating[$appearanceType][$i]['name'], $rating[$appearanceType][$i]['options'], $rating[$appearanceType][$i]['value'], $rating[$appearanceType][$i]['attributes']); ?>
                    </div>
                </div>
            </td>
            <?php
            } ?>
            <td data-title="<?php echo $this->lang->line('appearance_motm'); ?>" class="text-align-center">
                <div class="control-group<?php echo form_error($motm[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_radio($motm[$appearanceType][$i]); ?>
                    </div>
                </div>
            </td>
            <td data-title="<?php echo $this->lang->line('appearance_injury'); ?>" class="text-align-center">
                <div class="control-group<?php echo form_error($injury[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_checkbox($injury[$appearanceType][$i]); ?>
                    </div>
                </div>
            </td>
            <td data-title="<?php echo $this->lang->line('appearance_position'); ?>">
                <div class="control-group<?php echo form_error($position[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($position[$appearanceType][$i]['name'], $position[$appearanceType][$i]['options'], $position[$appearanceType][$i]['value'], $position[$appearanceType][$i]['attributes']); ?>
                    </div>
                </div>
            </td>
            <?php
            if (Configuration::get('include_appearance_shirt_numbers') === true) { ?>
            <td data-title="<?php echo $this->lang->line('appearance_shirt'); ?>">
                <div class="control-group<?php echo form_error($shirt[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($shirt[$appearanceType][$i]['name'], $shirt[$appearanceType][$i]['options'], $shirt[$appearanceType][$i]['value'], $shirt[$appearanceType][$i]['attributes']); ?>
                    </div>
                </div>
            </td>
            <?php
            } ?>
            <td data-title="<?php echo $this->lang->line('appearance_subbed_on'); ?>">
                <?php
                if ($appearanceType != 'starts') { ?>
                <div class="control-group<?php echo form_error($on[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($on[$appearanceType][$i]['name'], $on[$appearanceType][$i]['options'], $on[$appearanceType][$i]['value'], $on[$appearanceType][$i]['attributes']); ?>
                    </div>
                </div>
                <?php
                } ?>
            </td>
            <td data-title="<?php echo $this->lang->line('appearance_subbed_off'); ?>">
                <div class="control-group<?php echo form_error($off[$appearanceType][$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($off[$appearanceType][$i]['name'], $off[$appearanceType][$i]['options'], $off[$appearanceType][$i]['value'], $off[$appearanceType][$i]['attributes']); ?>
                    </div>
                </div>
            </td>
        </tr>
        <?php
                if ($errorMessages) { ?>
                <tr>
                    <td colspan="<?php echo $columnCount; ?>">
                        <div class="control-group error">
                            <div class="controls">
                                <?php echo $errorMessages; ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
                }
                $i++;
            } ?>
    </tbody>
</table>
<?php
} ?>

<h3><?php echo $this->lang->line('appearance_no_motm'); ?></h3>
<div class="control-group<?php echo form_error($motm['no_motm']['name']) ? ' error' : ''; ?>">
    <div class="controls">
        <label for="<?php echo $motm['no_motm']['id']; ?>">
        <?php echo form_radio($motm['no_motm']); ?>
        <?php echo $this->lang->line('appearance_no_motm'); ?>
        </label>
        <?php
        if (form_error($motm['no_motm']['name'])) { ?>
            <span class="help-inline"><?php echo form_error($motm['name']); ?></span>
        <?php
        } ?>
    </div>
</div>

<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>