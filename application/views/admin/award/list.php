<?php
echo $pagination; ?>
    <table class="no-more-tables">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('award_long_name'); ?></td>
                <td><?php echo $this->lang->line('award_short_name'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($awards) > 0) {
        foreach ($awards as $award) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('award_long_name'); ?>"><?php echo Award_helper::longName($award->id); ?></td>
                <td data-title="<?php echo $this->lang->line('award_short_name'); ?>"><?php echo Award_helper::shortName($award->id); ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/award/edit/id/{$award->id}"); ?>"><?php echo $this->lang->line('award_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/award/delete/id/{$award->id}"); ?>"><?php echo $this->lang->line('award_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="3"><?php echo $this->lang->line('award_no_awards'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>