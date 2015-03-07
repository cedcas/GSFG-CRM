<?php
// created: 2011-11-09 23:46:15
$dictionary["gsf_seminardetails_users"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'gsf_seminardetails_users' => 
    array (
      'lhs_module' => 'GSF_SeminarDetails',
      'lhs_table' => 'gsf_seminardetails',
      'lhs_key' => 'id',
      'rhs_module' => 'Users',
      'rhs_table' => 'users',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'gsf_seminardetails_users',
      'join_key_lhs' => 'gsf_seminardetails_id',
      'join_key_rhs' => 'user_id',
    ),
  ),
  'table' => 'gsf_seminardetails_users',
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
      'name' => 'gsf_seminardetails_id',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'user_id',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
    array (
      0 => 
      array (
	'name' => 'gsf_seminardetails_usersspk',
	'type' => 'primary',
	'fields' => 
	array (
	  0 => 'id',
	),
      ),
      1 => 
      array (
	'name' => 'gsf_seminardetails_users_alt',
	'type' => 'alternate_key',
	'fields' => 
	array (
	  0 => 'gsf_seminardetails_id',
	  1 => 'user_id',
	),
      ),
    ),
);
?>
