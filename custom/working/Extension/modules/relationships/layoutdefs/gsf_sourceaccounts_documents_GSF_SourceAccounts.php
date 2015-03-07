<?php
// created: 2011-03-19 17:04:18
$layout_defs["GSF_SourceAccounts"]["subpanel_setup"]["gsf_sourceaccounts_documents"] = array (
  'order' => 100,
  'module' => 'Documents',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_GSF_SOURCEACCOUNTS_DOCUMENTS_FROM_DOCUMENTS_TITLE',
  'get_subpanel_data' => 'gsf_sourceaccounts_documents',
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
