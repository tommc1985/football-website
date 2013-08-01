<?php
if (count($awards) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('award_long_name'); ?></td>
                <td><?php echo $this->lang->line('award_short_name'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($awards as $award) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('award_long_name'); ?>" class="width-50-percent"><?php echo Award_helper::longName($award->id); ?></td>
                <td data-title="<?php echo $this->lang->line('award_short_name'); ?>" class="width-35-percent"><?php echo Award_helper::shortName($award->id); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/award/edit/id/{$award->id}"); ?>"><?php echo $this->lang->line('award_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/award/delete/id/{$award->id}"); ?>"><?php echo $this->lang->line('award_delete'); ?></a>
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
        <?php echo $this->lang->line('award_no_awards'); ?>
    </div>
<?php
} ?>