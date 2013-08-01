    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables">
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
    if (count($matches) > 0) {
        foreach ($matches as $match) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('match_date'); ?>"><?php echo Utility_helper::shortDate($match->date); ?></td>
                <td data-title="<?php echo $this->lang->line('match_opposition'); ?>"><?php echo Opposition_helper::name($match->opposition_id); ?> (<?php echo Match_helper::venue($match); ?>)</td>
                <td data-title="<?php echo $this->lang->line('match_competition'); ?>"><?php echo Competition_helper::shortName($match->competition_id); ?></td>
                <td data-title="<?php echo $this->lang->line('match_score'); ?>"><?php echo Match_helper::score($match); ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/match/edit/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_edit'); ?></a>
                        <?php
                        if (!is_null($match->h)) { ?>
                            <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/appearance/edit/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_appearances'); ?></a>
                            <?php
                            if ($match->h > 0) { ?>
                                <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/goal/edit/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_goals'); ?></a>
                            <?php
                            } else { ?>
                                <a class="btn btn-mini" href="#"><s><?php echo $this->lang->line('match_goals'); ?></s></a>
                            <?php
                            } ?>
                            <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/card/edit/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_cards'); ?></a>
                        <?php
                        } else { ?>
                            <a class="btn btn-mini" href="#"><s><?php echo $this->lang->line('match_appearances'); ?></s></a>
                            <a class="btn btn-mini" href="#"><s><?php echo $this->lang->line('match_goals'); ?></s></a>
                            <a class="btn btn-mini" href="#"><s><?php echo $this->lang->line('match_cards'); ?></s></a>
                        <?php
                        } ?>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/match/delete/id/{$match->id}"); ?>"><?php echo $this->lang->line('match_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="5"><?php echo $this->lang->line('match_no_matches'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>

    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>