<?php
$module_name = 'GSF_Seminars';
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
            'name' => 'seminar_total_capacity',
            'label' => 'LBL_SEMINAR_TOTAL_CAPACITY',
            'type' => 'readonly',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'seminar_mailing_date',
            'label' => 'LBL_SEMINAR_MAILING_DATE',
          ),
          1 => 
          array (
            'name' => 'seminar_dollar_spent',
            'label' => 'LBL_SEMINAR_DOLLAR_SPENT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'seminars_income_narrow_ranges',
            'label' => 'LBL_SEMINARS_INCOME_NARROW_RANGES',
          ),
          1 => 
          array (
            'name' => 'seminars_age',
            'label' => 'LBL_SEMINARS_AGE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'seminar_number_of_mailers',
            'label' => 'LBL_SEMINAR_NUMBER_OF_MAILERS',
          ),
          1 => 
          array (
            'name' => 'seminars_homeowner',
            'label' => 'LBL_SEMINARS_HOMEOWNER',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'seminar_zipcodes_for_mailers',
            'studio' => 'visible',
            'label' => 'LBL_SEMINAR_ZIPCODES_FOR_MAILERS',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'studio' => 'visible',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
?>
