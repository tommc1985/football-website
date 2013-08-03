<?php
if (count($competitions) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('competition_short_name'); ?></td>
                <td><?php echo $this->lang->line('competition_type'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($competitions as $competition) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('competition_short_name'); ?>" class="width-60-percent"><?php echo Competition_helper::shortName($competition); ?></td>
                <td data-title="<?php echo $this->lang->line('competition_type'); ?>" class="width-25-percent text-align-center"><?php echo Competition_helper::type($competition); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-mini" href="<?php echo site_url("admin/competition/edit/id/{$competition->id}"); ?>"><?php echo $this->lang->line('competition_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/competition/delete/id/{$competition->id}"); ?>"><?php echo $this->lang->line('competition_delete'); ?></a>
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
        <?php echo $this->lang->line('competition_no_competitions'); ?>
    </div>
<?php
} ?>