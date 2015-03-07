<?php

$dictionary["Document"]["fields"]["leads_documents"] = array (
  'name' => 'leads_documents',
  'type' => 'link',
  'relationship' => 'leads_documents',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_NAME',
);
$dictionary["Document"]["fields"]["parent_name"] = array (
  'name' => 'parent_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_NAME',
  'save' => true,
  'id_name' => 'parent_id',
  'link' => 'leads_documents',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
);
$dictionary["Document"]["fields"]["parent_id"] = array (
  'name' => 'parent_id',
  'type' => 'link',
  'relationship' => 'leads_documents',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_LEADS_ID',
);

?>