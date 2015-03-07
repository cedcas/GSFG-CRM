<?php
$module_name = 'GSF_Withdrawals';
$_module_name = 'gsf_withdrawals';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'gsf_withdrawal_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_GSF_WITHDRAWAL_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_withdrawal_amount',
      ),
      'gsf_withdrawals_type' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_withdrawals_type',
      ),
    ),
    'advanced_search' => 
    array (
      'gsf_withdrawal_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_GSF_WITHDRAWAL_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_withdrawal_amount',
      ),
      'gsf_withdrawal_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_withdrawal_type',
      ),
      'gsf_withdrawal_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_GSF_WITHDRAWAL_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_withdrawal_date',
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
