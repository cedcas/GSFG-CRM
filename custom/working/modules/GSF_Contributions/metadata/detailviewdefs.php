<?php
$module_name = 'GSF_Contributions';
$_object_name = 'gsf_contributions';
$viewdefs [$module_name] = 
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
          3 => 
          array (
            'customCode' => '<input title="{$APP.LBL_DUP_MERGE}" accesskey="M" class="button" onclick="this.form.return_module.value=\'GSF_Contribution\';this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Step1\'; this.form.module.value=\'MergeRecords\';" name="button" value="{$APP.LBL_DUP_MERGE}" type="submit">',
          ),
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
            'name' => 'date_entered',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'gsf_contribution_amount',
            'comment' => 'Name of the Sale',
            'label' => 'LBL_GSF_CONTRIBUTION_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'gsf_contribution_type',
            'comment' => 'The Sale is of this type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'gsf_contribution_date',
            'label' => 'LBL_GSF_CONTRIBUTION_DATE',
          ),
          1 => 
          array (
            'name' => 'gsf_contribution_repeat_client',
            'label' => 'LBL_GSF_CONTRIBUTION_REPEAT_CLIENT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'nl2br' => true,
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'account_name',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'gsf_seminardetails_name',
          ),
        ),
      ),
    ),
  ),
);
?>
