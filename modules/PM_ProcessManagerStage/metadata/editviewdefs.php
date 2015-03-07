<?php
$module_name = 'PM_ProcessManagerStage';
$viewdefs = array (
$module_name =>
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
    ),
    'panels' => 
    array (
      'DEFAULT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'start_delay_minutes',
            'label' => 'LBL_START_DELAY_MINUTES',
          ),
          1 => 
          array (
            'name' => 'start_delay_hours',
            'label' => 'LBL_START_DELAY_HOURS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'start_delay_days',
            'label' => 'LBL_START_DELAY_DAYS',
          ),
          1 => 
          array (
            'name' => 'start_delay_months',
            'label' => 'LBL_START_DELAY_MONTHS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'start_delay_years',
            'label' => 'LBL_START_DELAY_YEARS',
          ),
          1 => NULL,
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
)
);
?>
