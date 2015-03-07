<?php
// created: 2012-09-21 14:42:59
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '10%',
    'default' => true,
  ),
  'gsf_venues_gsf_seminardetails_name' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'vname' => 'LBL_DETAILS_VENUE',
    'width' => '15%',
    'default' => true,
  ),
  'details_venue_address1' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_DETAILS_VENUE_ADDRESS1',
    'width' => '10%',
    'default' => true,
  ),
  'details_venue_city' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_DETAILS_VENUE_CITY',
    'width' => '10%',
    'default' => true,
  ),
  'details_venue_postalcode' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_DETAILS_VENUE_POSTALCODE',
    'width' => '10%',
    'default' => true,
  ),
  'details_from_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_DETAILS_FROM_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'details_from_time' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_DETAILS_FROM_TIME',
    'sortable' => false,
    'width' => '10%',
  ),
  'details_capacity' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_CAPACITY',
    'width' => '10%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'widget_class' => 'SubPanelEditButton',
    'module' => 'GSF_SeminarDetails',
    'width' => '4%',
    'default' => true,
  ),
  'details_registered' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_DETAILS_REGISTERED',
    'width' => '10%',
    'default' => true,
  ),
);
?>
