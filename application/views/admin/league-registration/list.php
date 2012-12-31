<?php
echo $pagination;
if (count($leagueRegistrations) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td>League ID</td>
                <td>Team</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($leagueRegistrations as $leagueRegistration) { ?>
            <tr>
                <td><?php echo $leagueRegistration->league_id; ?></td>
                <td><?php echo $leagueRegistration->opposition_id; ?></td>
                <td><a href="/admin/league-registration/edit/id/<?php echo $leagueRegistration->id;?>">Edit</a></td>
                <td><a href="/admin/league-registration/delete/id/<?php echo $leagueRegistration->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>