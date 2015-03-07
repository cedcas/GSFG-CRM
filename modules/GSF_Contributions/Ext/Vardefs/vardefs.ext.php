<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2011-03-24 03:03:20
$dictionary['GSF_Contributions']['fields']['name']['required']=true;

 

// created: 2011-03-19 16:19:06
$dictionary["GSF_Contributions"]["fields"]["accounts_gsf_contributions"] = array (
  'name' => 'accounts_gsf_contributions',
  'type' => 'link',
  'relationship' => 'accounts_gsf_contributions',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_GSF_CONTRIBUTIONS_FROM_ACCOUNTS_TITLE',
);
$dictionary["GSF_Contributions"]["fields"]["accounts_gsf_contributions_name"] = array (
  'name' => 'accounts_gsf_contributions_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_GSF_CONTRIBUTIONS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_g813cccounts_ida',
  'link' => 'accounts_gsf_contributions',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["GSF_Contributions"]["fields"]["accounts_g813cccounts_ida"] = array (
  'name' => 'accounts_g813cccounts_ida',
  'type' => 'link',
  'relationship' => 'accounts_gsf_contributions',
  'source' => 'non-db',
  'reportable' => true,
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_GSF_CONTRIBUTIONS_FROM_GSF_CONTRIBUTIONS_TITLE',
);


 // created: 2011-03-24 03:02:59
$dictionary['GSF_Contributions']['fields']['gsf_contribution_amount']['required']=true;

 

// created: 2011-03-19 17:09:43
$dictionary["GSF_Contributions"]["fields"]["gsf_contributions_documents"] = array (
  'name' => 'gsf_contributions_documents',
  'type' => 'link',
  'relationship' => 'gsf_contributions_documents',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_GSF_CONTRIBUTIONS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);



$dictionary["GSF_Contributions"]["fields"]["gsf_contribution_type"]["required"] = true;
$dictionary["GSF_Contributions"]["fields"]["accounts_gsf_contributions_name"]["required"] = true;
$dictionary["GSF_Contributions"]["fields"]["gsf_contribution_amount"]["required"] = true;

$dictionary["GSF_Contributions"]["fields"]["gsf_seminardetails_id"] = array (
    'name' => 'gsf_seminardetails_id',
    'vname' => 'LBL_GSF_SEMINARDETAILS_ID',
    'required' => false,
    'type' => 'id',
    'reportable' => true,
    'massupdate'=>false,
    'audited' => true,
);

$dictionary["GSF_Contributions"]["fields"]["gsf_seminardetails_name"] = array (
    'required' => false,
    'name'=>'gsf_seminardetails_name',
    'rname'=>'name',
    'id_name'=>'gsf_seminardetails_id',
    'vname'=>'LBL_GSF_SEMINARDETAILS_NAME',
    'type'=>'relate',
    'table'=>'gsf_seminardetails',
    'isnull'=>'true',
    'module'=>'GSF_SeminarDetails',
    'link'=>'gsf_seminardetails_link',
    'massupdate'=>false,
    'source'=>'non-db'
);

$dictionary["GSF_Contributions"]["fields"]["gsf_seminardetails_link"] = array (
    'name' => 'gsf_seminardetails_link',
    'type' => 'link',
    'relationship' => 'gsf_seminardetails_gsf_contributions',
    'vname' => 'LBL_GSF_SEMINARDETAILS_NAME',
    'link_type' => 'one',
    'module'=>'GSF_SeminarDetails',
    'bean_name'=>'GSF_SeminarDetails',
    'source'=>'non-db',
);


$dictionary["GSF_Contributions"]["fields"]["account_id"] = array (
    'name' => 'account_id',
    'vname' => 'LBL_ACCOUNT_ID',
    'required' => false,
    'type' => 'id',
    'reportable' => true,
    'massupdate'=>false,
    'audited' => true,
);

$dictionary["GSF_Contributions"]["fields"]["account_name"] = array (
    'required' => true,
    'name'=>'account_name',
    'rname'=>'name',
    'id_name'=>'account_id',
    'vname'=>'LBL_ACCOUNT_NAME',
    'type'=>'relate',
    'table'=>'accounts',
    'isnull'=>'true',
    'module'=>'Accounts',
    'link'=>'account_link',
    'massupdate'=>false,
    'source'=>'non-db'
);

$dictionary["GSF_Contributions"]["fields"]["account_link"] = array (
    'name' => 'account_link',
    'type' => 'link',
    'relationship' => 'account_gsf_contributions',
    'vname' => 'LBL_ACCOUNT_NAME',
    'link_type' => 'one',
    'module'=>'Accounts',
    'bean_name'=>'Account',
    'source'=>'non-db',
);


$dictionary["GSF_Contributions"]["indices"] = array (
    array('name' => 'idx_contribution_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_contribution_type', 'type' => 'index', 'fields'=> array('gsf_contributions_type')),
    array('name' => 'idx_contribution_repeat', 'type' => 'index', 'fields'=> array('gsf_contribution_repeat_client')),
    array('name' => 'idx_contribution_account', 'type' => 'index', 'fields'=> array('account_id')),
    array('name' => 'idx_contribution_seminar', 'type' => 'index', 'fields'=> array('gsf_seminardetails_id')),
    array('name' => 'idx_contribution_date', 'type' => 'index', 'fields'=> array('gsf_contribution_date')),
);


?>