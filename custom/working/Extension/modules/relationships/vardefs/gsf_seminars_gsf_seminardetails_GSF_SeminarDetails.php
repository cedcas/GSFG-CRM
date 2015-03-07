<?php
// created: 2011-03-19 17:18:17
$dictionary["GSF_SeminarDetails"]["fields"]["gsf_seminars_gsf_seminardetails"] = array (
  'name' => 'gsf_seminars_gsf_seminardetails',
  'type' => 'link',
  'relationship' => 'gsf_seminars_gsf_seminardetails',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SEMINARS_GSF_SEMINARDETAILS_FROM_GSF_SEMINARS_TITLE',
);
$dictionary["GSF_SeminarDetails"]["fields"]["gsf_seminars_gsf_seminardetails_name"] = array (
  'name' => 'gsf_seminars_gsf_seminardetails_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_GSF_SEMINARS_GSF_SEMINARDETAILS_FROM_GSF_SEMINARS_TITLE',
  'save' => true,
  'id_name' => 'gsf_seminac629eminars_ida',
  'link' => 'gsf_seminars_gsf_seminardetails',
  'table' => 'gsf_seminars',
  'module' => 'GSF_Seminars',
  'rname' => 'name',
);
$dictionary["GSF_SeminarDetails"]["fields"]["gsf_seminac629eminars_ida"] = array (
  'name' => 'gsf_seminac629eminars_ida',
  'type' => 'link',
  'relationship' => 'gsf_seminars_gsf_seminardetails',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_GSF_SEMINARS_GSF_SEMINARDETAILS_FROM_GSF_SEMINARDETAILS_TITLE',
);
