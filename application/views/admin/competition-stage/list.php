<?php
if (count($competitionStages) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('competition_stage_name'); ?></td>
                <td><?php echo $this->lang->line('competition_stage_abbreviation'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($competitionStages as $competitionStage) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('competition_stage_name'); ?>" class="width-65-percent"><?php echo Competition_Stage_helper::name($competitionStage); ?></td>
                <td data-title="<?php echo $this->lang->line('competition_stage_abbreviation'); ?>" class="width-20-percent text-align-center"><?php echo Competition_Stage_helper::abbreviation($competitionStage); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/competition-stage/edit/id/{$competitionStage->id}"); ?>"><?php echo $this->lang->line('competition_stage_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/competition-stage/delete/id/{$competitionStage->id}"); ?>"><?php echo $this->lang->line('competition_stage_delete'); ?></a>
                    </div>
                </td>
            </tr>
        <?php
        } ?>
        </tbody>
    </table>

    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

<?php
} else { ?>
    <div class="alert alert-error">
        <?php echo $this->lang->line('competition_stage_no_competition_stages'); ?>
    </div>
<?php
} ?>