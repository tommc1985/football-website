<?php
echo $pagination;
if (count($officials) > 0) { ?>
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
    foreach ($officials as $official) { ?>
            <tr>
                <td><?php echo $official->first_name; ?></td>
                <td><?php echo $official->surname; ?></td>
                <td><a href="/admin/official/edit/id/<?php echo $official->id;?>">Edit</a></td>
                <td><a href="/admin/official/delete/id/<?php echo $official->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>