<?php
// created: 2011-03-19 17:14:19
$dictionary["gsf_venues_gsf_seminardetails"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'gsf_venues_gsf_seminardetails' => 
    array (
      'lhs_module' => 'GSF_Venues',
      'lhs_table' => 'gsf_venues',
      'lhs_key' => 'id',
      'rhs_module' => 'GSF_SeminarDetails',
      'rhs_table' => 'gsf_seminardetails',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'gsf_venues_minardetails_c',
      'join_key_lhs' => 'gsf_venues56d9_venues_ida',
      'join_key_rhs' => 'gsf_venuesc61bdetails_idb',
    ),
  ),
  'table' => 'gsf_venues_minardetails_c',
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
      'name' => 'gsf_venues56d9_venues_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'gsf_venuesc61bdetails_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'gsf_venues_seminardetailsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'gsf_venues_seminardetails_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'gsf_venues56d9_venues_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'gsf_venues_seminardetails_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'gsf_venuesc61bdetails_idb',
      ),
    ),
  ),
);
?>
