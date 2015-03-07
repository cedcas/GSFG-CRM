<?php
$module_name = 'GSF_SourceAccounts';
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
            'name' => 'source_tax_status',
            'studio' => 'visible',
            'label' => 'LBL_SOURCE_TAX_STATUS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'accounts_gsf_sourceaccounts_name',
          ),
          1 => 
          array (
            'name' => 'source_agent_ordered_funds',
            'label' => 'LBL_SOURCE_AGENT_ORDERED_FUNDS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'source_account_number',
            'label' => 'LBL_SOURCE_ACCOUNT_NUMBER',
          ),
          1 => 
          array (
            'name' => 'source_transfer',
            'label' => 'LBL_SOURCE_TRANSFER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'source_transfer_paperwork_mail',
            'label' => 'LBL_SOURCE_TRANSFER_PAPERWORK_MAIL',
          ),
          1 => 
          array (
            'name' => 'source_cash_personal_check',
            'label' => 'LBL_SOURCE_CASH_PERSONAL_CHECK',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'projected_amount',
            'label' => 'LBL_PROJECTED_AMOUNT',
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
