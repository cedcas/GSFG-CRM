<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(1, 'INSERT_INTO_PM_ENTRY_TABLE', 'modules/PM_ProcessManager/insertIntoPmEntryTable.php','insertIntoPmEntryTable', 'setPmEntryTable'); 
$hook_array['after_delete'] = Array(); 
$hook_array['after_delete'][] = Array(1, 'INSERT_INTO_PM_ENTRY_TABLE', 'modules/PM_ProcessManager/insertIntoPmEntryTable.php','insertIntoPmEntryTable', 'setPmEntryTable'); 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'notify', 'custom/include/Calls/google_calendar_plg.php','GoogleCalls', 'GoogleCalls'); 
$hook_array['before_delete'] = Array(); 
$hook_array['before_delete'][] = Array(1, 'notify', 'custom/include/Calls/before_delete_google.php','GoogleCalls', 'GoogleCalls'); 



?>