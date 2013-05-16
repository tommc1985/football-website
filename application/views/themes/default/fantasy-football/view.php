<h2><?php echo $this->lang->line('fantasy_football_title'); ?> - <?php echo Utility_helper::formattedSeason($season); ?><?php echo ($type != 'overall' ? ' (' . Competition_helper::type($type) . ' ' . $this->lang->line("club_statistics_matches") . ')' : ''); ?></h2>

<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line('fantasy_football_player'); ?></td>
            <td><?php echo $this->lang->line('fantasy_football_appearances'); ?></td>
            <td><?php echo $this->lang->line('fantasy_football_points'); ?></td>
            <td><?php echo $this->lang->line('fantasy_football_points_per_game'); ?></td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($fantasyFootballData as $player) { ?>
        <tr>
            <td><?php echo Player_helper::fullNameReverse($player->player_id); ?></td>
            <td><?php echo $player->appearances; ?></td>
            <td><?php echo $player->total_points; ?></td>
            <td><?php echo $player->points_per_game; ?></td>
        </tr>
        <?php
        } ?>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <td><?php echo $this->lang->line('fantasy_football_player'); ?></td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($bestLineup as $position => $playerId) { ?>
        <tr>
            <td><?php echo Player_helper::fullNameReverse($playerId); ?></td>
            <td><?php echo $position; ?></td>
        </tr>
        <?php
        } ?>
    </tbody>
</table>