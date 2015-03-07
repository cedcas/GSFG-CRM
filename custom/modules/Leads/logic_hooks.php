<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
//BEFORE SAVE
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'Leads push feed', 'modules/Leads/SugarFeeds/LeadFeed.php','LeadFeed', 'pushFeed'); 
$hook_array['before_save'][] = Array(2,'','custom/modules/Leads/Leads_Hook.php','Leads_Hook','set_seminar_title',);

//AFTER SAVE
$hook_array['after_save'] = Array();
$hook_array['after_save'][] = Array(1, 'INSERT_INTO_PM_ENTRY_TABLE', 'modules/PM_ProcessManager/insertIntoPmEntryTable.php','insertIntoPmEntryTable', 'setPmEntryTable'); 
//created custom hook for custom checking
//$hook_array['after_save'] = Array();
$hook_array['after_save'][] = Array(2, 'CUSTOM_INSERT_INTO_PM_ENTRY_TABLE', 'custom/modules/Leads/Leads_Hook.php','Leads_Hook', 'setPmEntryTable'); 
$hook_array['after_save'][] = Array(3, 'SET DAYS SINCE INITIAL CONTACT', 'custom/modules/Leads/Leads_Hook.php','Leads_Hook', 'setDaysSinceInitialContact');

//AFTER DELETE
$hook_array['after_delete'] = Array();
$hook_array['after_delete'][] = Array(1, 'INSERT_INTO_PM_ENTRY_TABLE', 'modules/PM_ProcessManager/insertIntoPmEntryTable.php','insertIntoPmEntryTable', 'setPmEntryTable'); 


?>