<?php
$module_name = 'GSF_Contributions';
$_module_name = 'gsf_contributions';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'gsf_contribution_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_GSF_CONTRIBUTION_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_contribution_amount',
      ),
      'gsf_contributions_type' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_contributions_type',
      ),
    ),
    'advanced_search' => 
    array (
      'gsf_contribution_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_GSF_CONTRIBUTION_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_contribution_amount',
      ),
      'gsf_contribution_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_contribution_type',
      ),
      'gsf_contribution_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_GSF_CONTRIBUTION_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_contribution_date',
      ),
      'gsf_contribution_repeat_client' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_GSF_CONTRIBUTION_REPEAT_CLIENT',
        'width' => '10%',
        'default' => true,
        'name' => 'gsf_contribution_repeat_client',
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
