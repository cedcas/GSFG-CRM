<?php
$module_name = 'GSF_Seminars';
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
            'name' => 'seminar_number_of_mailers',
            'label' => 'LBL_SEMINAR_NUMBER_OF_MAILERS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'seminar_mailing_date',
            'label' => 'LBL_SEMINAR_MAILING_DATE',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
?>
