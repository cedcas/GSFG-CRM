<?php
$module_name = 'PM_ProcessManagerStageTask';
$viewdefs = array (
$module_name =>
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
        ),
      ),
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

        2=> 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),

      'CUSTOM SCRIPT DETAILS' => 
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
      'EMAIL DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'email_template_name',
            'label' => 'LBL_CHOOSE_EMAIL_TEMPLATE',
          ),
         1 => 
          array (
            'name' => 'contact_role',
            'label' => 'LBL_CONTACT_ROLE',
          ),  
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_internal_email',
                        'customCode' => '{if $fields.internal_email.value == "1"}' .
            	 	        '{assign var="INTERNAL_EMAIL_CHECK" value="checked"}' .
            	 	        '{else}' .
            	 	        '{assign var="INTERNAL_EMAIL_CHECK" value=""}' .
            	 	        '{/if}' .
            	 	        '<input name="internal_email"  type="checkbox" DISABLED class="checkbox" value="1" {$INTERNAL_EMAIL_CHECK}>',
                    'label' => 'LBL_INTERNAL_EMAIL',
            'label' => 'LBL_INTERNAL_EMAIL',
          ),
         1 => 
          array (
            'name' => 'detail_view_internal_email_to_address',
            'label' => 'LBL_INTERNAL_EMAIL_ADDRESS',
          ),  
        ), 
        2 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_send_email_to_caseopp_account',
                        'customCode' => '{if $fields.send_email_to_caseopp_account.value == "1"}' .
            	 	        '{assign var="DETAIL_VIEW_SEND_EMAIL_TO_CASEOPP_ACCOUNT_CHECK" value="checked"}' .
            	 	        '{else}' .
            	 	        '{assign var="DETAIL_VIEW_SEND_EMAIL_TO_CASEOPP_ACCOUNT_CHECK" value=""}' .
            	 	        '{/if}' .
            	 	        '<input name="send_email_to_caseopp_account"  type="checkbox" DISABLED class="checkbox" value="1" {$DETAIL_VIEW_SEND_EMAIL_TO_CASEOPP_ACCOUNT_CHECK}>',
                    'label' => 'LBL_SEND_EMAIL_TO_CASEOPP_ACCOUNT',
            'label' => 'LBL_SEND_EMAIL_TO_CASEOPP_ACCOUNT',
          ), 
         1 => 
          array (
            'name' => 'detail_view_send_email_to_object_owner',
                        'customCode' => '{if $fields.send_email_to_object_owner.value == "1"}' .
            	 	        '{assign var="DETAIL_VIEW_SEND_EMAIL_TO_OBJECT_OWNER_CHECK" value="checked"}' .
            	 	        '{else}' .
            	 	        '{assign var="DETAIL_VIEW_SEND_EMAIL_TO_OBJECT_OWNER_CHECK" value=""}' .
            	 	        '{/if}' .
            	 	        '<input name="send_email_to_object_owner"  type="checkbox" DISABLED class="checkbox" value="1" {$DETAIL_VIEW_SEND_EMAIL_TO_OBJECT_OWNER_CHECK}>',
                    'label' => 'LBL_SEND_EMAIL_TO_OBJECT_OWNER',
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
            'name' => 'detail_view_task_subject',
            'label' => 'LBL_TASK_SUBJECT',
          ),
         1 => 
          array (
            'name' => 'detail_view_task_priority',
            'label' => 'LBL_TASK_PRIORITY',
          ),  
        ), 
         1 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_due_date_delay_minutes',
            'label' => 'LBL_DUE_DATE_DELAY_MINUTES',
          ),
         1 => 
          array (
            'name' => 'detail_view_due_date_delay_hours',
            'label' => 'LBL_DUE_DATE_DELAY_HOURS',
          ),  
        ), 
        2 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_due_date_delay_days',
            'label' => 'LBL_DUE_DATE_DELAY_DAYS',
          ),
         1 => 
          array (
            'name' => 'detail_view_due_date_delay_months',
            'label' => 'LBL_DUE_DATE_DELAY_MONTHS',
          ),  
        ), 
        3 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_due_date_delay_years',
            'label' => 'LBL_DUE_DATE_DELAY_YEARS',
          ),
         1 => 
          array (
            'name' => 'detail_view_assigned_user_id_task',
            'label' => 'LBL_ASSIGNED_TO',
          ),  
        ),                         
        4 => 
        array (
          0 => 
          array (
            'name' => 'is_escalatable_task',
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
            'name' => 'detai_view_task_description',
            'label' => 'LBL_TASK_DESCRIPTION',
          ),
         1 => 
          array (
            '' => '',
            '' => '',
          ),  
        ),        
                                         
      ),
      //PROJECT TASKS
      'PROJECT TASK DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_project_task_subject',
            'label' => 'LBL_PROJECT_TASK_SUBJECT',
          ),
          1 => 
          array (
          	'name' => 'detail_view_project_task_id',
          	'label' => 'LBL_PROJECT_TASK_ID',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_project_task_status',
            'label' => 'LBL_PROJECT_TASK_STATUS',
          ),
          1 => 
          array (
            'name' => 'detail_view_project_task_priority',
            'label' => 'LBL_PROJECT_TASK_PRIORITY',
          ), 
        ), 
        2 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_project_task_start_date',
            'label' => 'LBL_PROJECT_TASK_START_DATE_DELAY',
          ),
          1 => 
          array (
            'name' => 'detail_view_project_task_end_date',
            'label' => 'LBL_PROJECT_TASK_END_DATE_DELAY',
          ), 
        ),
        3 => 
        array (
          0 => 
     	  array (
            'name' => 'detail_view_assigned_user_id_project_task',
            'label' => 'LBL_ASSIGNED_TO',
          ), 
          1 => 
     	  array (
            'name' => 'detail_view_project_task_milestone',
            'label' => 'LBL_PROJECT_TASK_MILESTONE',
          ),
        ),
        4 => 
        array (
          0 => 
     	  array (
            'name' => 'detail_view_project_task_task_number',
            'label' => 'LBL_PROJECT_TASK_TASK_NUMBER',
          ),
          1 => 
     	  array (
            'name' => 'detail_view_project_task_order',
            'label' => 'LBL_PROJECT_TASK_ORDER',
          ),  
        ),      
        5 => 
        array (
          0 => 
          array (
            'name' => 'detai_view_project_task_description',
            'label' => 'LBL_PROJECT_TASK_DESCRIPTION',
          ),   

        ),                                
      ),
      //END PROJECT TASKS
       'CALL DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_call_call_subject',
            'label' => 'LBL_CALL_SUBJECT',
          ),
         1 => 
          array (
            'name' => 'detail_view_call_reminder_time',
            'label' => 'LBL_REMINDER_TIME',
          ),  
        ), 
         1 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_call_start_delay_minutes',
            'label' => 'LBL_START_DATE_DELAY_MINUTES',
          ),
         1 => 
          array (
            'name' => 'detail_view_call_start_delay_hours',
            'label' => 'LBL_START_DATE_DELAY_HOURS',
          ),  
        ), 
        2 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_call_start_delay_days',
            'label' => 'LBL_START_DATE_DELAY_DAYS',
          ),
         1 => 
          array (
            'name' => 'detail_view_call_start_delay_months',
            'label' => 'LBL_START_DATE_DELAY_MONTHS',
          ),  
        ), 
        3 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_call_start_delay_years',
            'label' => 'LBL_START_DATE_DELAY_YEARS',
          ),
         1 => 
          array (
            'name' => 'detail_view_call_assigned_user_id_call',
            'label' => 'LBL_ASSIGNED_TO',
          ),  
        ),                         
        4 => 
        array (
          0 => 
          array (
            'name' => 'is_escalatable_call',
            'label' => 'LBL_IS_ESCALATABLE_CALL',
          ),
         1 => 
          array (
            'name' => 'detail_view_escalation_delay_minutes_call',
            'label' => 'LBL_ESCALATION_DELAY_MINUTES_CALL',
          ),  
        ), 
       5 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_call_description',
            'label' => 'LBL_CALL_DESCRIPTION',
          ),
         1 => 
          array (
            '' => '',
            '' => '',
          ),  
        ),                                 
      ), 
       'MEETING DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_meeting_meeting_subject',
            'label' => 'LBL_MEETING_SUBJECT',
          ),
         1 => 
          array (
            'name' => 'detail_view_meeting_reminder_time',
            'label' => 'LBL_REMINDER_TIME',
          ),  
        ), 
         1 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_meeting_start_delay_minutes',
            'label' => 'LBL_START_DATE_DELAY_MINUTES',
          ),
         1 => 
          array (
            'name' => 'detail_view_meeting_start_delay_hours',
            'label' => 'LBL_START_DATE_DELAY_HOURS',
          ),  
        ), 
        2 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_meeting_start_delay_days',
            'label' => 'LBL_START_DATE_DELAY_DAYS',
          ),
         1 => 
          array (
            'name' => 'detail_view_meeting_start_delay_months',
            'label' => 'LBL_START_DATE_DELAY_MONTHS',
          ),  
        ), 
        3 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_meeting_start_delay_years',
            'label' => 'LBL_START_DATE_DELAY_YEARS',
          ),
         1 => 
          array (
            'name' => 'detail_view_meeting_assigned_user_id_meeting',
            'label' => 'LBL_ASSIGNED_TO',
          ),  
        ),                         
        4 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_meeting_meeting_location',
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
            'name' => 'detail_view_meeting_meeting_description',
            'label' => 'LBL_MEETING_DESCRIPTION',
          ),
         1 => 
          array (
            '' => '',
            '' => '',
          ),  
        ),                                 
      ), 
       'CREATE NEW RECORD DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_create_object_create_object_type',
            'label' => 'LBL_CREATE_OBJECT_TYPE',
          ),
          1 => 
          array (
            'name' => 'detail_view_create_object_create_object_id',
            'label' => 'LBL_CREATE_OBJECT_ID',
          ),          
        ),
         1 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_create_object_create_object_delay_minutes',
            'label' => 'LBL_CREATE_OBJECT_DELAY_MINUTES',
          ),
         1 => 
          array (
            'name' => 'detail_view_create_object_create_object_delay_hours',
            'label' => 'LBL_CREATE_OBJECT_DELAY_HOURS',
          ),  
        ), 
        2 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_create_object_create_object_delay_days',
            'label' => 'LBL_CREATE_OBJECT_DELAY_DAYS',
          ),
         1 => 
          array (
            'name' => 'detail_view_create_object_create_object_delay_months',
            'label' => 'LBL_CREATE_OBJECT_DELAY_MONTHS',
          ),  
        ), 
        3 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_create_object_create_object_delay_years',
            'label' => 'LBL_CREATE_OBJECT_DELAY_YEARS',
          ),
         1 => 
          array (
            'name' => 'detail_view_create_object_assigned_user_id_create_object',
            'label' => 'LBL_ASSIGNED_TO',
          ),  
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_create_object_inherit_parent_data',
            'label' => 'LBL_INHERIT_PARENT_DATA',
          ),
         1 => 
          array (
            'name' => 'detail_view_create_object_inherit_parent_relationships',
            'label' => 'LBL_INHERIT_PARENT_RELATIONSHIPS',
          ),  
        ),        
        5 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_create_object_create_object_description',
            'label' => 'LBL_CREATE_OBJECT_DESCRIPTION',
          ),
         1 => 
          array (
            'name' => '',
            'label' => '',
          ),  
        ),                                    
      )                            
    ),
  ),
)
);
?>
