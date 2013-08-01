    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables">
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
                <td data-title="<?php echo $this->lang->line('official_name'); ?>"><?php echo Official_helper::fullNameReverse($official); ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/official/edit/id/{$official->id}"); ?>"><?php echo $this->lang->line('official_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/official/delete/id/{$official->id}"); ?>"><?php echo $this->lang->line('official_delete'); ?></a>
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

    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>