<?php
$module_name = 'PM_ProcessManagerStage';
$viewdefs = array (
$module_name =>
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
        ),
      ),
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
      '' => 
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
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'PANEL 1' => 
      array (
        0 => 
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
        1 => 
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
        2 => 
        array (
          0 => 
          array (
            'name' => 'start_delay_years',
            'label' => 'LBL_START_DELAY_YEARS',
          ),
          1 => NULL,
        ),
      ),
    ),
  ),
)
);
?>
