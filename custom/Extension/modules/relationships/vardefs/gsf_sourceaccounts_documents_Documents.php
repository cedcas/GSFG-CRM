<?php
// created: 2011-03-19 17:04:18
$dictionary["Document"]["fields"]["gsf_sourceaccounts_documents"] = array (
  'name' => 'gsf_sourceaccounts_documents',
  'type' => 'link',
  'relationship' => 'gsf_sourceaccounts_documents',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SOURCEACCOUNTS_DOCUMENTS_FROM_GSF_SOURCEACCOUNTS_TITLE',
);
$dictionary["Document"]["fields"]["gsf_sourceaccounts_documents_name"] = array (
  'name' => 'gsf_sourceaccounts_documents_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SOURCEACCOUNTS_DOCUMENTS_FROM_GSF_SOURCEACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'gsf_source0973ccounts_ida',
  'link' => 'gsf_sourceaccounts_documents',
  'table' => 'gsf_sourceaccounts',
  'module' => 'GSF_SourceAccounts',
  'rname' => 'name',
);
$dictionary["Document"]["fields"]["gsf_source0973ccounts_ida"] = array (
  'name' => 'gsf_source0973ccounts_ida',
  'type' => 'link',
  'relationship' => 'gsf_sourceaccounts_documents',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_GSF_SOURCEACCOUNTS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);
