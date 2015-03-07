<?php
// created: 2011-04-12 19:42:53
$dictionary["gsf_seminardetails_leads"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'gsf_seminardetails_leads' => 
    array (
      'lhs_module' => 'GSF_SeminarDetails',
      'lhs_table' => 'gsf_seminardetails',
      'lhs_key' => 'id',
      'rhs_module' => 'Leads',
      'rhs_table' => 'leads',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'gsf_seminaretails_leads_c',
      'join_key_lhs' => 'gsf_semina6647details_ida',
      'join_key_rhs' => 'gsf_semina5325dsleads_idb',
    ),
  ),
  'table' => 'gsf_seminaretails_leads_c',
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
      'name' => 'gsf_semina6647details_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'gsf_semina5325dsleads_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'gsf_seminardetails_leadsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'gsf_seminardetails_leads_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'gsf_semina6647details_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'gsf_seminardetails_leads_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'gsf_semina5325dsleads_idb',
      ),
    ),
  ),
);
?>
