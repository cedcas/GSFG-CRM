<?php
// created: 2011-03-19 17:07:12
$dictionary["Document"]["fields"]["gsf_withdrawals_documents"] = array (
  'name' => 'gsf_withdrawals_documents',
  'type' => 'link',
  'relationship' => 'gsf_withdrawals_documents',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_WITHDRAWALS_DOCUMENTS_FROM_GSF_WITHDRAWALS_TITLE',
);
$dictionary["Document"]["fields"]["gsf_withdrawals_documents_name"] = array (
  'name' => 'gsf_withdrawals_documents_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_WITHDRAWALS_DOCUMENTS_FROM_GSF_WITHDRAWALS_TITLE',
  'save' => true,
  'id_name' => 'gsf_withdr1ca6drawals_ida',
  'link' => 'gsf_withdrawals_documents',
  'table' => 'gsf_withdrawals',
  'module' => 'GSF_Withdrawals',
  'rname' => 'name',
);
$dictionary["Document"]["fields"]["gsf_withdr1ca6drawals_ida"] = array (
  'name' => 'gsf_withdr1ca6drawals_ida',
  'type' => 'link',
  'relationship' => 'gsf_withdrawals_documents',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_GSF_WITHDRAWALS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);
