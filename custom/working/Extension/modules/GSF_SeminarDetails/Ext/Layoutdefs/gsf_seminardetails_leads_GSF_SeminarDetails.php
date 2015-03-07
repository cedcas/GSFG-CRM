<?php
// created: 2011-04-12 19:42:53
$layout_defs["GSF_SeminarDetails"]["subpanel_setup"]["gsf_seminardetails_leads"] = array (
  'order' => 100,
  'module' => 'Leads',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_GSF_SEMINARDETAILS_LEADS_FROM_LEADS_TITLE',
  'get_subpanel_data' => 'gsf_seminardetails_leads',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
