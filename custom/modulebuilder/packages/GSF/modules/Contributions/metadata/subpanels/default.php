<?php
$module_name='GSF_Contributions';
$subpanel_layout = array (
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'popup_module' => 'GSF_Contribution',
    ),
  ),
  'where' => '',
  'list_fields' => 
  array (
    'gsf_contribution_amount' => 
    array (
      'type' => 'currency',
      'vname' => 'LBL_GSF_CONTRIBUTION_AMOUNT',
      'currency_format' => true,
      'width' => '25%',
      'default' => true,
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
    'remove_button' => 
    array (
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'GSF_Contribution',
      'width' => '5%',
      'default' => true,
    ),
    'amount_usdollar' => 
    array (
      'usage' => 'query_only',
    ),
  ),
);