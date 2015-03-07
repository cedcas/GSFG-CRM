<?php
$module_name = 'GSF_Withdrawals';
$_object_name = 'gsf_withdrawals';
$viewdefs [$module_name] = 
array (
  'EditView' => 
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
      'javascript' => '{$PROBABILITY_SCRIPT}',
      'useTabs' => false,
    ),
    'panels' => 
    array (
      'lbl_sale_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'gsf_withdrawal_amount',
            'comment' => 'Name of the Sale',
            'label' => 'LBL_GSF_WITHDRAWAL_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'gsf_withdrawal_type',
            'comment' => 'The Sale is of this type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'gsf_withdrawal_date',
            'label' => 'LBL_GSF_WITHDRAWAL_DATE',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Description of the sale',
            'studio' => 'visible',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'accounts_gsf_withdrawals_name',
          ),
        ),
      ),
    ),
  ),
);
?>
