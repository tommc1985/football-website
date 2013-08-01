<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('competition_stage_name'); ?></td>
                <td><?php echo $this->lang->line('competition_stage_abbreviation'); ?></td>
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
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/competition-stage/edit/id/{$competitionStage->id}"); ?>"><?php echo $this->lang->line('competition_stage_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/competition-stage/delete/id/{$competitionStage->id}"); ?>"><?php echo $this->lang->line('competition_stage_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo $this->lang->line('competition_stage_no_competition_stages'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>