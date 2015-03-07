<?php
$module_name = 'GSF_Contributions';
$_object_name = 'gsf_contributions';
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
            'name' => 'gsf_contribution_amount',
            'label' => 'LBL_GSF_CONTRIBUTION_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'gsf_contribution_type',
            'comment' => 'The Sale is of this type',
            'label' => 'LBL_TYPE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'gsf_contribution_date',
            'label' => 'LBL_GSF_CONTRIBUTION_DATE',
          ),
          1 => 
          array (
            'name' => 'gsf_contribution_repeat_client',
            'label' => 'LBL_GSF_CONTRIBUTION_REPEAT_CLIENT',
          ),
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
      ),
    ),
  ),
);
?>
