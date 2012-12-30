<?php
echo $pagination;
if (count($players) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td>First Name</td>
                <td>Surname</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($players as $player) { ?>
            <tr>
                <td><?php echo $player->first_name; ?></td>
                <td><?php echo $player->surname; ?></td>
                <td><a href="/admin/player/edit/id/<?php echo $player->id;?>">Edit</a></td>
                <td><a href="/admin/player/delete/id/<?php echo $player->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>