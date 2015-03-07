<?php
$searchdefs ['Accounts'] = 
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
      'accounts_anniversary_date_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_ACCOUNTS_ANNIVERSARY_DATE',
        'width' => '10%',
        'name' => 'accounts_anniversary_date_c',
      ),
      'accounts_tracking_number_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ACCOUNTS_TRACKING_NUMBER',
        'sortable' => false,
        'width' => '10%',
        'name' => 'accounts_tracking_number_c',
      ),
      'accounts_total_premium_c' => 
      array (
        'type' => 'currency',
        'default' => true,
        'label' => 'LBL_ACCOUNTS_TOTAL_PREMIUM',
        'currency_format' => true,
        'width' => '10%',
        'name' => 'accounts_total_premium_c',
      ),
      'followupdate_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_FOLLOWUPDATE',
        'width' => '10%',
        'name' => 'followupdate_c',
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
      'accounts_company_product_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ACCOUNTS_COMPANY_PRODUCT',
        'sortable' => false,
        'width' => '10%',
        'name' => 'accounts_company_product_c',
      ),
      'accounts_tracking_number_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ACCOUNTS_TRACKING_NUMBER',
        'sortable' => false,
        'width' => '10%',
        'name' => 'accounts_tracking_number_c',
      ),
      'accounts_account_number_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_ACCOUNTS_ACCOUNT_NUMBER',
        'width' => '10%',
        'name' => 'accounts_account_number_c',
      ),
      'accounts_anniversary_date_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_ACCOUNTS_ANNIVERSARY_DATE',
        'width' => '10%',
        'name' => 'accounts_anniversary_date_c',
      ),
      'accounts_total_premium_c' => 
      array (
        'type' => 'currency',
        'default' => true,
        'label' => 'LBL_ACCOUNTS_TOTAL_PREMIUM',
        'currency_format' => true,
        'width' => '10%',
        'name' => 'accounts_total_premium_c',
      ),
      'accounts_tax_status_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ACCOUNTS_TAX_STATUS',
        'sortable' => false,
        'width' => '10%',
        'name' => 'accounts_tax_status_c',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'sortable' => false,
        'width' => '10%',
        'name' => 'status',
      ),
      'accounts_repeat_client_c' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_ACCOUNTS_REPEAT_CLIENT',
        'width' => '10%',
        'name' => 'accounts_repeat_client_c',
      ),
      'lead_name' => 
      array (
        'name' => 'lead_name',
        'type' => 'relate',
        'default' => true,
        'width' => '10%',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'type' => 'enum',
        'label' => 'LBL_ASSIGNED_TO',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'followupdate_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_FOLLOWUPDATE',
        'width' => '10%',
        'name' => 'followupdate_c',
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
