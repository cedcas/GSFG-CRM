<?php
$listViewDefs ['Accounts'] = 
array (
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_ACCOUNT_NAME',
    'link' => true,
    'default' => true,
  ),
  'ACCOUNTS_COMPANY_PRODUCT_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_ACCOUNTS_COMPANY_PRODUCT',
    'sortable' => false,
    'width' => '20%',
  ),
  'ACCOUNTS_ACCOUNT_NUMBER_C' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_ACCOUNTS_ACCOUNT_NUMBER',
    'width' => '10%',
  ),
  'ACCOUNTS_ANNIVERSARY_DATE_C' => 
  array (
    'type' => 'date',
    'default' => true,
    'label' => 'LBL_ACCOUNTS_ANNIVERSARY_DATE',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'ACCOUNTS_TOTAL_PREMIUM_C' => 
  array (
    'width' => '10%',
    'label' => 'LBL_ACCOUNTS_TOTAL_PREMIUM',
    'default' => true,
  ),
  'DATE_MODIFIED' => 
  array (
    'width' => '5%',
    'label' => 'LBL_DATE_MODIFIED',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '5%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CREATED',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MODIFIED',
    'default' => false,
  ),
  'ACCOUNTS_TAX_STATUS_C' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_ACCOUNTS_TAX_STATUS',
    'sortable' => false,
    'width' => '10%',
  ),
  'ACCOUNTS_OTHER_DESCRIPTION_C' => 
  array (
    'type' => 'text',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_ACCOUNTS_OTHER_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
  ),
  'ACCOUNTS_REPEAT_CLIENT_C' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_ACCOUNTS_REPEAT_CLIENT',
    'width' => '10%',
  ),
  'ACCOUNTS_PROJECTED_AMOUNT_C' => 
  array (
    'type' => 'currency',
    'default' => false,
    'label' => 'LBL_ACCOUNTS_PROJECTED_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
  ),
  'ACCOUNTS_TRACKING_NUMBER_C' => 
  array (
    'type' => 'varchar',
    'default' => false,
    'label' => 'LBL_ACCOUNTS_TRACKING_NUMBER',
    'width' => '10%',
  ),
  'ACCOUNTS_APPLICATION_RECEIVE_C' => 
  array (
    'type' => 'date',
    'default' => false,
    'label' => 'LBL_ACCOUNTS_APPLICATION_RECEIVE',
    'width' => '10%',
  ),
  'ACCOUNTS_APPLICATION_MAILED__C' => 
  array (
    'type' => 'date',
    'default' => false,
    'label' => 'LBL_ACCOUNTS_APPLICATION_MAILED_',
    'width' => '10%',
  ),
  'LEAD_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LEAD_NAME',
    'module' => 'Leads',
    'id' => 'LEAD_ID',
    'default' => false,
  ),
  'FOLLOWUPDATE_C' => 
  array (
    'type' => 'date',
    'default' => false,
    'label' => 'LBL_FOLLOWUPDATE',
    'width' => '10%',
  ),
);
?>
