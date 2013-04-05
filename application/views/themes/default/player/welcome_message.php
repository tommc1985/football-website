<h2><?php echo $this->lang->line('player_squad_list'); ?></h2>

<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line('player_player'); ?></td>
            <td><?php echo $this->lang->line('player_d_o_b'); ?></td>
            <td><?php echo $this->lang->line('player_apps'); ?></td>
            <td><?php echo $this->lang->line('player_goals'); ?></td>
            <td><?php echo $this->lang->line('player_assists'); ?></td>
            <td><?php echo $this->lang->line('player_motms'); ?></td>
            <td><?php echo $this->lang->line('player_yellows'); ?></td>
            <td><?php echo $this->lang->line('player_reds'); ?></td>
            <td><?php echo $this->lang->line('player_rating'); ?></td>
        </tr>
    </thead>
<?php
if ($players) { ?>
    <tbody>
<?php
    foreach ($players as $player) { ?>
        <tr>
            <td><?php echo anchor('/player/view/id/' . $player->id, Player_helper::fullNameReverse($player)); ?></td>
            <td><?php echo Utility_helper::formattedDate($player->dob, "jS M Y"); ?></td>
            <td><?php echo $player->appearances; ?> (<?php echo $player->substitute_appearances; ?>)</td>
            <td><?php echo $player->goals; ?></td>
            <td><?php echo $player->assists; ?></td>
            <td><?php echo $player->motms; ?></td>
            <td><?php echo $player->yellows; ?></td>
            <td><?php echo $player->reds; ?></td>
            <td><?php echo Player_helper::rating($player->average_rating); ?></td>
        </tr>
<?php
    }
} else { ?>
        <tr>
            <td colspan="9"><?php echo sprintf($this->lang->line('player_no_players_found'), Utility_helper::formattedSeason($season)); ?></td>
        </td>
<?php
} ?>
    </tbody>
</table>