<?php
$module_name = 'GSF_SeminarDetails';
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
            'name' => 'details_venue',
            'studio' => 'visible',
            'label' => 'LBL_DETAILS_VENUE',
          ),
          1 => 
          array (
            'name' => 'details_from_datetime',
            'label' => 'LBL_DETAILS_FROM_DATETIME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
?>
