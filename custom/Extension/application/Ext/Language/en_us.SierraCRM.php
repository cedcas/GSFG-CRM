<?php 
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004 - 2007 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

$app_list_strings["moduleList"]["PM_ProcessManager"] = 'Process Manager';
$app_list_strings["moduleList"]["PM_ProcessManagerStage"] = 'Process Manager Stage';
$app_list_strings["moduleList"]["PM_ProcessManagerStageTask"] = 'Process Manager Stage Task';
$app_list_strings["pm_processmanager_type_dom"] = array (
  'Administration' => 'Administration',
  'Product' => 'Product',
  'User' => 'User',
);
$app_list_strings["pm_processmanager_status_dom"] = array (
  'New' => 'New',
  'Assigned' => 'Assigned',
  'Closed' => 'Closed',
  'Pending Input' => 'Pending Input',
  'Rejected' => 'Rejected',
  'Duplicate' => 'Duplicate',
);
$app_list_strings["pm_processmanager_priority_dom"] = array (
  'P1' => 'High',
  'P2' => 'Medium',
  'P3' => 'Low',
);
$app_list_strings["pm_processmanager_resolution_dom"] = array ( '' => '',
  '' => '',
  'Accepted' => 'Accepted',
  'Duplicate' => 'Duplicate',
  'Closed' => 'Closed',
  'Out of Date' => 'Out of Date',
  'Invalid' => 'Invalid',
);
$app_list_strings["process_object"] = array (
  '' => '',
  'leads' => 'leads',
  'opportunities' => 'opportunities',
  'accounts' => 'accounts',
  'contacts' => 'contacts',
  'cases' => 'cases',
  'bugs' => 'bugs',
  'project' => 'project',
  'tasks' => 'tasks',
  'calls' => 'calls'
);
$app_list_strings['process_status_dom']=array ( '' => '',
  '' => '',
);
$app_list_strings['process_status_dom']=array ( '' => '',
  '' => '',
  'Active' => 'Active',
  'Inactive' => 'Inactive',
);

$app_list_strings['process_start_event']=array (
  'Create' => 'Create',
  'Modify' => 'Modify',
);
$app_list_strings['process_object_field']=array ( '' => '',
  '' => '',
);
$app_list_strings['process_cancel_event']=array (
  '--None--' => '--None--',
  'Delete' => 'Delete',
  'Modify' => 'Modify',
);
$app_list_strings['process_object_cancel_field']=array ( '' => '',
  '' => '',
);
$app_list_strings['start_delay_minutes']=array (
  0 => '0',  
  1 => '1',
  2 => '2',
  3 => '3',
  4 => '4',
  5 => '5',
  6 => '6',
  7 => '7',
  8 => '8',
  9 => '9',
  10 => '10',
  11 => '11',
  12 => '12',
  13 => '13',
  14 => '14',
  15 => '15',
  16 => '16',
  17 => '17',
  18 => '18',
  19 => '19',
  20 => '20',
  21 => '21',
  22 => '22',
  23 => '23',
  24 => '24',
  25 => '25',
  26 => '26',
  27 => '27',
  28 => '28',
  29 => '29',
  30 => '30',
  31 => '31',
  32 => '32',
  33 => '33',
  34 => '34',
  35 => '35',
  36 => '36',
  37 => '37',
  38 => '38',
  39 => '39',
  40 => '40',
  41 => '41',
  42 => '42',
  43 => '43',
  44 => '44',
  45 => '45',
  46 => '46',
  47 => '47',
  48 => '48',
  49 => '49',
  50 => '50',
  51 => '51',
  52 => '52',
  53 => '53',
  54 => '54',
  55 => '55',
  56 => '56',
  57 => '57',
  58 => '58',
  59 => '59',
  60 => '60',   
);
$app_list_strings['start_delay_hours']=array (
  0 => '0',  
1 => '1',
  2 => '2',
  3 => '3',
  4 => '4',
  5 => '5',
  6 => '6',
  7 => '7',
  8 => '8',
  9 => '9',
  10 => '10',
  11 => '11',
  12 => '12',
  13 => '13',
  14 => '14',
  15 => '15',
  16 => '16',
  17 => '17',
  18 => '18',
  19 => '19',
  20 => '20',
  21 => '21',
  22 => '22',
  23 => '23',
);
$app_list_strings['start_delay_days']=array (
  0 => '0',  
1 => '1',
  2 => '2',
  3 => '3',
  4 => '4',
  5 => '5',
  6 => '6',
  7 => '7',
  8 => '8',
  9 => '9',
  10 => '10',
  11 => '11',
  12 => '12',
  13 => '13',
  14 => '14',
  15 => '15',
  16 => '16',
  17 => '17',
  18 => '18',
  19 => '19',
  20 => '20',
  21 => '21',
  22 => '22',
  23 => '23',
  24 => '24',
  25 => '25',
  26 => '26',
  27 => '27',
  28 => '28',
  29 => '29',
  30 => '30',
  31 => '31',
);
$app_list_strings['start_delay_months']=array (
  0 => '0',  
1 => '1',
  2 => '2',
  3 => '3',
  4 => '4',
  5 => '5',
  6 => '6',
  7 => '7',
  8 => '8',
  9 => '9',
  10 => '10',
  11 => '11',
  12 => '12',
);
$app_list_strings['start_delay_years']=array (
  0 => '0',  
1 => '1',
  2 => '2',
  3 => '3',
  4 => '4',
  5 => '5',
  6 => '6',
  7 => '7',
  8 => '8',
  9 => '9',
  10 => '10',
);
$app_list_strings['task_type']=array (
  '' => '',
  'Send Email' => 'Send Email',
  'Schedule Call' => 'Schedule Call',
  'Create Task' => 'Create Task',
  'Schedule Meeting' => 'Schedule Meeting',
  'Custom Script' => 'Custom Script',
  'Create New Record' => 'Create New Record',
  'Create Project Task' => 'Create Project Task',
);
$app_list_strings['task_start_delay_type']=array (
  '--None--' => '--None--',
  'Create' => 'Create',
  'Modify' => 'Modify',
  'From Completion of Previous Task' => 'From Completion of Previous Task',
);
//Create Stage Task App List Strings
$app_list_strings['task_priority']=array (
  'High' => 'High',
  'Medium' => 'Medium',
  'Low' => 'Low',
  );

//And Or Filter Fields
$app_list_strings['and_or_filter_fields']=array (
  'and' => 'abd',
  'or' => 'or',
);

$app_list_strings['reminder_time_options']=array (
  0 => 'None',
  60 => '1 minute prior',
  300 => '5 minutes prior',
  600 => '10 minutes prior',
  900 => '15 minutes prior',
  1800 => '30 minutes prior',
  3600 => '1 hour prior',
);

?>