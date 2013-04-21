<?php
echo $pagination;
if (count($competitionStages) > 0) { ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('competition_stage_name'); ?></td>
                <td><?php echo $this->lang->line('competition_stage_abbreviation'); ?></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($competitionStages as $competitionStage) { ?>
            <tr>
                <td><?php echo Competition_Stage_helper::name($competitionStage); ?></td>
                <td><?php echo Competition_Stage_helper::abbreviation($competitionStage); ?></td>
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