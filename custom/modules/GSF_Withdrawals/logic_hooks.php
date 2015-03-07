<?php

$hook_array['before_save'] = Array();
$hook_array['after_save'] = Array();
$hook_array['after_delete'] = Array();

$hook_array['before_save'][] = Array(
    1,
    'Set name',
    'custom/modules/GSF_Withdrawals/GSF_Withdrawals_Hook.php',
    'GSF_Withdrawals_Hook',
    'set_name'
);

$hook_array['after_save'][] = Array(
    1,
    'Update accounts total withdrawal',
    'custom/modules/GSF_Withdrawals/GSF_Withdrawals_Hook.php',
    'GSF_Withdrawals_Hook',
    'update_accounts_total_withdrawal'
);

$hook_array['after_delete'][] = Array(
    1,
    'Update accounts total withdrawal',
    'custom/modules/GSF_Withdrawals/GSF_Withdrawals_Hook.php',
    'GSF_Withdrawals_Hook',
    'update_accounts_total_withdrawal'
);

?>