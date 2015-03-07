<?php
$module_name = 'GSF_SeminarDetails';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'MEETING_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MEETING_ID',
    'width' => '10%',
    'default' => true,
  ),
  'GSF_VENUES_GSF_SEMINARDETAILS_NAME' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_GSF_VENUES_GSF_SEMINARDETAILS_FROM_GSF_VENUES_TITLE',
    'width' => '20%',
    'default' => true,
    'link' => false,
  ),
  'DETAILS_FROM_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DETAILS_FROM_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'DETAILS_VENUE_ADDRESS1' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_VENUE_ADDRESS1',
    'width' => '15%',
    'default' => true,
  ),
  'DETAILS_VENUE_CITY' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_VENUE_CITY',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => 'assigned_user_link',
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => 'modified_user_link',
    'label' => 'LBL_MODIFIED_NAME',
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_AMOUNT_PER_PERSON' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_DETAILS_AMOUNT_PER_PERSON',
    'currency_format' => true,
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_TOTAL_AMOUNT' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_DETAILS_TOTAL_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_FROM_TIME' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_DETAILS_FROM_TIME',
    'sortable' => false,
    'width' => '10%',
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => 'created_by_link',
    'label' => 'LBL_CREATED',
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_REGISTERED' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_REGISTERED',
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_APPOINTMENT_SETS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_APPOINTMENT_SETS',
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_TO_TIME' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_DETAILS_TO_TIME',
    'sortable' => false,
    'width' => '10%',
  ),
  'DETAILS_VENUE_POSTALCODE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_VENUE_POSTALCODE',
    'width' => '5%',
    'default' => false,
  ),
  'DETAILS_BUTS_IN_SITS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_BUTS_IN_SITS',
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_BLUE_SHEETS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_BLUE_SHEETS',
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_BUYING_UNITS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_BUYING_UNITS',
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_TO_DATETIME' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_DETAILS_TO_DATETIME',
    'width' => '10%',
    'default' => false,
  ),
  'DETAILS_VENUE_STATE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_VENUE_STATE',
    'width' => '5%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '20%',
    'default' => false,
  ),
  'DETAILS_CAPACITY' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_DETAILS_CAPACITY',
    'width' => '20%',
    'default' => false,
  ),
);
?>
