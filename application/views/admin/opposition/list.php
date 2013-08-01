<?php
echo $pagination; ?>
    <table class="no-more-tables">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('opposition_name'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php
    if (count($oppositions) > 0) {
        foreach ($oppositions as $opposition) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('opposition_name'); ?>"><?php echo Opposition_helper::name($opposition); ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/opposition/edit/id/{$opposition->id}"); ?>"><?php echo $this->lang->line('opposition_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/opposition/delete/id/{$opposition->id}"); ?>"><?php echo $this->lang->line('opposition_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="2"><?php echo $this->lang->line('opposition_no_oppositions'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>