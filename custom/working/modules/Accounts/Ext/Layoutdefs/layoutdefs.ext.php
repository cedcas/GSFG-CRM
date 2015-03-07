<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2011-03-19 16:19:06
$layout_defs["Accounts"]["subpanel_setup"]["gsf_contributions"] = array (
  'order' => 20,
  'module' => 'GSF_Contributions',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_GSF_CONTRIBUTIONS_FROM_GSF_CONTRIBUTIONS_TITLE',
  'get_subpanel_data' => 'gsf_contributions_link',
  'add_subpanel_data' => 'account_id',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
  ),
);


// created: 2011-03-19 16:17:15
$layout_defs["Accounts"]["subpanel_setup"]["accounts_gsf_withdrawals"] = array (
  'order' => 100,
  'module' => 'GSF_Withdrawals',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_GSF_WITHDRAWALS_FROM_GSF_WITHDRAWALS_TITLE',
  'get_subpanel_data' => 'accounts_gsf_withdrawals',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
  ),
);



unset($layout_defs['Accounts']['subpanel_setup']['accounts']);



// created: 2011-02-07 13:04:32
$layout_defs["Accounts"]["subpanel_setup"]["accounts_documents"] = array (
  'order' => 100,
  'module' => 'Documents',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
  'get_subpanel_data' => 'accounts_documents',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


// created: 2011-03-19 16:15:48
$layout_defs["Accounts"]["subpanel_setup"]["accounts_gsf_sourceaccounts"] = array (
  'order' => 10,
  'module' => 'GSF_SourceAccounts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_GSF_SOURCEACCOUNTS_FROM_GSF_SOURCEACCOUNTS_TITLE',
  'get_subpanel_data' => 'accounts_gsf_sourceaccounts',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
  ),
);


//auto-generated file DO NOT EDIT
$layout_defs['Accounts']['subpanel_setup']['gsf_contributions']['override_subpanel_name'] = 'Accountdefault';


//auto-generated file DO NOT EDIT
$layout_defs['Accounts']['subpanel_setup']['accounts_gsf_withdrawals']['override_subpanel_name'] = 'Accountdefault';

?>