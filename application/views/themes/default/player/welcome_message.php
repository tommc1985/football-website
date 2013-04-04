<h2>Squad List</h2>


<table>
    <thead>
        <tr>
            <td>Player</td>
            <td>D.o.B.</td>
            <td>Apps</td>
            <td>Goals</td>
            <td>Assists</td>
            <td>MotM</td>
            <td>Yellows</td>
            <td>Reds</td>
            <td>Rating</td>
        </tr>
    </thead>
<?php
if ($players) { ?>
    <tbody>
<?php
    foreach ($players as $player) { ?>
        <tr>
            <td><?php echo Player_helper::fullNameReverse($player); ?></td>
            <td><?php echo Utility_helper::shortDate($player->dob); ?></td>
            <td><?php echo $player->appearances; ?> (<?php echo $player->substitute_appearances; ?>)</td>
            <td><?php echo $player->goals; ?></td>
            <td><?php echo $player->assists; ?></td>
            <td><?php echo $player->motms; ?></td>
            <td><?php echo $player->yellows; ?></td>
            <td><?php echo $player->reds; ?></td>
            <td><?php echo number_format($player->average_rating, 2); ?></td>
        </tr>
<?php
    }
} else { ?>
        <tr>
            <td colspan="9">No players found for the season <?php echo $season; ?></td>
        </td>
<?php
} ?>
    </tbody>
</table>