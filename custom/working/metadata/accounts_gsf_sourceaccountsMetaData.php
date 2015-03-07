<?php
// created: 2011-03-19 16:15:48
$dictionary["accounts_gsf_sourceaccounts"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'accounts_gsf_sourceaccounts' => 
    array (
      'lhs_module' => 'Accounts',
      'lhs_table' => 'accounts',
      'lhs_key' => 'id',
      'rhs_module' => 'GSF_SourceAccounts',
      'rhs_table' => 'gsf_sourceaccounts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'accounts_gsurceaccounts_c',
      'join_key_lhs' => 'accounts_g2316ccounts_ida',
      'join_key_rhs' => 'accounts_gf0f6ccounts_idb',
    ),
  ),
  'table' => 'accounts_gsurceaccounts_c',
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
      'name' => 'accounts_g2316ccounts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'accounts_gf0f6ccounts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'accounts_gssourceaccountsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'accounts_gssourceaccounts_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'accounts_g2316ccounts_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'accounts_gssourceaccounts_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'accounts_gf0f6ccounts_idb',
      ),
    ),
  ),
);
?>
