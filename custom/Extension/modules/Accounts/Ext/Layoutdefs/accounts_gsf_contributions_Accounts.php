<?php
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
