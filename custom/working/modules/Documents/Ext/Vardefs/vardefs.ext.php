<?php 
 //WARNING: The contents of this file are auto-generated


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


 // created: 2012-03-02 17:33:46

 

 // created: 2012-03-02 17:33:46

 

 // created: 2012-03-02 17:33:46

 


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



 // created: 2012-03-02 17:33:46

 

 // created: 2012-03-02 17:33:46

 

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


 // created: 2011-02-06 15:38:17
$dictionary['Document']['fields']['template_type']['options']='gsf_documents_type_list';
$dictionary['Document']['fields']['template_type']['reportable']=true;

 

 // created: 2012-03-02 17:33:46

 

// created: 2011-02-07 13:04:32
$dictionary["Document"]["fields"]["accounts_documents"] = array (
  'name' => 'accounts_documents',
  'type' => 'link',
  'relationship' => 'accounts_documents',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_DOCUMENTS_FROM_ACCOUNTS_TITLE',
);
$dictionary["Document"]["fields"]["accounts_documents_name"] = array (
  'name' => 'accounts_documents_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_DOCUMENTS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_d13e2ccounts_ida',
  'link' => 'accounts_documents',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["Document"]["fields"]["accounts_d13e2ccounts_ida"] = array (
  'name' => 'accounts_d13e2ccounts_ida',
  'type' => 'link',
  'relationship' => 'accounts_documents',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
);


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


 // created: 2011-02-06 15:30:42
$dictionary['Document']['fields']['active_date']['required']=false;

 
?>