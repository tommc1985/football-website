<?php
echo $pagination;
if (count($competitionStages) > 0) { ?>
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
    foreach ($competitionStages as $competitionStage) { ?>
            <tr>
                <td><?php echo $competitionStage->name; ?></td>
                <td><?php echo $competitionStage->abbreviation; ?></td>
                <td><a href="/admin/competition-stage/edit/id/<?php echo $competitionStage->id;?>">Edit</a></td>
                <td><a href="/admin/competition-stage/delete/id/<?php echo $competitionStage->id;?>">Delete</a></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
}
echo $pagination; ?>