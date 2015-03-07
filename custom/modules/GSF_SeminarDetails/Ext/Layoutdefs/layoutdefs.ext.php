<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2011-11-09 23:46:15
$layout_defs["GSF_SeminarDetails"]["subpanel_setup"]["gsf_seminardetails_users"] = array (
  'order' => 100,
  'module' => 'Users',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_GSF_SEMINARDETAILS_USERS_FROM_USERS_TITLE',
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



$layout_defs['GSF_SeminarDetails']['subpanel_setup']['users'] = array(
    'order' => 200,
    'sort_by' => 'user_name',
    'sort_order' => 'asc',
    'module' => 'Users',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'users',
    'add_subpanel_data' => 'user_id',
    'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'Users',
            'mode' => 'MultiSelect',
            //used only as flag to filter the Users popup list
            //see custom\modules\Users\metadata\popupdefs.php
            'initial_filter_fields' => array('name' => 'description'),
        ),
    ),
);

$layout_defs['GSF_SeminarDetails']['subpanel_setup']['gsf_contributions'] = array(
    'order' => 10,
    'sort_by' => 'name',
    'sort_order' => 'asc',
    'module' => 'GSF_Contributions',
    'subpanel_name' => 'Accountdefault',
    'get_subpanel_data' => 'gsf_contributions_link',
    'add_subpanel_data' => 'gsf_contributions_id',
    'title_key' => 'LBL_GSF_CONTRIBUTIONS_SUBPANEL_TITLE',
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
    ),
);

?>