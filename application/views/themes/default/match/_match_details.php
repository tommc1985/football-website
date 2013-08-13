<h3><?php echo $this->lang->line('match_details'); ?></h3>

<dl class="dl-horizontal">
    <dt><?php echo $this->lang->line('match_date'); ?></dt>
    <dd><time itemprop="startDate" datetime="<?php echo Utility_helper::formattedDate($match->date, "c"); ?>"><?php echo Utility_helper::longDateTime($match->date); ?></time></dd>
    <?php
    if (!$preview) { ?>
        <dt><?php echo $this->lang->line('match_score'); ?></dt>
        <dd><?php echo Match_helper::longScore($match); ?></dd>
    <?php
    } ?>
    <dt><?php echo $this->lang->line('match_venue'); ?></dt>
    <dd><span itemprop="name" itemscope itemtype="http://schema.org/SportsTeam"><?php echo Match_helper::longVenue($match) . ' ' . $this->lang->line('match_versus'); ?> <span itemprop="name"><?php echo Opposition_helper::name($match->opposition_id); ?></span></span></dd>
    <dt><?php echo $this->lang->line('match_competition'); ?></dt>
    <dd><?php echo Match_helper::fullCompetitionNameCombined($match); ?></dd>
    <dt><?php echo $this->lang->line('match_location'); ?></dt>
    <dd><span itemprop="location"><?php echo $match->location ? $match->location : $this->lang->line('global_unknown'); ?></span></dd>
    <dt><?php echo $this->lang->line('match_official'); ?></dt>
    <dd><?php echo $match->official_id == 0 ? $this->lang->line('global_unknown') : Official_helper::initialSurname($match->official_id); ?></dd>
    <?php
    if (Configuration::get('include_match_attendances') === true && !$preview) { ?>
        <dt><?php echo $this->lang->line('match_attendance'); ?></dt>
        <dd><?php echo is_null($match->attendance) ? $this->lang->line('global_unknown') : $match->attendance; ?></dd><?php
    } ?>
</dl>