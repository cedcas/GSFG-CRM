<?php
$module_name = 'GSF_Seminars';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'seminar_mailing_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_SEMINAR_MAILING_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'seminar_mailing_date',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'seminar_number_of_mailers' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_SEMINAR_NUMBER_OF_MAILERS',
        'width' => '10%',
        'default' => true,
        'name' => 'seminar_number_of_mailers',
      ),
      'seminar_dollar_spent' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_SEMINAR_DOLLAR_SPENT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'seminar_dollar_spent',
      ),
      'seminar_mailing_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_SEMINAR_MAILING_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'seminar_mailing_date',
      ),
      'seminar_total_capacity' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_SEMINAR_TOTAL_CAPACITY',
        'width' => '10%',
        'name' => 'seminar_total_capacity',
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
