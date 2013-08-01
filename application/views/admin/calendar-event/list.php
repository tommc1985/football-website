<?php
echo $pagination; ?>
    <table>
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
    if (count($calendarEvents) > 0) {
        foreach ($calendarEvents as $calendarEvent) { ?>
            <tr>
                <td><?php echo Calendar_Event_helper::name($calendarEvent); ?></td>
                <td><?php echo Calendar_Event_helper::start($calendarEvent); ?></td>
                <td><?php echo Calendar_Event_helper::end($calendarEvent); ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary btn-small" href="<?php echo site_url("admin/calendar-event/edit/id/{$calendarEvent->id}"); ?>"><?php echo $this->lang->line('calendar_event_edit'); ?></a>
                        <a class="btn btn-danger btn-small" href="<?php echo site_url("admin/calendar-event/delete/id/{$calendarEvent->id}"); ?>"><?php echo $this->lang->line('calendar_event_delete'); ?></a>
                    </div>
                </td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="4"><?php echo $this->lang->line('calendar_event_no_calendar_events'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>