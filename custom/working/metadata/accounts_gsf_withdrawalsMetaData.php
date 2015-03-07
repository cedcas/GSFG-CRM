<?php
// created: 2011-03-19 16:17:15
$dictionary["accounts_gsf_withdrawals"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'accounts_gsf_withdrawals' => 
    array (
      'lhs_module' => 'Accounts',
      'lhs_table' => 'accounts',
      'lhs_key' => 'id',
      'rhs_module' => 'GSF_Withdrawals',
      'rhs_table' => 'gsf_withdrawals',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'accounts_gs_withdrawals_c',
      'join_key_lhs' => 'accounts_ge7aaccounts_ida',
      'join_key_rhs' => 'accounts_gce4bdrawals_idb',
    ),
  ),
  'table' => 'accounts_gs_withdrawals_c',
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
      'name' => 'accounts_ge7aaccounts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'accounts_gce4bdrawals_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'accounts_gsf_withdrawalsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'accounts_gsf_withdrawals_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'accounts_ge7aaccounts_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'accounts_gsf_withdrawals_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'accounts_gce4bdrawals_idb',
      ),
    ),
  ),
);
?>
