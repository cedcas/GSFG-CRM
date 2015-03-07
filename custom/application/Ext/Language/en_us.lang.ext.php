<?php 
 //WARNING: The contents of this file are auto-generated


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
$app_list_strings['moduleList']['Calendar2'] = 'Calendar2';
$app_list_strings['moduleList']['Resources'] = 'Resources';
$app_list_strings['moduleList']['cal_Notes'] = 'Cal2Notes';
$app_list_strings['res_type_dom'] = array (
    '' => '',
    'Room' => 'Room',
    'Car' => 'Car',
    'Projector' => 'Projector',
    'Hall' => 'Hall',
    'Other' => 'Other',
  );
$app_list_strings['res_status_default_key'] = 'Active';
$app_list_strings['res_status_dom'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
  );
$app_list_strings['category_list'] = array (
  'customerin' => 'Meeting Customer (in)',
  'customerout' => 'Meeting Customer (out)',
  'meeting' => 'Internal',
  'vacation' => 'Vacation',
  'holiday' => 'Holiday',
  'training' => 'Training',
  'event' => 'Event',
  'project' => 'Project Work',
  'etc' => 'etc.',
  );
$app_list_strings['repeat_types'] = array (
	''			=>	'None',
	'Daily'			=>	'Daily',
	'Weekly'		=>	'Weekly',
	'Monthly (date)'	=>	'Monthly (date)',
	'Monthly (day)'		=>	'Monthly (day)',
	'Yearly'		=>	'Yearly',
);
$app_strings['LBL_NOTATION_DAY'] = 'l F j, Y';
$app_strings['LBL_NOTATION_WEEK'] = 'F j, Y';
$app_strings['LBL_NOTATION_MONTH'] = 'F, Y';
$app_strings['LBL_CAL2_DURATION'] = 'Duration:';
$app_strings['LBL_CAL2_NAME'] = 'Name:';
$app_strings['LBL_CAL2_ADDITIONAL_DETAIL'] = 'Additional Detail';
$app_strings['LBL_CATEGORY'] = 'Category';
$app_strings['MSG_CONFIRM_EDIT_RECURRENCE'] = 'Do you want to edit all recurring records at once?';


 
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



/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2010 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

$app_list_strings['moduleList']['GSF_Withdrawals'] = 'Withdrawal';
$app_list_strings['moduleList']['GSF_Seminars'] = 'Seminars';
$app_list_strings['moduleList']['GSF_SourceAccounts'] = 'Source Accounts';
$app_list_strings['moduleList']['GSF_Contributions'] = 'Contribution';
$app_list_strings['moduleList']['GSF_SeminarDetails'] = 'Seminar Details';
$app_list_strings['moduleList']['GSF_Venues'] = 'Venues';
$app_list_strings['gsf_withdrawals_type_dom'][''] = '';
$app_list_strings['gsf_withdrawals_type_dom']['Existing Business'] = 'Existing Business';
$app_list_strings['gsf_withdrawals_type_dom']['New Business'] = 'New Business';
$app_list_strings['gsf_contributions_type_dom'][''] = '';
$app_list_strings['gsf_contributions_type_dom']['Existing Business'] = 'Existing Business';
$app_list_strings['gsf_contributions_type_dom']['New Business'] = 'New Business';
$app_list_strings['gsf_venues_type_dom'][''] = '';
$app_list_strings['gsf_venues_type_dom']['Analyst'] = 'Analyst';
$app_list_strings['gsf_venues_type_dom']['Competitor'] = 'Competitor';
$app_list_strings['gsf_venues_type_dom']['Customer'] = 'Customer';
$app_list_strings['gsf_venues_type_dom']['Integrator'] = 'Integrator';
$app_list_strings['gsf_venues_type_dom']['Investor'] = 'Investor';
$app_list_strings['gsf_venues_type_dom']['Partner'] = 'Partner';
$app_list_strings['gsf_venues_type_dom']['Press'] = 'Press';
$app_list_strings['gsf_venues_type_dom']['Prospect'] = 'Prospect';
$app_list_strings['gsf_venues_type_dom']['Reseller'] = 'Reseller';
$app_list_strings['gsf_venues_type_dom']['Other'] = 'Other';
$app_list_strings['gsf_accounts_products_list']['Allianz 1'] = 'Allianz - MasterDex X Annuity | 10 Years';
$app_list_strings['gsf_accounts_products_list']['Allianz 2'] = 'Allianz - MasterDex 5 Plus |10 Years';
$app_list_strings['gsf_accounts_products_list']['Allianz 3'] = 'Allianz - Endurance Elite | 5 Years';
$app_list_strings['gsf_accounts_products_list']['American Equity 1'] = 'American Equity - Retirement Gold | 10 Years';
$app_list_strings['gsf_accounts_products_list']['American Equity 2'] = 'American Equity - Integrity Gold';
$app_list_strings['gsf_accounts_products_list']['Jackson National 1'] = 'Jackson National - Elite Choice Rewards | 10 Years';
$app_list_strings['gsf_accounts_products_list']['Jackson National 2'] = 'Jackson National - Elite Choice | 10 Years';
$app_list_strings['gsf_accounts_products_list']['Jackson National 3'] = 'Jackson National - Ascender Plus Select | 10 Years';
$app_list_strings['gsf_accounts_products_list']['ING 1'] = 'ING - Secure Index Opportunities Plus | 10 Years';
$app_list_strings['gsf_accounts_products_list']['Old Mutual 1'] = 'Old Mutual - Index Accelerator 7 | 7 Years';
$app_list_strings['gsf_accounts_products_list']['Aviva 1'] = 'Aviva - Multi-Choice Index (MCIX) | 10 Years';
$app_list_strings['gsf_accounts_products_list']['Other'] = 'Other';
$app_list_strings['gsf_accounts_tax_statuses_list']['IRA'] = 'IRA';
$app_list_strings['gsf_accounts_tax_statuses_list']['NQ'] = 'NQ';
$app_list_strings['gsf_accounts_tax_statuses_list']['Roth IRA'] = 'Roth IRA';
$app_list_strings['gsf_accounts_tax_statuses_list']['SEP IRA'] = 'SEP IRA';
$app_list_strings['gsf_accounts_tax_statuses_list']['401K'] = '401K';
$app_list_strings['gsf_venues_type_list']['Restaurant'] = 'Restaurant';
$app_list_strings['gsf_venues_type_list']['Hotel'] = 'Hotel';
$app_list_strings['gsf_venues_type_list']['Other'] = 'Other';
$app_list_strings['gsf_withdrawal_type_list'][''] = '';
$app_list_strings['gsf_withdrawal_type_list']['Type 1'] = 'Type 1';
$app_list_strings['gsf_withdrawal_type_list']['Type 2'] = 'Type 2';
$app_list_strings['gsf_withdrawal_type_list']['Type 3'] = 'Type 3';
$app_list_strings['gsf_withdrawal_type_list']['Type 4'] = 'Type 4';
$app_list_strings['gsf_withdrawal_type_list']['Type 5'] = 'Type 5';
$app_list_strings['gsf_contribution_type_list'][''] = '';
$app_list_strings['gsf_contribution_type_list']['Type 1'] = 'Type 1';
$app_list_strings['gsf_contribution_type_list']['Type 2'] = 'Type 2';
$app_list_strings['gsf_contribution_type_list']['Type 3'] = 'Type 3';
$app_list_strings['gsf_contribution_type_list']['Type 4'] = 'Type 4';
$app_list_strings['gsf_contribution_type_list']['Type 5'] = 'Type 5';
$app_list_strings['account_type_dom'][''] = '';
$app_list_strings['account_type_dom']['Analyst'] = 'Analyst';
$app_list_strings['account_type_dom']['Competitor'] = 'Competitor';
$app_list_strings['account_type_dom']['Customer'] = 'Customer';
$app_list_strings['account_type_dom']['Integrator'] = 'Integrator';
$app_list_strings['account_type_dom']['Investor'] = 'Investor';
$app_list_strings['account_type_dom']['Partner'] = 'Partner';
$app_list_strings['account_type_dom']['Press'] = 'Press';
$app_list_strings['account_type_dom']['Prospect'] = 'Prospect';
$app_list_strings['account_type_dom']['Reseller'] = 'Reseller';
$app_list_strings['account_type_dom']['Other'] = 'Other';
$app_list_strings['gsf_time_list'][''] = '';
$app_list_strings['gsf_time_list']['1:00:00 PM'] = '1:00:00 PM';
$app_list_strings['gsf_time_list']['2:00:00 PM'] = '2:00:00 PM';
$app_list_strings['gsf_time_list']['3:00:00 PM'] = '3:00:00 PM';
$app_list_strings['gsf_time_list']['4:00:00 PM'] = '4:00:00 PM';
$app_list_strings['gsf_time_list']['5:00:00 PM'] = '5:00:00 PM';
$app_list_strings['gsf_time_list']['6:00:00 PM'] = '6:00:00 PM';
$app_list_strings['gsf_time_list']['7:00:00 PM'] = '7:00:00 PM';
$app_list_strings['gsf_time_list']['8:00:00 PM'] = '8:00:00 PM';
$app_list_strings['gsf_time_list']['9:00:00 PM'] = '9:00:00 PM';
$app_list_strings['gsf_time_list']['10:00:00 PM'] = '10:00:00 PM';
$app_list_strings['gsf_time_list']['11:00:00 PM'] = '11:00:00 PM';
$app_list_strings['gsf_time_list']['12:00:00 AM'] = '12:00:00 AM';



/*********************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed 
 * by KINAMU Business Solutions AG. All rights ar (c) 2010 by KINAMU Business
 * Solutions AG.
 *
 * This Version of the KReporter is licensed software and may only be used in 
 * alignment with the signed subscription ahgreement with KINAMU Business Solutions
 * AG. This Software is copyrighted and may not be further distributed
 * 
 * You can contact KINAMU Business Solutions AG at Am Concordepark 2/F12
 * A-2320 Schwechat or via email at office@kinamu.com
 * 
 ********************************************************************************/
$app_list_strings['moduleList']['KReports'] = 'Reports';


?>