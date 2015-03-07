<?php
// created: 2011-03-19 17:07:12
$dictionary["gsf_withdrawals_documents"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'gsf_withdrawals_documents' => 
    array (
      'lhs_module' => 'GSF_Withdrawals',
      'lhs_table' => 'gsf_withdrawals',
      'lhs_key' => 'id',
      'rhs_module' => 'Documents',
      'rhs_table' => 'documents',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'gsf_withdrals_documents_c',
      'join_key_lhs' => 'gsf_withdr1ca6drawals_ida',
      'join_key_rhs' => 'gsf_withdr53ebcuments_idb',
    ),
  ),
  'table' => 'gsf_withdrals_documents_c',
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
      'name' => 'gsf_withdr1ca6drawals_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'gsf_withdr53ebcuments_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
    5 => 
    array (
      'name' => 'document_revision_id',
      'type' => 'varchar',
      'len' => '36',
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'gsf_withdrawals_documentsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'gsf_withdrawals_documents_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'gsf_withdr1ca6drawals_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'gsf_withdrawals_documents_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'gsf_withdr53ebcuments_idb',
      ),
    ),
  ),
);
?>
