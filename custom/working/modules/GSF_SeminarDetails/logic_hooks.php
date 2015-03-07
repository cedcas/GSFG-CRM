<?php

$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(
    1,
    'add_code',
    'custom/modules/GSF_SeminarDetails/add_code_hook.php',
    'add_code',
    'add_code'
);

$hook_array['after_save'][] = Array(
    2,
    'Update parents field',
    'custom/modules/GSF_SeminarDetails/GSF_SeminarDetails_Hook.php',
    'GSF_SeminarDetails_Hook',
    'update_parent_field'
);

$hook_array['before_delete'] = Array(); 
$hook_array['before_delete'][] = Array(
    1,
    'Update parents field',
    'custom/modules/GSF_SeminarDetails/GSF_SeminarDetails_Hook.php',
    'GSF_SeminarDetails_Hook',
    'update_parent_field'
);

?>