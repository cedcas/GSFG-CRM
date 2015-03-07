<?php
$module_name = 'GSF_Seminars';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'SEMINAR_MAILING_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_SEMINAR_MAILING_DATE',
    'width' => '20%',
    'default' => true,
  ),
  'SEMINAR_NUMBER_OF_MAILERS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SEMINAR_NUMBER_OF_MAILERS',
    'width' => '20%',
    'default' => true,
  ),
  'SEMINAR_TOTAL_CAPACITY' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'label' => 'LBL_SEMINAR_TOTAL_CAPACITY',
    'width' => '20%',
  ),
  'SEMINAR_DOLLAR_SPENT' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_SEMINAR_DOLLAR_SPENT',
    'currency_format' => true,
    'width' => '20%',
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
  'SEMINAR_ZIPCODES_FOR_MAILERS' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_SEMINAR_ZIPCODES_FOR_MAILERS',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
);
?>
