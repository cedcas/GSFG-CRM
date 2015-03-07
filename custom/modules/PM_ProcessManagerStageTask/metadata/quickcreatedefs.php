<?php
$module_name = 'PM_ProcessManagerStageTask';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
    ),
    'panels' => 
    array (
      'DEFAULT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'task_type',
            'label' => 'LBL_TASK_TYPE',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'start_delay_type',
            'label' => 'LBL_START_DELAY_TYPE',
          ),
          1 => 
          array (
            'name' => 'task_order',
            'label' => 'LBL_TASK_ORDER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'CUSTOM SCRIPT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'custom_script',
            'label' => 'LBL_CUSTOM_SCRIPT',
          ),
        ),
      ),
      'TASK EMAIL DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'email_templates',
            'label' => 'LBL_CHOOSE_EMAIL_TEMPLATE',
          ),
          1 => 
          array (
            'name' => 'contact_roles',
            'label' => 'LBL_CHOOSE_CONTACT_ROLE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'internal_email',
            'customCode' => '{if $fields.internal_email.value == "1"}{assign var="INTERNAL_EMAIL_CHECK" value="checked"}{else}{assign var="INTERNAL_EMAIL_CHECK" value=""}{/if}<input name="internal_email"  type="checkbox" class="checkbox" value="1" {$INTERNAL_EMAIL_CHECK}>',
            'label' => 'LBL_INTERNAL_EMAIL',
          ),
          1 => 
          array (
            'name' => 'send_email_to_caseopp_account',
            'customCode' => '{if $fields.send_email_to_caseopp_account.value == "1"}{assign var="SEND_EMAIL_TO_CASE_OPP_ACCOUNT" value="checked"}{else}{assign var="SEND_EMAIL_TO_CASE_OPP_ACCOUNT" value=""}{/if}<input name="send_email_to_caseopp_account" id="send_email_to_caseopp_account"  type="checkbox" class="checkbox" value="1" {$SEND_EMAIL_TO_CASE_OPP_ACCOUNT}>',
            'label' => 'LBL_SEND_EMAIL_TO_CASEOPP_ACCOUNT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'internal_email_to_address',
            'label' => 'LBL_INTERNAL_EMAIL_ADDRESS',
          ),
          1 => 
          array (
            'name' => 'send_email_to_object_owner',
            'customCode' => '{if $fields.send_email_to_object_owner.value == "1"}{assign var="SEND_EMAIL_TO_OBJECT_OWNER" value="checked"}{else}{assign var="SEND_EMAIL_TO_OBJECT_OWNER" value=""}{/if}<input name="send_email_to_object_owner" id="send_email_to_object_owner"  type="checkbox" class="checkbox" value="1" {$SEND_EMAIL_TO_OBJECT_OWNER}>',
            'label' => 'LBL_SEND_EMAIL_TO_OBJECT_OWNER',
          ),
        ),
      ),
      'TASK DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'task_subject',
            'label' => 'LBL_TASK_SUBJECT',
          ),
          1 => 
          array (
            'name' => 'task_priority',
            'label' => 'LBL_TASK_PRIORITY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'task_due_date_delay_minutes',
            'label' => 'LBL_DUE_DATE_DELAY_MINUTES',
          ),
          1 => 
          array (
            'name' => 'task_due_date_delay_hours',
            'label' => 'LBL_DUE_DATE_DELAY_HOURS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'task_due_date_delay_days',
            'label' => 'LBL_DUE_DATE_DELAY_DAYS',
          ),
          1 => 
          array (
            'name' => 'task_due_date_delay_months',
            'label' => 'LBL_DUE_DATE_DELAY_MONTHS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'task_due_date_delay_years',
            'label' => 'LBL_DUE_DATE_DELAY_YEARS',
          ),
          1 => 
          array (
            'name' => 'assigned_user_id_task',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'is_escalatable_task_edit',
            'label' => 'LBL_IS_ESCALATABLE_TASK',
          ),
          1 => 
          array (
            'name' => 'escalation_delay_minutes_task',
            'label' => 'LBL_ESCALATION_DELAY_MINUTES_TASK',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'task_description',
            'label' => 'LBL_TASK_DESCRIPTION',
          ),
        ),
      ),
      'PROJECT TASK DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'project_task_subject',
            'label' => 'LBL_PROJECT_TASK_SUBJECT',
          ),
          1 => 
          array (
            'name' => 'project_task_id',
            'label' => 'LBL_PROJECT_TASK_ID',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'project_task_status',
            'label' => 'LBL_PROJECT_TASK_STATUS',
          ),
          1 => 
          array (
            'name' => 'project_task_priority',
            'label' => 'LBL_PROJECT_TASK_PRIORITY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'project_task_start_date',
            'label' => 'LBL_PROJECT_TASK_START_DATE_DELAY',
          ),
          1 => 
          array (
            'name' => 'project_task_end_date',
            'label' => 'LBL_PROJECT_TASK_END_DATE_DELAY',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_id_project_task',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'project_task_milestone',
            'label' => 'LBL_PROJECT_TASK_MILESTONE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'project_task_task_number',
            'label' => 'LBL_PROJECT_TASK_TASK_NUMBER',
          ),
          1 => 
          array (
            'name' => 'project_task_order',
            'label' => 'LBL_PROJECT_TASK_ORDER',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'project_task_description',
            'label' => 'LBL_PROJECT_TASK_DESCRIPTION',
          ),
        ),
      ),
      'CALL DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'call_subject',
            'label' => 'LBL_CALL_SUBJECT',
          ),
          1 => 
          array (
            'name' => 'reminder_time',
            'customCode' => '{if $fields.reminder_checked.value == "1"}{assign var="REMINDER_TIME_DISPLAY" value="inline"}{assign var="REMINDER_CHECKED" value="checked"}{else}{assign var="REMINDER_TIME_DISPLAY" value="none"}{assign var="REMINDER_CHECKED" value=""}{/if}<input name="reminder_checked" type="hidden" value="0"><input name="reminder_checked" onclick=\'toggleDisplay("should_remind_list");\' type="checkbox" class="checkbox" value="1" {$REMINDER_CHECKED}><div id="should_remind_list" style="display:{$REMINDER_TIME_DISPLAY}">{$fields.reminder_time.value}</div>',
            'label' => 'LBL_REMINDER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'call_due_date_delay_minutes',
            'label' => 'LBL_START_DATE_DELAY_MINUTES',
          ),
          1 => 
          array (
            'name' => 'call_due_date_delay_hours',
            'label' => 'LBL_START_DATE_DELAY_HOURS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'call_due_date_delay_days',
            'label' => 'LBL_START_DATE_DELAY_DAYS',
          ),
          1 => 
          array (
            'name' => 'call_due_date_delay_months',
            'label' => 'LBL_START_DATE_DELAY_MONTHS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'call_due_date_delay_years',
            'label' => 'LBL_START_DATE_DELAY_YEARS',
          ),
          1 => 
          array (
            'name' => 'assigned_user_id_call',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'is_escalatable_call_edit',
            'label' => 'LBL_IS_ESCALATABLE_CALL',
          ),
          1 => 
          array (
            'name' => 'escalation_delay_minutes_call',
            'label' => 'LBL_ESCALATION_DELAY_MINUTES_CALL',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'call_description',
            'label' => 'LBL_CALL_DESCRIPTION',
          ),
        ),
      ),
      'MEETING DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'meeting_subject',
            'label' => 'LBL_MEETING_SUBJECT',
          ),
          1 => 
          array (
            'name' => 'meeting_reminder_time',
            'customCode' => '{if $fields.meeting_reminder_checked.value == "1"}{assign var="MEETING_REMINDER_TIME_DISPLAY" value="inline"}{assign var="MEETING_REMINDER_CHECKED" value="checked"}{else}{assign var="MEETING_REMINDER_TIME_DISPLAY" value="none"}{assign var="MEETING_REMINDER_CHECKED" value=""}{/if}<input name="meeting_reminder_checked" type="hidden" value="0"><input name="meeting_reminder_checked" onclick=\'toggleDisplay("should_remind_list_meeting");\' type="checkbox" class="checkbox" value="1" {$MEETING_REMINDER_CHECKED}><div id="should_remind_list_meeting" style="display:{$MEETING_REMINDER_TIME_DISPLAY}">{$fields.meeting_reminder_time.value}</div>',
            'label' => 'LBL_REMINDER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'meeting_start_delay_minutes',
            'label' => 'LBL_START_DATE_DELAY_MINUTES',
          ),
          1 => 
          array (
            'name' => 'meeting_start_delay_hours',
            'label' => 'LBL_START_DATE_DELAY_HOURS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'meeting_start_delay_days',
            'label' => 'LBL_START_DATE_DELAY_DAYS',
          ),
          1 => 
          array (
            'name' => 'meeting_start_delay_months',
            'label' => 'LBL_START_DATE_DELAY_MONTHS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'meeting_start_delay_years',
            'label' => 'LBL_START_DATE_DELAY_YEARS',
          ),
          1 => 
          array (
            'name' => 'assigned_user_id_meeting',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'meeting_location',
            'label' => 'LBL_MEETING_LOCATION',
          ),
          1 => 
          array (
            'name' => '',
            'label' => '',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'meeting_description',
            'label' => 'LBL_MEETING_DESCRIPTION',
          ),
        ),
      ),
      'CREATE OBJECT DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'create_object_type',
            'label' => 'LBL_CREATE_OBJECT_TYPE',
          ),
          1 => 
          array (
            'name' => 'create_object_id',
            'label' => 'LBL_CREATE_OBJECT_ID',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'create_object_delay_minutes',
            'label' => 'LBL_CREATE_OBJECT_DELAY_MINUTES',
          ),
          1 => 
          array (
            'name' => 'create_object_delay_hours',
            'label' => 'LBL_CREATE_OBJECT_DELAY_HOURS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'create_object_delay_days',
            'label' => 'LBL_CREATE_OBJECT_DELAY_DAYS',
          ),
          1 => 
          array (
            'name' => 'create_object_delay_months',
            'label' => 'LBL_CREATE_OBJECT_DELAY_MONTHS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'create_object_delay_years',
            'label' => 'LBL_CREATE_OBJECT_DELAY_YEARS',
          ),
          1 => 
          array (
            'name' => 'assigned_user_id_create_object',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'inherit_parent_data',
            'label' => 'LBL_INHERIT_PARENT_DATA',
          ),
          1 => 
          array (
            'name' => 'inherit_parent_relationships',
            'label' => 'LBL_INHERIT_PARENT_RELATIONSHIPS',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'create_object_description',
            'label' => 'LBL_CREATE_OBJECT_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
?>
