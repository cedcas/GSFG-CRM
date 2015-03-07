<?php
$module_name = 'GSF_SourceAccounts';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'SOURCE_COMPANY_PRODUCT' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SOURCE_COMPANY_PRODUCT',
    'sortable' => false,
    'width' => '20%',
  ),
  'SOURCE_ACCOUNT_NUMBER' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SOURCE_ACCOUNT_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'SOURCE_ANNIVERSARY_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_SOURCE_ANNIVERSARY_DATE',
    'width' => '20%',
    'default' => true,
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => 'created_by_link',
    'label' => 'LBL_CREATED',
    'width' => '20%',
    'default' => true,
  ),
  'SOURCE_TRANSFER' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_SOURCE_TRANSFER',
    'width' => '10%',
    'default' => false,
  ),
  'SOURCE_TAX_STATUS' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_SOURCE_TAX_STATUS',
    'sortable' => false,
    'width' => '10%',
  ),
  'SOURCE_TRANSFER_PAPERWORK_MAIL' => 
  array (
    'type' => 'date',
    'label' => 'LBL_SOURCE_TRANSFER_PAPERWORK_MAIL',
    'width' => '10%',
    'default' => false,
  ),
  'SOURCE_CASH_PERSONAL_CHECK' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_SOURCE_CASH_PERSONAL_CHECK',
    'width' => '10%',
    'default' => false,
  ),
  'SOURCE_AGENT_ORDERED_FUNDS' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_SOURCE_AGENT_ORDERED_FUNDS',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => 'modified_user_link',
    'label' => 'LBL_MODIFIED_NAME',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
);
?>
