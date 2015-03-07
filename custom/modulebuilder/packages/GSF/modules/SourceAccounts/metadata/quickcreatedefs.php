<?php
$module_name = 'GSF_SourceAccounts';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'source_account_number',
            'label' => 'LBL_SOURCE_ACCOUNT_NUMBER',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'source_company_product',
            'studio' => 'visible',
            'label' => 'LBL_SOURCE_COMPANY_PRODUCT',
          ),
          1 => 
          array (
            'name' => 'source_anniversary_date',
            'label' => 'LBL_SOURCE_ANNIVERSARY_DATE',
          ),
        ),
      ),
    ),
  ),
);
?>
