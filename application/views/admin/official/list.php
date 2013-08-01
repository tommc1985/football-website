<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('official_name'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($officials) > 0) {
        foreach ($officials as $official) { ?>
            <tr>
                <td><?php echo Official_helper::fullNameReverse($official); ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/official/edit/id/{$official->id}"); ?>"><?php echo $this->lang->line('official_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/official/delete/id/{$official->id}"); ?>"><?php echo $this->lang->line('official_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="2"><?php echo $this->lang->line('official_no_officials'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>