<div class="row-fluid">
    <div class="span12">
        <h2><?php echo sprintf($this->lang->line('match_fixtures_and_results_for_season'), Utility_helper::formattedSeason($season)); ?></h2>
        <?php
        if ($matches) { ?>

        <table class="no-more-tables table table-striped table-condensed">
            <thead>
                <tr>
                    <td class="width-15-perecent"><?php echo $this->lang->line('match_date'); ?></td>
                    <td class="width-25-perecent"><?php echo $this->lang->line('match_opposition'); ?></td>
                    <td class="width-30-perecent"><?php echo $this->lang->line('match_competition'); ?></td>
                    <td class="width-10-perecent text-align-center"><?php echo $this->lang->line('match_venue'); ?></td><?php
                    if (Configuration::get('include_match_attendances') === true) { ?>
                    <td class="width-10-perecent text-align-center"><?php echo $this->lang->line('match_attendance'); ?></td><?php
                    } ?>
                    <td class="width-20-perecent text-align-center"><?php echo $this->lang->line('match_score'); ?></td>
                </tr>
            </thead>
            <tbody>
        <?php
            foreach ($matches as $match) { ?>
                <tr itemscope itemtype="http://schema.org/SportsEvent">
                    <td data-title="<?php echo $this->lang->line('match_date'); ?>"><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($match->date, "c"); ?>"><?php echo $match->date ? Utility_helper::formattedDate($match->date, "jS M 'y") : $this->lang->line('match_t_b_c'); ?></time></td>
                    <td itemprop="name" itemscope itemtype="http://schema.org/SportsTeam" data-title="<?php echo $this->lang->line('match_opposition'); ?>"><span itemprop="name" itemprop="legalName"><?php echo Opposition_helper::name($match->opposition_id); ?></span></td>
                    <td data-title="<?php echo $this->lang->line('match_competition'); ?>"><?php echo Match_helper::fullCompetitionNameCombined($match); ?></td>
                    <td class="text-align-center" data-title="<?php echo $this->lang->line('match_venue'); ?>"><?php echo Match_helper::longVenue($match); ?></td><?php
                    if (Configuration::get('include_match_attendances') === true) { ?>
                    <td class="text-align-center" data-title="<?php echo $this->lang->line('match_attendance'); ?>"><?php echo Match_helper::attendance($match) ; ?></td><?php
                    } ?>
                    <td class="text-align-center" data-title="<?php echo $this->lang->line('match_score'); ?>"><a itemprop="url" href="<?php echo site_url('/match/view/id/' . $match->id); ?>"><?php echo Match_helper::score($match); ?></a></td>
                </tr>
        <?php
            } ?>
            </tbody>
        </table>
        <?php
        } else { ?>
                <p><?php echo sprintf($this->lang->line('match_no_matches_found'), Utility_helper::formattedSeason($season)); ?></p>
        <?php
        } ?>
    </div>
</div>