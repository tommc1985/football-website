<?php
$match_id = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id', $match->id),
);

$i = 0;
while($i < $cardCount) {
    $id[$i] = array(
        'name'  => "id[{$i}]",
        'id'    => "id_{$i}",
        'value' => set_value("id[{$i}]", isset($cards[$i]->id) ? $cards[$i]->id : ''),
    );

    $minute[$i] = array(
        'name'    => "minute[{$i}]",
        'id'      => "minute_{$i}",
        'options' => array('' => '--- Select ---') + Match_model::fetchMinutes(),
        'value'   => set_value("minute[{$i}]", isset($cards[$i]->minute) ? $cards[$i]->minute : ''),
        'attributes' => 'class="input-mini"',
    );

    $playerId[$i] = array(
        'name'  => "player_id[{$i}]",
        'id'    => "player_id{$i}",
        'options' => array('' => '--- Select ---') + $this->Appearance_model->fetchForDropdown($match->id),
        'value' => set_value("player_id[{$i}]", isset($cards[$i]->player_id) ? $cards[$i]->player_id : ''),
        'attributes' => 'class="input-large"',
    );

    $offence[$i] = array(
        'name'  => "offence[{$i}]",
        'id'    => "offence{$i}",
        'options' => array('' => '--- Select ---') + Card_model::fetchOffencesForDropdown(),
        'value' => set_value("offence[{$i}]", isset($cards[$i]->offence) ? $cards[$i]->offence : ''),
        'attributes' => 'class="input-xlarge"',
    );

    $i++;
}

echo form_open($this->uri->uri_string()); ?>
<table class="no-more-tables table table-striped table-bordered">
    <?php echo form_hidden($match_id['name'], $match_id['value']);
    echo form_error('match_id', '<tr><td colspan="3"><div class="control-group error"><div class="controls"><span class="help-inline">', '</span></div></div></td></tr>'); ?>
    <thead>
        <tr>
            <th><?php echo $this->lang->line('card_minute'); ?></th>
            <th><?php echo $this->lang->line('card_player'); ?></th>
            <th><?php echo $this->lang->line('card_offence'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    while($i < $cardCount) {
        $errorMessages = '';
        $errorMessages .= form_error($id[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($minute[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($playerId[$i]['name'], '<span class="help-inline">', '</span><br />');
        $errorMessages .= form_error($offence[$i]['name'], '<span class="help-inline">', '</span><br />'); ?>
        <tr>
            <td data-title="<?php echo $this->lang->line('card_minute'); ?>"><?php echo form_hidden($id[$i]['name'], $id[$i]['value']); ?>
                <div class="control-group<?php echo form_error($minute[$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($minute[$i]['name'], $minute[$i]['options'], $minute[$i]['value'], $minute[$i]['attributes']); ?>
                    </div>
                </div>
            </td>
            <td data-title="<?php echo $this->lang->line('card_player'); ?>">
                <div class="control-group<?php echo form_error($playerId[$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($playerId[$i]['name'], $playerId[$i]['options'], $playerId[$i]['value'], $playerId[$i]['attributes']); ?>
                    </div>
                </div>
            </td>
            <td data-title="<?php echo $this->lang->line('card_offence'); ?>">
                <div class="control-group<?php echo form_error($offence[$i]['name']) ? ' error' : ''; ?>">
                    <div class="controls">
                        <?php echo form_dropdown($offence[$i]['name'], $offence[$i]['options'], $offence[$i]['value'], $offence[$i]['attributes']); ?>
                    </div>
                </div>
            </td>
        </tr>
    <?php
    if ($errorMessages) { ?>
        <tr>
            <td colspan="3">
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

<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>