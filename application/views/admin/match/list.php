<?php
if (count($matches) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('match_date'); ?></td>
                <td><?php echo $this->lang->line('match_opposition'); ?></td>
                <td><?php echo $this->lang->line('match_competition'); ?></td>
                <td><?php echo $this->lang->line('match_score'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($matches as $match) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('match_date'); ?>" class="width-15-percent text-align-center"><?php echo Utility_helper::shortDate($match->date); ?></td>
                <td data-title="<?php echo $this->lang->line('match_opposition'); ?>" class="width-25-percent"><?php echo Opposition_helper::name($match->opposition_id); ?> (<?php echo Match_helper::venue($match); ?>)</td>
                <td data-title="<?php echo $this->lang->line('match_competition'); ?>" class="width-15-percent"><?php echo Competition_helper::shortName($match->competition_id); ?></td>
                <td data-title="<?php echo $this->lang->line('match_score'); ?>" class="width-10-percent text-align-center"><?php echo Match_helper::score($match); ?></td>
                <td class="actions width-30-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-mini" href="<?php echo site_url("admin/match/edit/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_edit'); ?></a>
                        <?php
                        if (!is_null($match->h)) { ?>
                            <a class="btn btn-mini" href="<?php echo site_url("admin/appearance/edit/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_appearances'); ?></a>
                            <?php
                            if ($match->h > 0) { ?>
                                <a class="btn btn-mini" href="<?php echo site_url("admin/goal/edit/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_goals'); ?></a>
                            <?php
                            } else { ?>
                                <a class="btn btn-mini disabled" href="#"><?php echo $this->lang->line('match_goals'); ?></a>
                            <?php
                            } ?>
                            <a class="btn btn-mini" href="<?php echo site_url("admin/card/edit/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_cards'); ?></a>
                        <?php
                        } else { ?>
                            <a class="btn btn-mini disabled" href="#"><?php echo $this->lang->line('match_appearances'); ?></a>
                            <a class="btn btn-mini disabled" href="#"><?php echo $this->lang->line('match_goals'); ?></a>
                            <a class="btn btn-mini disabled" href="#"><?php echo $this->lang->line('match_cards'); ?></a>
                        <?php
                        } ?>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/match/delete/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_delete'); ?></a>
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
        <?php echo $this->lang->line('match_no_matches'); ?>
    </div>
<?php
} ?>