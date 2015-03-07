<?php

$dictionary["Task"]["indices"] = array (
    array('name' => 'idx_task_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_task_status', 'type' => 'index', 'fields'=> array('status')),
    array('name' => 'idx_task_start', 'type' => 'index', 'fields'=> array('date_start')),
    array('name' => 'idx_task_parent_id', 'type' => 'index', 'fields'=> array('parent_id')),
    array('name' => 'idx_task_parent_type', 'type' => 'index', 'fields'=> array('parent_type')),
    array('name' => 'idx_task_priority', 'type' => 'index', 'fields'=> array('priority')),
);

 ?>