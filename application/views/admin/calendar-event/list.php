<?php
if (count($calendarEvents) > 0) { ?>
    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables width-100-percent">
        <thead>
            <tr>
                <td><?php echo $this->lang->line('calendar_event_name'); ?></td>
                <td><?php echo $this->lang->line('calendar_event_start'); ?></td>
                <td><?php echo $this->lang->line('calendar_event_end'); ?></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($calendarEvents as $calendarEvent) { ?>
            <tr>
                <td data-title="<?php echo $this->lang->line('calendar_event_name'); ?>" class="width-25-percent"><?php echo Calendar_Event_helper::name($calendarEvent); ?></td>
                <td data-title="<?php echo $this->lang->line('calendar_event_start'); ?>" class="width-25-percent text-align-center"><?php echo Calendar_Event_helper::start($calendarEvent); ?></td>
                <td data-title="<?php echo $this->lang->line('calendar_event_end'); ?>" class="width-25-percent text-align-center"><?php echo Calendar_Event_helper::end($calendarEvent); ?></td>
                <td class="actions width-15-percent text-align-center">
                    <div class="btn-group">
                        <a class="btn btn-mini" href="<?php echo site_url("admin/calendar-event/edit/id/{$calendarEvent->id}"); ?>"><?php echo $this->lang->line('calendar_event_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/calendar-event/delete/id/{$calendarEvent->id}"); ?>"><?php echo $this->lang->line('calendar_event_delete'); ?></a>
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
        <?php echo $this->lang->line('calendar_event_no_calendar_events'); ?>
    </div>
<?php
} ?>