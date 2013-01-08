<?php
echo $pagination;
if (count($playerRegistrations) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td>Player ID</td>
                <td>Season</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($playerRegistrations as $playerRegistration) { ?>
            <tr>
                <td><?php echo $playerRegistration->player_id; ?></td>
                <td><?php echo $playerRegistration->season; ?></td>
                <td><a href="/admin/player-registration/edit/id/<?php echo $playerRegistration->id;?>">Edit</a></td>
                <td><a href="/admin/player-registration/delete/id/<?php echo $playerRegistration->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>