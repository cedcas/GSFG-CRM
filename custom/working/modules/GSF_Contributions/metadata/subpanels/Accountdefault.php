<?php
// created: 2011-03-24 03:08:41
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'type' => 'name',
    'vname' => 'LBL_GSF_CONTRIBUTION_AMOUNT',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
  ),
  'gsf_contribution_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'vname' => 'LBL_TYPE',
    'sortable' => false,
    'width' => '25%',
    'default' => true,
  ),
  'gsf_contribution_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_GSF_CONTRIBUTION_DATE',
    'width' => '20%',
    'default' => true,
  ),
  'gsf_contribution_repeat_client' => 
  array (
    'type' => 'bool',
    'vname' => 'LBL_GSF_CONTRIBUTION_REPEAT_CLIENT',
    'width' => '10%',
    'default' => true,
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => 'created_by_link',
    'vname' => 'LBL_CREATED',
    'width' => '10%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'widget_class' => 'SubPanelEditButton',
    'module' => 'GSF_Contribution',
    'width' => '4%',
    'default' => true,
  ),
  'amount_usdollar' => 
  array (
    'usage' => 'query_only',
  ),
);
?>
