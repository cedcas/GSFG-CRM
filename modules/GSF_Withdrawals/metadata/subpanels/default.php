<?php
$module_name='GSF_Withdrawals';
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
      'popup_module' => 'GSF_Withdrawal',
    ),
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
	  'widget_class' => 'SubPanelDetailViewLink',
      'width' => '25%',
    ),
    'gsf_withdrawal_type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_TYPE',
      'sortable' => false,
      'width' => '25%',
      'default' => true,
    ),
    'gsf_withdrawal_date' => 
    array (
      'type' => 'date',
      'vname' => 'LBL_GSF_WITHDRAWAL_DATE',
      'width' => '20%',
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
      'module' => 'GSF_Withdrawal',
      'width' => '4%',
      'default' => true,
    ),
    'remove_button' => 
    array (
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'GSF_Withdrawal',
      'width' => '5%',
      'default' => true,
    ),
    'amount_usdollar' => 
    array (
      'usage' => 'query_only',
    ),
  ),
);