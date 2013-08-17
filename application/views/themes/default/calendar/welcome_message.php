<div class="row-fluid">
    <div class="span12">

        <h2><?php echo $this->lang->line('calendar_title'); ?></h2>

        <p><?php echo $this->lang->line('calendar_text'); ?></p>

        <h3><?php echo $this->lang->line('calendar_fixtures_and_results_title'); ?></h3>

        <p><?php echo $this->lang->line('calendar_link'); ?> <a class="calendar-link" href="<?php echo site_url('calendar/fixtures-and-results'); ?>"><?php echo site_url('calendar/fixtures-and-results');?></a></p>

        <p><?php echo sprintf($this->lang->line('calendar_fixtures_and_results_explanation'), Configuration::get('team_name')); ?></p>

        <h3><?php echo $this->lang->line('calendar_player_birthdays_title'); ?></h3>

        <p><?php echo $this->lang->line('calendar_link'); ?> <a class="calendar-link" href="<?php echo site_url('calendar/player-birthdays'); ?>"><?php echo site_url('calendar/player-birthdays');?></a></p>

        <p><?php echo sprintf($this->lang->line('calendar_player_birthdays_explanation'), Configuration::get('team_name')); ?></p>

        <h3><?php echo $this->lang->line('calendar_events_title'); ?></h3>

        <p><?php echo $this->lang->line('calendar_link'); ?> <a class="calendar-link" href="<?php echo site_url('calendar/events'); ?>"><?php echo site_url('calendar/events');?></a></p>

        <p><?php echo sprintf($this->lang->line('calendar_events_explanation'), Configuration::get('team_name')); ?></p>

        <h3><?php echo $this->lang->line('calendar_combined_title'); ?></h3>

        <p><?php echo $this->lang->line('calendar_link'); ?> <a class="calendar-link" href="<?php echo site_url('calendar/combined'); ?>"><?php echo site_url('calendar/combined');?></a></p>

        <p><?php echo sprintf($this->lang->line('calendar_combined_explanation'), Configuration::get('team_name')); ?></p>
    </div>
</div>