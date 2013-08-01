<?php
if (count($officials) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('official_first_name'); ?></td>
                <td><?php echo $this->lang->line('official_surname'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($officials as $official) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('official_first_name'); ?>" class="width-45-percent"><?php echo $official->first_name; ?></td>
                <td data-title="<?php echo $this->lang->line('official_surname'); ?>" class="width-40-percent"><?php echo $official->surname; ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/official/edit/id/{$official->id}"); ?>"><?php echo $this->lang->line('official_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/official/delete/id/{$official->id}"); ?>"><?php echo $this->lang->line('official_delete'); ?></a>
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
        <?php echo $this->lang->line('official_no_officials'); ?>
    </div>
<?php
} ?>