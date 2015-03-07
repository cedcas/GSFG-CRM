<?php
$module_name = 'GSF_SourceAccounts';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'source_account_number' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_SOURCE_ACCOUNT_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'source_account_number',
      ),
      'projected_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_PROJECTED_AMOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'projected_amount',
      ),
      'source_tax_status' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_SOURCE_TAX_STATUS',
        'sortable' => false,
        'width' => '10%',
        'name' => 'source_tax_status',
      ),
      'source_agent_ordered_funds' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_SOURCE_AGENT_ORDERED_FUNDS',
        'width' => '10%',
        'default' => true,
        'name' => 'source_agent_ordered_funds',
      ),
      'source_transfer' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_SOURCE_TRANSFER',
        'width' => '10%',
        'default' => true,
        'name' => 'source_transfer',
      ),
      'source_cash_personal_check' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_SOURCE_CASH_PERSONAL_CHECK',
        'width' => '10%',
        'default' => true,
        'name' => 'source_cash_personal_check',
      ),
      'source_transfer_paperwork_mail' => 
      array (
        'type' => 'date',
        'label' => 'LBL_SOURCE_TRANSFER_PAPERWORK_MAIL',
        'width' => '10%',
        'default' => true,
        'name' => 'source_transfer_paperwork_mail',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>
