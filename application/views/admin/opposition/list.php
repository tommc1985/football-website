<?php
echo $pagination;
if (count($oppositions) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td>Name</td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($oppositions as $opposition) { ?>
            <tr>
                <td><?php echo $opposition->name; ?></td>
                <td><a href="/admin/opposition/edit/id/<?php echo $opposition->id;?>">Edit</a></td>
                <td><a href="/admin/opposition/delete/id/<?php echo $opposition->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>