<?php
$match_id = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id', $match->id),
);

$i = 0;
while($i < $match->h) {
    $id[$i] = array(
        'name'  => "id[{$i}]",
        'id'    => "id_{$i}",
        'value' => set_value("id[{$i}]", isset($goals[$i]->id) ? $goals[$i]->id : ''),
    );

    $minute[$i] = array(
        'name'    => "minute[{$i}]",
        'id'      => "minute_{$i}",
        'options' => array('' => '---') + Match_model::fetchMinutes(),
        'value'   => set_value("minute[{$i}]", isset($goals[$i]->minute) ? $goals[$i]->minute : ''),
        'attributes' => 'class="input-mini"',
    );

    $scorerId[$i] = array(
        'name'       => "scorer_id[{$i}]",
        'id'         => "scorer_id_{$i}",
        'options'    => array('' => '--- Select ---',
            '0'          => '-- ' . $this->lang->line('goal_own_goal') . ' --') + $this->Appearance_model->fetchForDropdown($match->id),
        'value'      => set_value("scorer_id[{$i}]", isset($goals[$i]->scorer_id) ? $goals[$i]->scorer_id : ''),
        'attributes' => 'class="input-medium"',
    );

    $assistId[$i] = array(
        'name'       => "assist_id[{$i}]",
        'id'         => "assist_id_{$i}",
        'options'    => array('' => '--- Select ---',
            '0'          => '-- ' . $this->lang->line('goal_no_assist') . ' --') + $this->Appearance_model->fetchForDropdown($match->id),
        'value'      => set_value("assist_id[{$i}]", isset($goals[$i]->assist_id) ? $goals[$i]->assist_id : ''),
        'attributes' => 'class="input-medium"',
    );

    $type[$i] = array(
        'name'       => "type[{$i}]",
        'id'         => "type_{$i}",
        'options'    => array('' => '--- Select ---') + Goal_model::fetchTypes(),
        'value'      => set_value("type[{$i}]", isset($goals[$i]->type) ? $goals[$i]->type : ''),
        'attributes' => 'class="input-medium"',
    );

    $bodyPart[$i] = array(
        'name'       => "body_part[{$i}]",
        'id'         => "body_part_{$i}",
        'options'    => array('' => '--- Select ---') + Goal_model::fetchBodyParts(),
        'value'      => set_value("body_part[{$i}]", isset($goals[$i]->body_part) ? $goals[$i]->body_part : ''),
        'attributes' => 'class="input-medium"',
    );

    $distance[$i] = array(
        'name'       => "distance[{$i}]",
        'id'         => "distance_{$i}",
        'options'    => array('' => '--- Select ---') + Goal_model::fetchDistances(),
        'value'      => set_value("distance[{$i}]", isset($goals[$i]->distance) ? $goals[$i]->distance : ''),
        'attributes' => 'class="input-medium"',
    );

    if (Configuration::get('include_goal_ratings') === true) {
        $rating[$i] = array(
            'name'       => "rating[{$i}]",
            'id'         => "rating_{$i}",
            'options'    => array('' => '---') + Goal_model::fetchRatings(),
            'value'      => set_value("rating[{$i}]", isset($goals[$i]->rating) ? $goals[$i]->rating : ''),
            'attributes' => 'class="input-mini"',
        );
    }

    $description[$i] = array(
        'name'        => "description[{$i}]",
        'id'          => "description_{$i}",
        'value'       => set_value("description[{$i}]", isset($goals[$i]->description) ? $goals[$i]->description : ''),
        'placeholder' => $this->lang->line('goal_description'),
        'class'       => 'input-medium',
    );

    $i++;
}

$submit = array(
    'name'  => 'submit',
    'class' => 'btn',
    'value' => $submitButtonText,
);

echo form_open($this->uri->uri_string());

$columnCount = 7;
$columnCount = Configuration::get('include_goal_ratings') === true ? $columnCount + 1 : $columnCount;

echo form_hidden($match_id['name'], $match_id['value']); ?>
<table class="no-more-tables table table-striped table-bordered">
    <thead>
        <tr>
            <th class="width-5-percent"><?php echo $this->lang->line('goal_minute'); ?></th>
            <th class="width-15-percent"><?php echo $this->lang->line('goal_scorer'); ?></th>
            <th class="width-15-percent"><?php echo $this->lang->line('goal_assister'); ?></th>
            <th class="width-15-percent"><?php echo $this->lang->line('goal_type'); ?></th>
            <th class="width-15-percent"><?php echo $this->lang->line('goal_body_part'); ?></th>
            <th class="width-15-percent"><?php echo $this->lang->line('goal_distance'); ?></th>
            <?php
            if (Configuration::get('include_goal_ratings') === true) { ?>
            <th><?php echo $this->lang->line('goal_rating'); ?></th>
            <?php
            } ?>
            <th class="width-15-percent"><?php echo $this->lang->line('goal_description'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php

    echo form_error('match_id', '<tr><td colspan="' . $columnCount . '"><div class="control-group error"><div class="controls"><span class="help-inline">', '</span></div></div></td></tr>');

    $i = 0;
    while($i < $match->h) {
        $errorMessages = '';
        $errorMessages .= form_error($id[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($minute[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($scorerId[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($assistId[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($type[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($bodyPart[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($distance[$i]['name'], '<span class="help-inline">', '</span><br />');

        if (Configuration::get('include_goal_ratings') === true) {
            $errorMessages .= form_error($rating[$i]['name'], '<span class="help-inline">', '</span><br />');
        }

        $errorMessages .= form_error($description[$i]['name'], '<span class="help-inline">', '</span><br />'); ?>
    <tr>
        <td data-title="<?php echo $this->lang->line('goal_minute'); ?>"><?php echo form_hidden($id[$i]['name'], $id[$i]['value']); ?>
            <div class="control-group<?php echo form_error($minute[$i]['name']) ? ' error' : ''; ?>">
                <div class="controls">
                    <?php echo form_dropdown($minute[$i]['name'], $minute[$i]['options'], $minute[$i]['value'], $minute[$i]['attributes']); ?>
                </div>
            </div>
        </td>
        <td data-title="<?php echo $this->lang->line('goal_scorer'); ?>">
            <div class="control-group<?php echo form_error($scorerId[$i]['name']) ? ' error' : ''; ?>">
                <div class="controls">
                    <?php echo form_dropdown($scorerId[$i]['name'], $scorerId[$i]['options'], $scorerId[$i]['value'], $scorerId[$i]['attributes']); ?>
                </div>
            </div>
        </td>
        <td data-title="<?php echo $this->lang->line('goal_assister'); ?>">
            <div class="control-group<?php echo form_error($assistId[$i]['name']) ? ' error' : ''; ?>">
                <div class="controls">
                    <?php echo form_dropdown($assistId[$i]['name'], $assistId[$i]['options'], $assistId[$i]['value'], $assistId[$i]['attributes']); ?>
                </div>
            </div>
        </td>
        <td data-title="<?php echo $this->lang->line('goal_type'); ?>">
            <div class="control-group<?php echo form_error($type[$i]['name']) ? ' error' : ''; ?>">
                <div class="controls">
                    <?php echo form_dropdown($type[$i]['name'], $type[$i]['options'], $type[$i]['value'], $type[$i]['attributes']); ?>
                </div>
            </div>
        </td>
        <td data-title="<?php echo $this->lang->line('goal_body_part'); ?>">
            <div class="control-group<?php echo form_error($bodyPart[$i]['name']) ? ' error' : ''; ?>">
                <div class="controls">
                    <?php echo form_dropdown($bodyPart[$i]['name'], $bodyPart[$i]['options'], $bodyPart[$i]['value'], $bodyPart[$i]['attributes']); ?>
                </div>
            </div>
        </td>
        <td data-title="<?php echo $this->lang->line('goal_distance'); ?>">
            <div class="control-group<?php echo form_error($distance[$i]['name']) ? ' error' : ''; ?>">
                <div class="controls">
                    <?php echo form_dropdown($distance[$i]['name'], $distance[$i]['options'], $distance[$i]['value'], $distance[$i]['attributes']); ?>
                </div>
            </div>
        </td>
        <?php
        if (Configuration::get('include_goal_ratings') === true) { ?>
        <td data-title="<?php echo $this->lang->line('goal_rating'); ?>">
            <div class="control-group<?php echo form_error($rating[$i]['name']) ? ' error' : ''; ?>">
                <div class="controls">
                    <?php echo form_dropdown($rating[$i]['name'], $rating[$i]['options'], $rating[$i]['value'], $rating[$i]['attributes']); ?>
                </div>
            </div>
        </td>
        <?php
        } ?>
        <td data-title="<?php echo $this->lang->line('goal_description'); ?>">
            <div class="control-group<?php echo form_error($description[$i]['name']) ? ' error' : ''; ?>">
                <div class="controls">
                    <?php echo form_textarea($description[$i]); ?>
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
    <tbody>
</table>

<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>