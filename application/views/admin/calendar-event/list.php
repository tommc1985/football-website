    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>

    <table class="no-more-tables">
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
                <td data-title="<?php echo $this->lang->line('calendar_event_name'); ?>"><?php echo Calendar_Event_helper::name($calendarEvent); ?></td>
                <td data-title="<?php echo $this->lang->line('calendar_event_start'); ?>"><?php echo Calendar_Event_helper::start($calendarEvent); ?></td>
                <td data-title="<?php echo $this->lang->line('calendar_event_end'); ?>"><?php echo Calendar_Event_helper::end($calendarEvent); ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-mini" href="<?php echo site_url("admin/calendar-event/edit/id/{$calendarEvent->id}"); ?>"><?php echo $this->lang->line('calendar_event_edit'); ?></a>
                        <a class="btn btn-danger btn-mini" href="<?php echo site_url("admin/calendar-event/delete/id/{$calendarEvent->id}"); ?>"><?php echo $this->lang->line('calendar_event_delete'); ?></a>
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

    <div class="pagination">
    <?php
    echo $pagination; ?>
    </div>