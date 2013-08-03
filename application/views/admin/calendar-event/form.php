<?php
$id = array(
    'name'  => 'id',
    'id'    => 'id',
    'value' => set_value('id', isset($calendarEvent->id) ? $calendarEvent->id : ''),
);

$name = array(
    'name'        => 'name',
    'id'          => 'name',
    'value'       => set_value('name', isset($calendarEvent->name) ? $calendarEvent->name : ''),
    'maxlength'   => $this->config->item('name_max_length', 'calendar_event'),
    'placeholder' => $this->lang->line('calendar_event_name'),
    'class'       => 'input-xlarge',
);

$description = array(
    'name'  => 'description',
    'id'    => 'description',
    'value' => set_value('description', isset($calendarEvent->description) ? $calendarEvent->description : ''),
    'maxlength'   => $this->config->item('name_max_length', 'calendar_event'),
    'placeholder' => $this->lang->line('calendar_event_description'),
    'class'       => 'input-xlarge',
);

$startDate = array(
    'name'  => 'start_date',
    'id'    => 'start-date',
    'value' => set_value('start_date', isset($calendarEvent->start_datetime) ? substr($calendarEvent->start_datetime, 0, 10) : ''),
    'class' => 'input-medium',
);

$startTime = array(
    'name'  => 'start_time',
    'id'    => 'start-time',
    'value' => set_value('start_time', isset($calendarEvent->start_datetime) ? substr($calendarEvent->start_datetime, 11, 5) : ''),
    'class' => 'input-small',
);

$endDate = array(
    'name'  => 'end_date',
    'id'    => 'end-date',
    'value' => set_value('end_date', isset($calendarEvent->end_datetime) ? substr($calendarEvent->end_datetime, 0, 10) : ''),
    'class' => 'input-medium',
);

$endTime = array(
    'name'  => 'end_time',
    'id'    => 'end-time',
    'value' => set_value('end_time', isset($calendarEvent->end_datetime) ? substr($calendarEvent->end_datetime, 11, 5) : ''),
    'class' => 'input-small',
);

$allDay = array(
    'name'    => "all_day",
    'id'      => "all-day",
    'checked' => set_checkbox('all_day', '1', isset($calendarEvent->all_day) && $calendarEvent->all_day == 1 ? true : false),
    'value'   => 1,
);

$location = array(
    'name'  => 'location',
    'id'    => 'location',
    'value' => set_value('location', isset($calendarEvent->location) ? $calendarEvent->location : ''),
    'class' => 'input-xlarge',
);

$submit = array(
    'name'  => 'submit',
    'class' => 'btn',
    'value' => $submitButtonText,
); ?>

<?php
echo form_open($this->uri->uri_string()); ?>
    <?php echo form_hidden($id['name'], $id['value']); ?>
    <fieldset>
        <legend><?php echo $this->lang->line('calendar_event_calendar_event_details');?></legend>
        <div class="control-group<?php echo form_error($name['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('calendar_event_name'), $name['id']); ?>
            <div class="controls">
                <?php echo form_input($name); ?>
                <?php
                if (form_error($name['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($name['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($description['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('calendar_event_description'), $description['id']); ?>
            <div class="controls">
                <?php echo form_textarea($description); ?>
                <?php
                if (form_error($description['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($description['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($startDate['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('calendar_event_start_date'), $startDate['id']); ?>
            <div class="controls">
                <?php echo form_date($startDate); ?>
                <?php
                if (form_error($startDate['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($startDate['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($startTime['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('calendar_event_start_time'), $startTime['id']); ?>
            <div class="controls">
                <?php echo form_time($startTime); ?>
                <?php
                if (form_error($startTime['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($startTime['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($endDate['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('calendar_event_end_date'), $endDate['id']); ?>
            <div class="controls">
                <?php echo form_date($endDate); ?>
                <?php
                if (form_error($endDate['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($endDate['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($endTime['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('calendar_event_end_time'), $endTime['id']); ?>
            <div class="controls">
                <?php echo form_time($endTime); ?>
                <?php
                if (form_error($endTime['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($endTime['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($allDay['name']) ? ' error' : ''; ?>">
            <div class="controls">
                <label class="checkbox" for="<?php echo $allDay['id']; ?>">
                <?php echo form_checkbox($allDay); ?>
                <?php echo $this->lang->line('calendar_event_all_day'); ?>
                </label>
                <?php
                if (form_error($allDay['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($allDay['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <div class="control-group<?php echo form_error($location['name']) ? ' error' : ''; ?>">
            <?php echo form_label($this->lang->line('calendar_event_location'), $location['id']); ?>
            <div class="controls">
                <?php echo form_input($location); ?>
                <?php
                if (form_error($location['name'])) { ?>
                    <span class="help-inline"><?php echo form_error($location['name']); ?></span>
                <?php
                } ?>
            </div>
        </div>
        <?php echo form_submit($submit); ?>
    </fieldset>
<?php
echo form_close(); ?>