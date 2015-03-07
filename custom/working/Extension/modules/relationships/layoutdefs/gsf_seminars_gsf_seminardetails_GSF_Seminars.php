<?php
// created: 2011-03-19 17:18:17
$layout_defs["GSF_Seminars"]["subpanel_setup"]["gsf_seminars_gsf_seminardetails"] = array (
  'order' => 100,
  'module' => 'GSF_SeminarDetails',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_GSF_SEMINARS_GSF_SEMINARDETAILS_FROM_GSF_SEMINARDETAILS_TITLE',
  'get_subpanel_data' => 'gsf_seminars_gsf_seminardetails',
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
