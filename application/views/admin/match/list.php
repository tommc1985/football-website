<?php
echo $pagination;
if (count($matches) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td>Date</td>
                <td>Opposition</td>
                <td>Compeititon</td>
                <td>Score</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($matches as $match) { ?>
            <tr>
                <td><?php echo $match->date; ?></td>
                <td><?php echo $match->opposition; ?></td>
                <td><?php echo $match->competition; ?></td>
                <td><?php echo $match->h; ?> <?php echo $match->a; ?></td>
                <td><a href="/admin/match/edit/id/<?php echo $match->id;?>">Edit</a></td>
                <td><a href="/admin/match/delete/id/<?php echo $match->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>