<div class="row-fluid">
    <div class="span12">

        <h2><?php echo $this->lang->line('league_title'); ?> - <?php echo League_helper::name($id); ?></h2>

        <div class="row-fluid">
            <div class="span10 offset1">
                <h3><?php echo $this->lang->line('league_league_table'); ?></h3>
                <?php
                if ($standings) { ?>
                <table class="no-more-tables width-100-percent">
                    <thead>
                        <tr>
                            <td class="width-60-percent">&nbsp;</td>
                            <td class="width-5-percent"><?php echo $this->lang->line("league_p"); ?></td>
                            <td class="width-5-percent"><?php echo $this->lang->line("league_w"); ?></td>
                            <td class="width-5-percent"><?php echo $this->lang->line("league_d"); ?></td>
                            <td class="width-5-percent"><?php echo $this->lang->line("league_l"); ?></td>
                            <td class="width-5-percent"><?php echo $this->lang->line("league_f"); ?></td>
                            <td class="width-5-percent"><?php echo $this->lang->line("league_a"); ?></td>
                            <td class="width-5-percent"><?php echo $this->lang->line("league_gd"); ?></td>
                            <td class="width-5-percent"><?php echo $this->lang->line("league_pts"); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($standings as $standing) { ?>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('league_team'); ?>"><?php echo Opposition_helper::name($standing->opposition_id); ?></td>
                            <td data-title="<?php echo $this->lang->line('league_played'); ?>"><?php echo $standing->played; ?></td>
                            <td data-title="<?php echo $this->lang->line('league_won'); ?>"><?php echo $standing->won; ?></td>
                            <td data-title="<?php echo $this->lang->line('league_drawn'); ?>"><?php echo $standing->drawn; ?></td>
                            <td data-title="<?php echo $this->lang->line('league_lost'); ?>"><?php echo $standing->lost; ?></td>
                            <td data-title="<?php echo $this->lang->line('league_for'); ?>"><?php echo $standing->gf; ?></td>
                            <td data-title="<?php echo $this->lang->line('league_against'); ?>"><?php echo $standing->ga; ?></td>
                            <td data-title="<?php echo $this->lang->line('league_goal_difference'); ?>"><?php echo $standing->gd; ?></td>
                            <td data-title="<?php echo $this->lang->line('league_points'); ?>"><?php echo $standing->points; ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
                <?php
                } else { ?>
                <p><?php echo $this->lang->line("league_no_data"); ?></p>
                <?php
                } ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <h3><?php echo sprintf($this->lang->line("league_form_last_x_matches"), $formMatchCount); ?></h3>
                <?php
                if ($formTeams) { ?>
                <table class="no-more-tables width-100-percent">
                    <tbody>
                    <?php
                    foreach ($formTeams as $opposition_id => $formTeam) { ?>
                        <tr>
                            <td data-title="<?php echo $this->lang->line('league_team'); ?>"><?php echo Opposition_helper::name($standings[$opposition_id]->opposition_id); ?></td>
                            <td data-title="<?php echo $this->lang->line('league_form'); ?>"><?php echo League_helper::formattedForm($standings[$opposition_id]->form, Configuration::get('form_match_count')); ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="width-70-percent"></td>
                            <td class="width-30-percent"></td>
                        </tr>
                    </tfoot>
                </table>
                <?php
                } else { ?>
                <p><?php echo $this->lang->line("league_no_data"); ?></p>
                <?php
                } ?>
            </div>
            <div class="span6">
                <h3><?php echo $this->lang->line("league_fixtures_and_results"); ?></h3>
            </div>
        </div>
    </div>
</div>