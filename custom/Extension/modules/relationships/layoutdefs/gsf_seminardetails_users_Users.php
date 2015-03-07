<?php
// created: 2011-11-09 23:46:15
$layout_defs["Users"]["subpanel_setup"]["gsf_seminardetails_users"] = array (
  'order' => 100,
  'module' => 'GSF_SeminarDetails',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_GSF_SEMINARDETAILS_USERS_FROM_GSF_SEMINARDETAILS_TITLE',
  'get_subpanel_data' => 'gsf_seminardetails_users',
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
