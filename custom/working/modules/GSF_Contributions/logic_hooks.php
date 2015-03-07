<?php

$hook_array['before_save'] = Array();
$hook_array['after_save'] = Array();
$hook_array['after_delete'] = Array();

$hook_array['before_save'][] = Array(
    1,
    'Set name',
    'custom/modules/GSF_Contributions/GSF_Contributions_Hook.php',
    'GSF_Contributions_Hook',
    'set_name'
);

$hook_array['after_save'][] = Array(
    1,
    'Update accounts total contributions',
    'custom/modules/GSF_Contributions/GSF_Contributions_Hook.php',
    'GSF_Contributions_Hook',
    'update_accounts_total_contributions'
);

$hook_array['after_delete'][] = Array(
    1,
    'Update accounts total contributions',
    'custom/modules/GSF_Contributions/GSF_Contributions_Hook.php',
    'GSF_Contributions_Hook',
    'update_accounts_total_contributions'
);

?>