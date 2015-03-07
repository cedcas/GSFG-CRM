<?php
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
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
