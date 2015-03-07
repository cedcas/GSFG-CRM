<?php

$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(
    1,
    'Update parents field',
    'custom/modules/GSF_Seminars/GSF_Seminars_Hook.php',
    'GSF_Seminars_Hook',
    'update_fields'
);

?>