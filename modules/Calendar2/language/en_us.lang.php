<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/******************************************************************************
OpensourceCRM End User License Agreement

INSTALLING OR USING THE OpensourceCRM's SOFTWARE THAT YOU HAVE SELECTED TO 
PURCHASE IN THE ORDERING PROCESS (THE "SOFTWARE"), YOU ARE AGREEING ON BEHALF OF
THE ENTITY LICENSING THE SOFTWARE ("COMPANY") THAT COMPANY WILL BE BOUND BY AND 
IS BECOMING A PARTY TO THIS END USER LICENSE AGREEMENT ("AGREEMENT") AND THAT 
YOU HAVE THE AUTHORITY TO BIND COMPANY.

IF COMPANY DOES NOT AGREE TO ALL OF THE TERMS OF THIS AGREEMENT, DO NOT SELECT 
THE "ACCEPT" BOX AND DO NOT INSTALL THE SOFTWARE. THE SOFTWARE IS PROTECTED BY 
COPYRIGHT LAWS AND INTERNATIONAL COPYRIGHT TREATIES, AS WELL AS OTHER 
INTELLECTUAL PROPERTY LAWS AND TREATIES. THE SOFTWARE IS LICENSED, NOT SOLD.

    *The COMPANY may not copy, deliver, distribute the SOFTWARE without written
     permit from OpensourceCRM.
    *The COMPANY may not reverse engineer, decompile, or disassemble the 
    SOFTWARE, except and only to the extent that such activity is expressly 
    permitted by applicable law notwithstanding this limitation.
    *The COMPANY may not sell, rent, or lease resell, or otherwise transfer for
     value, the SOFTWARE.
    *Termination. Without prejudice to any other rights, OpensourceCRM may 
    terminate this Agreement if the COMPANY fail to comply with the terms and 
    conditions of this Agreement. In such event, the COMPANY must destroy all 
    copies of the SOFTWARE and all of its component parts.
    *OpensourceCRM will give the COMPANY notice and 30 days to correct above 
    before the contract will be terminated.

The SOFTWARE is protected by copyright and other intellectual property laws and 
treaties. OpensourceCRM owns the title, copyright, and other intellectual 
property rights in the SOFTWARE.
*****************************************************************************/
$mod_strings = array (
  'LBL_MODULE_NAME'=>'Calendar2',
  'LBL_MODULE_TITLE'=>'Calendar2',
  'LNK_NEW_CALL' => 'Schedule Call',
  'LNK_SYNC_BATCH' => 'Sync Batch Process',
  'LNK_NEW_MEETING' => 'Schedule Meeting',
  'LNK_NEW_APPOINTMENT' => 'Create Appointment',
  'LNK_NEW_TASK' => 'Create Task',
  'LNK_CALL_LIST' => 'Calls',
  'LNK_MEETING_LIST' => 'Meetings',
  'LNK_TASK_LIST' => 'Tasks',
  'LNK_VIEW_CALENDAR' => 'Today',
  'LNK_IMPORT_CALLS'=>'Import Calls',
  'LNK_IMPORT_MEETINGS'=>'Import Meetings',
  'LNK_IMPORT_TASKS'=>'Import Tasks',
  'LBL_CALL' => 'Call',
  'LBL_TASK' => 'Task',
  'LBL_MEETING' => 'Meeting',
  'LBL_DAY' => 'Day',
  'LBL_YEAR' => 'Year',
  'LBL_WEEK' => 'Week',
  'LBL_MONTH' => 'Month',
  'LBL_PREVIOUS_MONTH' => 'Previous Month',
  'LBL_PREVIOUS_DAY' => 'Previous Day',
  'LBL_PREVIOUS_YEAR' => 'Previous Year',
  'LBL_PREVIOUS_WEEK' => 'Previous Week',
  'LBL_NEXT_MONTH' => 'Next Month',
  'LBL_NEXT_DAY' => 'Next Day',
  'LBL_NEXT_YEAR' => 'Next Year',
  'LBL_NEXT_WEEK' => 'Next Week',
  'LBL_AM' => 'AM',
  'LBL_PM' => 'PM',
  'LBL_SCHEDULED' => 'Scheduled',
  'LBL_SETTINGS' => 'Calendar2 Settings',
  'LBL_DEFAULT_ACTIVITY' => 'Default activity',
  'LBL_ACTIVITY_DISPLAY' => 'Show Activity',
  'LBL_CALL_SHOW' => 'Call Only',
  'LBL_MEETING_SHOW' => 'Meeting Only',
  'LBL_BOTH_SHOW' => 'Both',
  'LBL_BUSY' => 'Busy',
  'LBL_CONFLICT' => 'Conflict',
  'LBL_USER_CALENDARS' => 'User Calendars',
  'LBL_SHARED' => 'Shared',
  'LBL_PREVIOUS_SHARED' => 'Previous',
  'LBL_NEXT_SHARED' => 'Next',
  'LBL_SHARED_CAL_TITLE' => 'Shared Calendar',

  'LBL_SHAREDMONTHLY' => 'Shared Monthly',
  'LBL_SHAREDMONTHLY_CAL_TITLE' => 'Shared Monthly Calendar',  
  'LBL_PREVIOUS_SHAREDMONTHLY' => 'Previous',
  'LBL_NEXT_SHAREDMONTHLY' => 'Next',  
  
  'LBL_USERS' => 'User',
  'LBL_CONTACTS' => 'Contacts',
  'LBL_REFRESH' => 'Refresh',
  'LBL_EDIT' => 'Edit',
  'LBL_SELECT_USERS' => 'Select users for calendar display',
  'LBL_FILTER_BY_TEAM' => 'Filter user list by team:',
  'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
  'LBL_DATE' => 'Start Date & Time',
  'LNK_RESOURCE_LIST' => 'Resource',
  'LNK_NEW_RES' => 'New Resource',
  'LNK_RES_CAL' => 'Resource Calendar',
  'LBL_YES' => 'Yes',
  'LBL_NO' => 'No',
  'LBL_CREATE_NEW_RECORD' => 'Create new record',
  'LBL_LOADING' => 'Loading.........',
  'LBL_EDIT_RECORD' => 'Edit record',
  'LBL_ERROR_SAVING' => 'Error while saving',
  'LBL_ERROR_LOADING' => 'Error while loading',
  'LBL_ANOTHER_BROWSER' => 'Please try another browser to add more teams.',
  'LBL_FIRST_TEAM' => 'Sorry. You can not remove the first item.',
  'LBL_REMOVE_PARTICIPANTS' => 'You can not remove all participants.',
  'LBL_START_DAY' => 'Start Day of Week:',
  'LBL_START_TIME' => 'Start Time:',
  'LBL_END_TIME' => 'End Time:',
  'LBL_DURATION' => 'Duration:',
  'LBL_NAME' => 'Name:',
  'LBL_DESCRIPTION' => 'Description:',
  'LBL_LOCATION' => 'Location:',
  'LBL_ADDITIONAL_DETAIL' => 'Additional Detail:',
  'LBL_SHOW_TASKS' => 'Show Tasks:',
  'LBL_AUTO_ACCEPT' => 'Automatically accept schedule?:',
  'LBL_GCAL' => 'Google Settings',
  'LBL_CALDAV' => 'Caldav Settings',
  'LBL_GENERAL' => 'General',
  'LBL_PARTICIPANTS' => 'Participants',
  'LBL_INV_CONTACT' => 'Add Invitees',
  'LBL_RECURENCE' => 'Recurrence',
  'LBL_SAVE_BUTTON' => 'Save',
  'LBL_APPLY_BUTTON' => 'Apply',
  'LBL_CANCEL_BUTTON' => 'Cancel',
  'LBL_DELETE_BUTTON' => 'Delete',
  'LBL_TODAY' => 'Today',
  'LBL_NONE' => 'None',
  'LBL_SHOW_SEARCH' => 'Show Search',
  'LBL_HIDE_SEARCH' => 'Hide Search',
  'MSG_CANNOT_REMOVE_FIRST' => 'You can not delete the first schedule of recurring events. Please edit the schedule and save it.',
  'MSG_REMOVE_CONFIRM' => 'Are you sure you want to remove this record?',
  'MSG_CANNOT_HANDLE_YEAR' => 'Sorry, calendar cannot handle the year you requested',
  'MSG_CANNOT_HANDLE_YEAR2' => 'Year must be between 1970 and 2037',
  'LBL_NOTE' => 'Note',
  'LNK_NEW_CALNOTE' => 'New Note',
  'LBL_CALDAV_URL' => 'Caldav Url:',
  'LBL_CALDAV_USERNAME' => 'Username:',
  'LBL_CALDAV_PASSOWRD' => 'Password:',
  'LBL_CALDAV_SYNC_OPT' => 'Sync Options:',
  'LBL_CALDAV_SYNC_OPT1' => 'Two-way (from Calendar2 to Caldav, and Caldav to Calendar2)',
  'LBL_CALDAV_SYNC_OPT2' => 'One-way (from Calendar2 to Caldav)',
  'LBL_CALDAV_SYNC_OPT3' => 'One-way (from Caldav to Calendar2)',
  'LBL_CALDAV_PRIORITY' => 'Priority:',
  'LBL_CALDAV_PRIORITY1' => 'Caldav',
  'LBL_CALDAV_PRIORITY2' => 'Calendar2',
  'LBL_CALDAV_SYNC_MOD' => 'Sync Modules:',
  'LBL_CALDAV_INTERVAL' => 'Interval:',  
  'LBL_CALDAV_TIE_SLOT' => 'Time Slot:',  
  
  'LBL_GCAL_USERNAME' => 'Username:',
  'LBL_GCAL_PASSOWRD' => 'Password:',
  'LBL_GCAL_SYNC_OPT' => 'Sync Options:',
  'LBL_GCAL_SYNC_OPT1' => 'Two-way (from Calendar2 to Google, and Google to Calendar2)',
  'LBL_GCAL_SYNC_OPT2' => 'One-way (from Calendar2 to Google)',
  'LBL_GCAL_SYNC_OPT3' => 'One-way (from Google to Calendar2)',
  'LBL_GCAL_PRIORITY' => 'Priority:',
  'LBL_GCAL_PRIORITY1' => 'Google',
  'LBL_GCAL_PRIORITY2' => 'Calendar2',
  'LBL_GCAL_SYNC_MOD' => 'Sync Modules:',
  'LBL_GCAL_INTERVAL' => 'Interval:',  
  'LBL_GCAL_TIE_SLOT' => 'Time Slot:',  
  'LBL_ACCEPT_STATUS' => 'Accept Status:',  
  'LBL_TIME_SLOT_ALERT' => 'You have some schedules before / after the time slot',  
);

$mod_list_strings = array(
'dom_cal_weekdays'=>array(
"Sun",
"Mon",
"Tue",
"Wed",
"Thu",
"Fri",
"Sat",
),
'dom_cal_weekdays_long'=>array(
"Sunday",
"Monday",
"Tuesday",
"Wednesday",
"Thursday",
"Friday",
"Saturday",
),
'dom_cal_month'=>array(
"",
"Jan",
"Feb",
"Mar",
"Apr",
"May",
"Jun",
"Jul",
"Aug",
"Sep",
"Oct",
"Nov",
"Dec",
),
'dom_cal_month_long'=>array(
"",
"January",
"February",
"March",
"April",
"May",
"June",
"July",
"August",
"September",
"October",
"November",
"December",
)
);
?>
