<?php

$hook_array['after_save'] = Array();
$hook_array['after_delete'] = Array();

$hook_array['after_save'][] = Array(
    1,
    'Update accounts total projected amount',
    'custom/modules/GSF_SourceAccounts/GSF_SourceAccounts_Hook.php',
    'GSF_SourceAccounts_Hook',
    'update_accounts_total_projected_amount'
);

$hook_array['after_delete'][] = Array(
    1,
    'Update accounts total projected amount',
    'custom/modules/GSF_SourceAccounts/GSF_SourceAccounts_Hook.php',
    'GSF_SourceAccounts_Hook',
    'update_accounts_total_projected_amount'
);

?>