<?php
// created: 2011-03-19 17:18:17
$dictionary["gsf_seminars_gsf_seminardetails"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'gsf_seminars_gsf_seminardetails' => 
    array (
      'lhs_module' => 'GSF_Seminars',
      'lhs_table' => 'gsf_seminars',
      'lhs_key' => 'id',
      'rhs_module' => 'GSF_SeminarDetails',
      'rhs_table' => 'gsf_seminardetails',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'gsf_seminarminardetails_c',
      'join_key_lhs' => 'gsf_seminac629eminars_ida',
      'join_key_rhs' => 'gsf_semina6236details_idb',
    ),
  ),
  'table' => 'gsf_seminarminardetails_c',
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
      'name' => 'gsf_seminac629eminars_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'gsf_semina6236details_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'gsf_seminarseminardetailsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'gsf_seminarseminardetails_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'gsf_seminac629eminars_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'gsf_seminarseminardetails_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'gsf_semina6236details_idb',
      ),
    ),
  ),
);
?>
