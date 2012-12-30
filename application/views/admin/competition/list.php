<?php
echo $pagination;
if (count($competitions) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td>Short Name</td>
                <td>Type</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($competitions as $competition) { ?>
            <tr>
                <td><?php echo $competition->short_name; ?></td>
                <td><?php echo $competition->type; ?></td>
                <td><a href="/admin/competition/edit/id/<?php echo $competition->id;?>">Edit</a></td>
                <td><a href="/admin/competition/delete/id/<?php echo $competition->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>