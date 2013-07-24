<?php
echo $pagination; ?>
    <table>
        <thead>
            <tr>
                <td><?php echo $this->lang->line('calendar_event_name'); ?></td>
                <td><?php echo $this->lang->line('calendar_event_start'); ?></td>
                <td><?php echo $this->lang->line('calendar_event_end'); ?></td>
                <td></td>
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
                <td><a href="/admin/calendar-event/edit/id/<?php echo $calendarEvent->id;?>"><?php echo $this->lang->line('calendar_event_edit'); ?></a></td>
                <td><a href="/admin/calendar-event/delete/id/<?php echo $calendarEvent->id;?>"><?php echo $this->lang->line('calendar_event_delete'); ?></a></td>
            </tr>
    <?php
        }
    } else { ?>
            <tr>
                <td colspan="5"><?php echo $this->lang->line('calendar_event_no_calendar_events'); ?></td>
            </tr>
    <?php
    } ?>
        </tbody>
    </table>
<?php
echo $pagination; ?>