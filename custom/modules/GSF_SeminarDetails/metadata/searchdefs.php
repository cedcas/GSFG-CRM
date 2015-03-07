<?php
$module_name = 'GSF_SeminarDetails';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'details_from_datetime' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_DETAILS_FROM_DATETIME',
        'width' => '10%',
        'default' => true,
        'name' => 'details_from_datetime',
      ),
    ),
    'advanced_search' => 
    array (
      'details_venue' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_DETAILS_VENUE',
        'width' => '10%',
        'default' => true,
        'name' => 'details_venue',
      ),
      'details_from_datetime' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_DETAILS_FROM_DATETIME',
        'width' => '10%',
        'default' => true,
        'name' => 'details_from_datetime',
      ),
      'details_to_datetime' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_DETAILS_TO_DATETIME',
        'width' => '10%',
        'default' => true,
        'name' => 'details_to_datetime',
      ),
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'details_amount_per_person' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_DETAILS_AMOUNT_PER_PERSON',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'details_amount_per_person',
      ),
      'details_total_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_DETAILS_TOTAL_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'details_total_amount',
      ),
      'details_registered' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_DETAILS_REGISTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'details_registered',
      ),
      'details_buts_in_sits' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_DETAILS_BUTS_IN_SITS',
        'width' => '10%',
        'default' => true,
        'name' => 'details_buts_in_sits',
      ),
      'details_blue_sheets' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_DETAILS_BLUE_SHEETS',
        'width' => '10%',
        'default' => true,
        'name' => 'details_blue_sheets',
      ),
      'details_buying_units' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_DETAILS_BUYING_UNITS',
        'width' => '10%',
        'default' => true,
        'name' => 'details_buying_units',
      ),
      'details_appointment_sets' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_DETAILS_APPOINTMENT_SETS',
        'width' => '10%',
        'default' => true,
        'name' => 'details_appointment_sets',
      ),
      'assigned_user_name' => 
      array (
        'link' => 'assigned_user_link',
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'width' => '10%',
        'default' => true,
        'name' => 'assigned_user_name',
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
