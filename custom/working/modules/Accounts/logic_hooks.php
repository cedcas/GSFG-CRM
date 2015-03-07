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

//ADDED BY JOED@ASI
$hook_array['after_save'][] = Array(
    2,
    'Set repeat client/account',
    'custom/modules/Accounts/Accounts_Hook.php',
    'Accounts_Hook',
    'set_repeat_client'
);

$hook_array['after_save'][] = Array(
    3,
    'Calculate account fields',
    'custom/modules/Accounts/Accounts_Hook.php',
    'Accounts_Hook',
    'update_account_values'
);

$hook_array['after_delete'][] = Array(
    2,
    'Set repeat client/account',
    'custom/modules/Accounts/Accounts_Hook.php',
    'Accounts_Hook',
    'set_repeat_client'
);


// Change leads.status = Client when Account is created for it
// Requirement 2.6
// @KMJ 2011-11-13
$hook_array['after_save'][] = Array(
    4,
    'Change leads.status = Client when Account is created for it',
    'custom/modules/Accounts/Accounts_Hook.php',
    'Accounts_Hook',
    'checkAccountSetLead'
);

?>