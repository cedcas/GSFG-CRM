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
/*********************************************************************************
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights Reserved.
********************************************************************************/
global $mod_strings;
if(ACLController::checkAccess('Calls', 'edit', true))$module_menu[]=Array("index.php?module=Calls&action=EditView&return_module=Calls&return_action=DetailView", $mod_strings['LNK_NEW_CALL'],"CreateCalls");
if(ACLController::checkAccess('Meetings', 'edit', true))$module_menu[]=Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_NEW_MEETING'],"CreateMeetings");
if(ACLController::checkAccess('Tasks', 'edit', true))$module_menu[]=Array("index.php?module=Tasks&action=EditView&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_NEW_TASK'],"CreateTasks");
//if(ACLController::checkAccess('cal_Notes', 'edit', true))$module_menu[]=Array("index.php?module=cal_Notes&action=EditView&return_module=cal_Notes&return_action=DetailView", $mod_strings['LNK_NEW_CALNOTE'],"Createcal_Notes");
if(ACLController::checkAccess('Calls', 'list', true))$module_menu[]=Array("index.php?module=Calls&action=index&return_module=Calls&return_action=DetailView", $mod_strings['LNK_CALL_LIST'],"Calls");
if(ACLController::checkAccess('Meetings', 'list', true))$module_menu[]=Array("index.php?module=Meetings&action=index&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_MEETING_LIST'],"Meetings");
if(ACLController::checkAccess('Tasks', 'list', true))$module_menu[]=Array("index.php?module=Tasks&action=index&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_TASK_LIST'],"Tasks");
//if(ACLController::checkAccess('Resources', 'edit', true))$module_menu[]=Array("index.php?module=Resources&action=EditView&return_module=Resources&return_action=DetailView", $mod_strings['LNK_NEW_RES'],"CreateResource", 'Resources');
if(ACLController::checkAccess('Resources', 'edit', true))$module_menu[]=Array("index.php?module=Resources&action=EditView&return_module=Resources&return_action=DetailView", $mod_strings['LNK_NEW_RES'],"CreateResource");
//if(ACLController::checkAccess('Resources', 'list', true))$module_menu[]=Array("index.php?module=Resources&action=WeeklyListView&return_module=Resources&return_action=DetailView", $mod_strings['LNK_RES_CAL'],"Resources", 'Resources');
if(ACLController::checkAccess('Resources', 'list', true))$module_menu[]=Array("index.php?module=Resources&action=WeeklyListView&return_module=Resources&return_action=DetailView", $mod_strings['LNK_RES_CAL'],"Resources");
//if(ACLController::checkAccess('Resources', 'list', true))$module_menu [] =Array("index.php?module=Resources&action=index&return_module=Resources&return_action=DetailView", $mod_strings['LNK_RESOURCE_LIST'],"Resources", 'Resources');
if(ACLController::checkAccess('Resources', 'list', true))$module_menu [] =Array("index.php?module=Resources&action=index&return_module=Resources&return_action=DetailView", $mod_strings['LNK_RESOURCE_LIST'],"Resources");
if(ACLController::checkAccess('Calls', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Calls&return_module=Calls&return_action=index", $mod_strings['LNK_IMPORT_CALLS'],"Import", 'Calls');
if(ACLController::checkAccess('Meetings', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Meetings&return_module=Meetings&return_action=index", $mod_strings['LNK_IMPORT_MEETINGS'],"Import", 'Meetings');
if(ACLController::checkAccess('Tasks', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Tasks&return_module=Tasks&return_action=index", $mod_strings['LNK_IMPORT_TASKS'],"Import", 'Tasks');

// option batch sync
$module_menu[]=Array("javascript:start_batch();", $mod_strings['LNK_SYNC_BATCH'],"Import", 'Tasks');




?>
