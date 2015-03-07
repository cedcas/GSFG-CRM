<?php
// created: 2011-02-07 13:04:32
$dictionary["Document"]["fields"]["accounts_documents"] = array (
  'name' => 'accounts_documents',
  'type' => 'link',
  'relationship' => 'accounts_documents',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_DOCUMENTS_FROM_ACCOUNTS_TITLE',
);
$dictionary["Document"]["fields"]["accounts_documents_name"] = array (
  'name' => 'accounts_documents_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_DOCUMENTS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_d13e2ccounts_ida',
  'link' => 'accounts_documents',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["Document"]["fields"]["accounts_d13e2ccounts_ida"] = array (
  'name' => 'accounts_d13e2ccounts_ida',
  'type' => 'link',
  'relationship' => 'accounts_documents',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);
