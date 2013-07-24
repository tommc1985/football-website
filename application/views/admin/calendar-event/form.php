<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id'),
);

$name = array(
    'name'  => 'name',
    'id'    => 'name',
    'value' => set_value('name'),
    'maxlength' => $this->config->item('name_max_length', 'calendar_event'),
);

$description = array(
    'name'  => 'description',
    'id'    => 'description',
    'value' => set_value('description'),
);

$startDate = array(
    'name'  => 'start_date',
    'id'    => 'start-date',
);

$startTime = array(
    'name'  => 'start_time',
    'id'    => 'start-time',
);

$endDate = array(
    'name'  => 'end_date',
    'id'    => 'end-date',
);

$endTime = array(
    'name'  => 'end_time',
    'id'    => 'end-time',
);

$allDay = array(
    'name'  => "all_day",
    'id'    => "all-day",
    'checked' => set_checkbox('all_day', '1', isset($calendarEvent->all_day) && $calendarEvent->all_day == 1 ? true : false),
    'value' => 1,
);

$location = array(
    'name'  => 'location',
    'id'    => 'location',
    'value' => set_value('location'),
);

echo form_open($this->uri->uri_string()); ?>
<table>
    <?php echo form_hidden('id', set_value('id', isset($calendarEvent->id) ? $calendarEvent->id : '')); ?>
    <tr>
        <td><?php echo form_label($this->lang->line('calendar_event_name'), $name['name']); ?></td>
        <td><?php echo form_input($name['name'], set_value($name['name'], isset($calendarEvent->name) ? $calendarEvent->name : '')); ?></td>
        <td class="error"><?php echo form_error($name['name']); ?><?php echo isset($errors[$name['name']]) ? $errors[$name['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('calendar_event_description'), $description['name']); ?></td>
        <td><?php echo form_textarea($description['name'], set_value($description['name'], isset($calendarEvent->description) ? $calendarEvent->description : '')); ?></td>
        <td class="error"><?php echo form_error($description['name']); ?><?php echo isset($errors[$description['name']]) ? $errors[$description['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('calendar_event_start_date'), $startDate['name']); ?></td>
        <td><?php echo form_date($startDate['name'], set_value($startDate['name'], isset($calendarEvent->start_datetime) ? substr($calendarEvent->start_datetime, 0, 10) : '')); ?></td>
        <td class="error"><?php echo form_error($startDate['name']); ?><?php echo isset($errors[$startDate['name']]) ? $errors[$startDate['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('calendar_event_start_time'), $startTime['name']); ?></td>
        <td><?php echo form_time($startTime['name'], set_value($startTime['name'], isset($calendarEvent->start_datetime) ? substr($calendarEvent->start_datetime, 11, 5) : '')); ?></td>
        <td class="error"><?php echo form_error($startTime['name']); ?><?php echo isset($errors[$startTime['name']]) ? $errors[$startTime['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('calendar_event_end_date'), $endDate['name']); ?></td>
        <td><?php echo form_date($endDate['name'], set_value($endDate['name'], isset($calendarEvent->end_datetime) ? substr($calendarEvent->end_datetime, 0, 10) : '')); ?></td>
        <td class="error"><?php echo form_error($endDate['name']); ?><?php echo isset($errors[$endDate['name']]) ? $errors[$endDate['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('calendar_event_end_time'), $endTime['name']); ?></td>
        <td><?php echo form_time($endTime['name'], set_value($endTime['name'], isset($calendarEvent->end_datetime) ? substr($calendarEvent->end_datetime, 11, 5) : '')); ?></td>
        <td class="error"><?php echo form_error($endTime['name']); ?><?php echo isset($errors[$endTime['name']]) ? $errors[$endTime['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('calendar_event_all_day'), $allDay['name']); ?></td>
        <td><?php echo form_checkbox($allDay); ?></td>
        <td class="error"><?php echo form_error($allDay['name']); ?><?php echo isset($errors[$allDay['name']]) ? $errors[$allDay['name']] : ''; ?></td>
    </tr>
    <tr>
        <td><?php echo form_label($this->lang->line('calendar_event_location'), $location['name']); ?></td>
        <td><?php echo form_input($location['name'], set_value($location['name'], isset($calendarEvent->location) ? $calendarEvent->location : '')); ?></td>
        <td class="error"><?php echo form_error($location['name']); ?><?php echo isset($errors[$location['name']]) ? $errors[$location['name']] : ''; ?></td>
    </tr>
</table>
<?php echo form_submit('submit', $submitButtonText); ?>
<?php echo form_close(); ?>