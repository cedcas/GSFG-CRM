<?php
// created: 2010-07-05 14:59:05
$dictionary["cal_notes_calls"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'cal_notes_calls' => 
    array (
      'lhs_module' => 'cal_Notes',
      'lhs_table' => 'cal_notes',
      'lhs_key' => 'id',
      'rhs_module' => 'Calls',
      'rhs_table' => 'calls',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'cal_notes_calls_c',
      'join_key_lhs' => 'cal_notes_e803l_notes_ida',
      'join_key_rhs' => 'cal_notes_f62elscalls_idb',
    ),
  ),
  'table' => 'cal_notes_calls_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'cal_notes_e803l_notes_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'cal_notes_f62elscalls_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'cal_notes_callsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'cal_notes_calls_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'cal_notes_e803l_notes_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'cal_notes_calls_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'cal_notes_f62elscalls_idb',
      ),
    ),
  ),
);
?>
