<?php
echo $pagination; ?>
    <table>
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
                <td><?php echo Opposition_helper::name($opposition); ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/opposition/edit/id/{$opposition->id}"); ?>"><?php echo $this->lang->line('opposition_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/opposition/delete/id/{$opposition->id}"); ?>"><?php echo $this->lang->line('opposition_delete'); ?></a>
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