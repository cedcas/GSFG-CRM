<?php
// created: 2011-03-19 17:14:19
$dictionary["GSF_SeminarDetails"]["fields"]["gsf_venues_gsf_seminardetails"] = array (
  'name' => 'gsf_venues_gsf_seminardetails',
  'type' => 'link',
  'relationship' => 'gsf_venues_gsf_seminardetails',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_VENUES_GSF_SEMINARDETAILS_FROM_GSF_VENUES_TITLE',
);
$dictionary["GSF_SeminarDetails"]["fields"]["gsf_venues_gsf_seminardetails_name"] = array (
  'name' => 'gsf_venues_gsf_seminardetails_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_VENUES_GSF_SEMINARDETAILS_FROM_GSF_VENUES_TITLE',
  'save' => true,
  'id_name' => 'gsf_venues56d9_venues_ida',
  'link' => 'gsf_venues_gsf_seminardetails',
  'table' => 'gsf_venues',
  'module' => 'GSF_Venues',
  'rname' => 'name',
);
$dictionary["GSF_SeminarDetails"]["fields"]["gsf_venues56d9_venues_ida"] = array (
  'name' => 'gsf_venues56d9_venues_ida',
  'type' => 'link',
  'relationship' => 'gsf_venues_gsf_seminardetails',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_GSF_VENUES_GSF_SEMINARDETAILS_FROM_GSF_SEMINARDETAILS_TITLE',
);
