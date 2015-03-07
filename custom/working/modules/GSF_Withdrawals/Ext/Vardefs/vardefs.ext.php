<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2011-03-24 03:24:25
$dictionary['GSF_Withdrawals']['fields']['name']['required']=true;

 

// created: 2011-03-19 16:17:15
$dictionary["GSF_Withdrawals"]["fields"]["accounts_gsf_withdrawals"] = array (
  'name' => 'accounts_gsf_withdrawals',
  'type' => 'link',
  'relationship' => 'accounts_gsf_withdrawals',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_GSF_WITHDRAWALS_FROM_ACCOUNTS_TITLE',
);
$dictionary["GSF_Withdrawals"]["fields"]["accounts_gsf_withdrawals_name"] = array (
  'name' => 'accounts_gsf_withdrawals_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_GSF_WITHDRAWALS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_ge7aaccounts_ida',
  'link' => 'accounts_gsf_withdrawals',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["GSF_Withdrawals"]["fields"]["accounts_ge7aaccounts_ida"] = array (
  'name' => 'accounts_ge7aaccounts_ida',
  'type' => 'id',
  'relationship' => 'accounts_gsf_withdrawals',
  'source' => 'non-db',
  'reportable' => false,
  'vname' => 'LBL_ACCOUNTS_GSF_WITHDRAWALS_FROM_GSF_WITHDRAWALS_TITLE',
);


// created: 2011-03-19 17:07:12
$dictionary["GSF_Withdrawals"]["fields"]["gsf_withdrawals_documents"] = array (
  'name' => 'gsf_withdrawals_documents',
  'type' => 'link',
  'relationship' => 'gsf_withdrawals_documents',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_GSF_WITHDRAWALS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);


 // created: 2011-03-24 03:24:10
$dictionary['GSF_Withdrawals']['fields']['gsf_withdrawal_amount']['required']=true;

 


$dictionary["GSF_Withdrawals"]["indices"] = array (
    array('name' => 'idx_withdrawals_del', 'type' => 'index', 'fields'=> array('deleted')),
    array('name' => 'idx_withdrawals_type', 'type' => 'index', 'fields'=> array('gsf_withdrawal_type')),
    array('name' => 'idx_withdrawals_date', 'type' => 'index', 'fields'=> array('gsf_withdrawal_date')),
);

 
?>