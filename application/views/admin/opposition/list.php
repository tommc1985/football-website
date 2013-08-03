<?php
if (count($oppositions) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('opposition_name'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($oppositions as $opposition) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('opposition_name'); ?>" class="width-85-percent"><?php echo Opposition_helper::name($opposition); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-mini" href="<?php echo site_url("admin/opposition/edit/id/{$opposition->id}"); ?>"><?php echo $this->lang->line('opposition_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/opposition/delete/id/{$opposition->id}"); ?>"><?php echo $this->lang->line('opposition_delete'); ?></a>
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
        <?php echo $this->lang->line('opposition_no_oppositions'); ?>
    </div>
<?php
} ?>