<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2011-03-19 17:18:17
$dictionary["GSF_Seminars"]["fields"]["gsf_seminars_gsf_seminardetails"] = array (
  'name' => 'gsf_seminars_gsf_seminardetails',
  'type' => 'link',
  'relationship' => 'gsf_seminars_gsf_seminardetails',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_GSF_SEMINARS_GSF_SEMINARDETAILS_FROM_GSF_SEMINARDETAILS_TITLE',
);


 // created: 2011-03-04 00:22:18
$dictionary['GSF_Seminars']['fields']['name']['audited']=true;

 


$dictionary["GSF_Seminars"]["fields"]["seminars_income_narrow_ranges"] = array (
    'name' => 'seminars_income_narrow_ranges',
    'vname' => 'LBL_SEMINARS_INCOME_NARROW_RANGES',
    'type' => 'multienum',
    'options' => 'gsf_seminars_income_narrow_ranges_list',
    'len' => '255',
    'required' => false,
    'massupdate' => 0,
    'importable' => 'true',
    'audited' => true,
    'reportable' => true,
    'isMultiSelect' => true,
);

$dictionary["GSF_Seminars"]["fields"]["seminars_age"] = array (
    'name' => 'seminars_age',
    'vname' => 'LBL_SEMINARS_AGE',
    'type' => 'multienum',
    'options' => 'gsf_seminars_age_list',
    'len' => '255',
    'required' => false,
    'massupdate' => 0,
    'importable' => 'true',
    'audited' => true,
    'reportable' => true,
    'isMultiSelect' => true,
);

$dictionary["GSF_Seminars"]["fields"]["seminars_homeowner"] = array (
    'required' => false,
    'name' => 'seminars_homeowner',
    'vname' => 'LBL_SEMINARS_HOMEOWNER',
    'type' => 'bool',
    'default' => 1,
    'massupdate' => 0,
    'importable' => 'true',
    'audited' => false,
    'reportable' => true,
);

$dictionary["GSF_Seminars"]["fields"]["seminar_title"] = array (
    'required' => true,
    'name' => 'seminar_title',
    'vname' => 'LBL_SEMINAR_TITLE',
    'type' => 'varchar',
    'len' => '255',
    'massupdate' => 0,
    'importable' => 'true',
    'audited' => true,
    'reportable' => true,
);


$dictionary["GSF_Seminars"]["indices"] = array (
    array('name' => 'idx_seminars_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_seminars_mailingdate', 'type' => 'index', 'fields'=> array('seminar_mailing_date')),
    array('name' => 'idx_seminars_homeowner', 'type' => 'index', 'fields'=> array('seminars_homeowner')),
);


?>