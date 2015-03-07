<?php
// created: 2011-04-12 19:42:53
$dictionary["Lead"]["fields"]["gsf_seminardetails_leads"] = array (
  'name' => 'gsf_seminardetails_leads',
  'type' => 'link',
  'relationship' => 'gsf_seminardetails_leads',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SEMINARDETAILS_LEADS_FROM_GSF_SEMINARDETAILS_TITLE',
);
$dictionary["Lead"]["fields"]["gsf_seminardetails_leads_name"] = array (
  'name' => 'gsf_seminardetails_leads_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SEMINARDETAILS_LEADS_FROM_GSF_SEMINARDETAILS_TITLE',
  'save' => true,
  'id_name' => 'gsf_semina6647details_ida',
  'link' => 'gsf_seminardetails_leads',
  'table' => 'gsf_seminardetails',
  'module' => 'GSF_SeminarDetails',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["gsf_semina6647details_ida"] = array (
  'name' => 'gsf_semina6647details_ida',
  'type' => 'link',
  'relationship' => 'gsf_seminardetails_leads',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_GSF_SEMINARDETAILS_LEADS_FROM_LEADS_TITLE',
);
