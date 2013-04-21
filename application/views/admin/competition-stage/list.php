<?php
echo $pagination; ?>
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
    if (count($competitionStages) > 0) {
        foreach ($competitionStages as $competitionStage) { ?>
            <tr>
                <td><?php echo Competition_Stage_helper::name($competitionStage); ?></td>
                <td><?php echo Competition_Stage_helper::abbreviation($competitionStage); ?></td>
                <td><a href="/admin/competition-stage/edit/id/<?php echo $competitionStage->id;?>">Edit</a></td>
                <td><a href="/admin/competition-stage/delete/id/<?php echo $competitionStage->id;?>">Delete</a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo $this->lang->line('competition_stage_no_competition_stages'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>