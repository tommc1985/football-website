<div class="row-fluid">
    <div class="span12">
        <h2><?php echo $this->lang->line('match_result_details'); ?></h2>

        <div class="row-fluid">
            <div class="span12" itemscope itemtype="http://schema.org/SportsEvent">
                <?php $this->load->view("themes/{$theme}/match/_match_details.php"); ?>
            </div>
        </div>
<?php
$matchId = array(
    'name'  => 'match_id',
    'id'    => 'match_id',
    'value' => set_value('match_id', $match->id),
);

$i = 0;
while($i < $placingCount) {
    $id[$i] = array(
        'name'  => "id[{$i}]",
        'id'    => "id_{$i}",
        'value' => set_value("id[{$i}]", isset($votes[$i]->id) ? $votes[$i]->id : ''),
    );

    $playerId[$i] = array(
        'name'       => "player_id[{$i}]",
        'id'         => "player_id_{$i}",
        'options'    => array('' => '--- Select ---') + $this->Appearance_model->fetchForDropdown($match->id),
        'value'      => set_value("player_id[{$i}]", isset($votes[$i]->player_id) ? $votes[$i]->player_id : ''),
        'attributes' => 'class="input-medium"',
    );

    $i++;
}

$submit = array(
    'name'  => 'submit',
    'class' => 'btn',
    'value' => $submitButtonText,
); ?>
        <div class="row-fluid">
            <div class="span12">
<?php
echo form_open($this->uri->uri_string()); ?>
    <?php echo form_hidden($matchId['name'], $matchId['value']); ?>
    <fieldset>
        <legend><?php echo $this->lang->line('player_player_details');?></legend>
        <?php
        $i = 0;
        while ($i < $placingCount) { ?>
        <div class="control-group<?php echo form_error($playerId[$i]['name']) ? ' error' : ''; ?>">
            <?php echo form_label(sprintf($this->lang->line('motm_nth_place'), Utility_helper::ordinalWithSuffix($i + 1)), $playerId[$i]['id']); ?>
            <div class="controls">
                <?php echo form_hidden($id[$i]['name'], $id[$i]['value']); ?>
                <?php echo form_dropdown($playerId[$i]['name'], $playerId[$i]['options'], $playerId[$i]['value'], $playerId[$i]['attributes']); ?>
                <?php
                if (form_error($playerId[$i]['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($playerId[$i]['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php
            $i++;
        } ?>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>