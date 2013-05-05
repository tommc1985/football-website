<h2><?php echo $this->lang->line('head_to_head_title'); ?><?php
echo $opposition ? ' - ' . Opposition_helper::name($opposition) : ''; ?></h2>

<?php
echo form_open($this->uri->uri_string());

$inputOpposition = array(
    'name'    => 'opposition',
    'id'      => 'opposition',
    'options' => array('' => '--- Select ---') + $this->Opposition_model->fetchForDropdown(),
    'value'   => set_value('opposition', $opposition),
);

echo form_label($this->lang->line('head_to_head_competition_type'), $inputOpposition['name']);
echo form_dropdown($inputOpposition['name'], $inputOpposition['options'], $inputOpposition['value']); ?>

<?php
echo form_submit('submit', $this->lang->line('head_to_head_show'));
echo form_close(); ?>

<?php
if ($opposition) { ?>
<h3><?php echo $this->lang->line('head_to_head_accumulated_statistics'); ?></h3>
<table>
    <thead>
        <tr>
            <td></td>
            <td>P</td>
            <td>W</td>
            <td>D</td>
            <td>L</td>
            <td>F</td>
            <td>A</td>
            <td>GD</td>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($accumulatedData as $venue => $data) { ?>
        <tr>
            <td><?php echo $this->lang->line("head_to_head_{$venue}"); ?></td>
            <td><?php echo $data->p; ?></td>
            <td><?php echo $data->w; ?></td>
            <td><?php echo $data->d; ?></td>
            <td><?php echo $data->l; ?></td>
            <td><?php echo $data->f; ?></td>
            <td><?php echo $data->a; ?></td>
            <td><?php echo $data->gd; ?></td>
        </tr>
    <?php
    } ?>
    </tbody>
</table>

<h3><?php echo $this->lang->line('head_to_head_matches'); ?></h3>
<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line("match_date"); ?></td>
            <td><?php echo $this->lang->line("match_competition"); ?></td>
            <td><?php echo $this->lang->line("match_venue"); ?></td>
            <td><?php echo $this->lang->line("match_score"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($matches) {
        foreach ($matches as $match) { ?>
            <tr>
                <td><?php echo Utility_helper::formattedDate($match->date, "jS M 'y"); ?></td>
                <td><?php echo Match_helper::fullCompetitionNameCombined($match); ?></td>
                <td><?php echo Match_helper::venue($match); ?></td>
                <td><a href="/match/view/id/<?php echo $match->id; ?>" title=""><?php echo Match_helper::score($match); ?></a></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo sprintf($this->lang->line('head_to_head_no_statistics'), Configuration::get('team_name'), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>
<h3><?php echo $this->lang->line('head_to_head_top_scorers'); ?></h3>
<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line("head_to_head_player"); ?></td>
            <td><?php echo $this->lang->line("head_to_head_goals"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($scorers) {
        foreach ($scorers as $scorer) { ?>
            <tr>
                <td><?php echo Player_helper::fullNameReverse($scorer->player_id); ?></td>
                <td><?php echo $scorer->goals; ?></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="2"><?php echo sprintf($this->lang->line("head_to_head_no_scorers"), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>

<h3><?php echo $this->lang->line('head_to_head_top_assisters'); ?></h3>
<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line("head_to_head_player"); ?></td>
            <td><?php echo $this->lang->line("head_to_head_assists"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($assisters) {
        foreach ($assisters as $assister) { ?>
            <tr>
                <td><?php echo Player_helper::fullNameReverse($assister->player_id); ?></td>
                <td><?php echo $assister->assists; ?></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="2"><?php echo sprintf($this->lang->line("head_to_head_no_assisters"), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>

<h3><?php echo $this->lang->line('head_to_head_worst_discipline'); ?></h3>
<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line("head_to_head_player"); ?></td>
            <td><?php echo $this->lang->line("head_to_head_reds"); ?></td>
            <td><?php echo $this->lang->line("head_to_head_yellows"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($offenders) {
        foreach ($offenders as $offender) { ?>
            <tr>
                <td><?php echo Player_helper::fullNameReverse($offender->player_id); ?></td>
                <td><?php echo $offender->reds; ?></td>
                <td><?php echo $offender->yellows; ?></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo sprintf($this->lang->line("head_to_head_no_worst_discipline"), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>

<h3><?php echo $this->lang->line('head_to_head_top_point_gainers'); ?></h3>
<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line("head_to_head_player"); ?></td>
            <td><?php echo $this->lang->line("head_to_head_points"); ?></td>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($pointsGainers) {
        foreach ($pointsGainers as $pointsGainer) { ?>
            <tr>
                <td><?php echo Player_helper::fullNameReverse($pointsGainer->player_id); ?></td>
                <td><?php echo $pointsGainer->points; ?></td>
            </tr>
        <?php
        }
    } else { ?>
            <tr>
                <td colspan="2"><?php echo sprintf($this->lang->line("head_to_head_no_top_point_gainers"), Opposition_helper::name($opposition)); ?></td>
            </tr>
    <?php
    } ?>
</table>
<?php
} ?>