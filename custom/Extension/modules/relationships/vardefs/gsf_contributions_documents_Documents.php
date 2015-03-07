<?php
// created: 2011-03-19 17:09:43
$dictionary["Document"]["fields"]["gsf_contributions_documents"] = array (
  'name' => 'gsf_contributions_documents',
  'type' => 'link',
  'relationship' => 'gsf_contributions_documents',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_CONTRIBUTIONS_DOCUMENTS_FROM_GSF_CONTRIBUTIONS_TITLE',
);
$dictionary["Document"]["fields"]["gsf_contributions_documents_name"] = array (
  'name' => 'gsf_contributions_documents_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_CONTRIBUTIONS_DOCUMENTS_FROM_GSF_CONTRIBUTIONS_TITLE',
  'save' => true,
  'id_name' => 'gsf_contri93cfbutions_ida',
  'link' => 'gsf_contributions_documents',
  'table' => 'gsf_contributions',
  'module' => 'GSF_Contributions',
  'rname' => 'name',
);
$dictionary["Document"]["fields"]["gsf_contri93cfbutions_ida"] = array (
  'name' => 'gsf_contri93cfbutions_ida',
  'type' => 'link',
  'relationship' => 'gsf_contributions_documents',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_GSF_CONTRIBUTIONS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);
