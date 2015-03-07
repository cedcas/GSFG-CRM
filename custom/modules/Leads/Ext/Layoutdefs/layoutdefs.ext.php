<?php 
 //WARNING: The contents of this file are auto-generated



$layout_defs["Leads"]["subpanel_setup"]["accounts"] = array(
    'order' => 1,
    'sort_order' => 'asc',
    'sort_by' => 'name',
    'module' => 'Accounts',
    'subpanel_name' => 'customDefault',
    'get_subpanel_data' => 'accounts_link',
    'add_subpanel_data' => 'lead_id',
    'title_key' => 'LBL_ACCOUNTS_SUBPANEL_TITLE',
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
    ),
);

$layout_defs["Leads"]["subpanel_setup"]["documents"] = array(
    'order' => 2,
    'sort_order' => 'asc',
    'sort_by' => 'document_name',
    'module' => 'Documents',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'documents',
    'add_subpanel_data' => 'parent_id',
    'title_key' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        //array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
    ),
);



//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['accounts']['override_subpanel_name'] = 'Lead_subpanel_accounts';

?>