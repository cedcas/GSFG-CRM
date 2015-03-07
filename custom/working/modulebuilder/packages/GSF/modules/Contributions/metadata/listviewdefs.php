<?php
$module_name = 'GSF_Contributions';
$OBJECT_NAME = 'GSF_CONTRIBUTIONS';
$listViewDefs [$module_name] = 
array (
  'GSF_CONTRIBUTION_AMOUNT' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_GSF_CONTRIBUTION_AMOUNT',
    'currency_format' => true,
    'width' => '25%',
    'default' => true,
  ),
  'GSF_CONTRIBUTION_TYPE' => 
  array (
    'width' => '25%',
    'label' => 'LBL_TYPE',
    'default' => true,
  ),
  'GSF_CONTRIBUTION_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_GSF_CONTRIBUTION_DATE',
    'width' => '20%',
    'default' => true,
  ),
  'GSF_CONTRIBUTION_REPEAT_CLIENT' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_GSF_CONTRIBUTION_REPEAT_CLIENT',
    'width' => '10%',
    'default' => true,
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CREATED',
    'default' => true,
  ),
  'LEAD_SOURCE' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LEAD_SOURCE',
    'default' => false,
  ),
  'SALES_STAGE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_SALE_STAGE',
    'default' => false,
  ),
  'NEXT_STEP' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NEXT_STEP',
    'default' => false,
  ),
  'DATE_CLOSED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_DATE_CLOSED',
    'default' => false,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
  'AMOUNT_USDOLLAR' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_AMOUNT',
    'align' => 'right',
    'default' => false,
    'currency_format' => true,
  ),
  'PROBABILITY' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PROBABILITY',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'width' => '5%',
    'label' => 'LBL_MODIFIED',
    'default' => false,
  ),
);
?>
