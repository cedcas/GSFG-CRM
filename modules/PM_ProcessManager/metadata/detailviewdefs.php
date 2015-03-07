<?php
$module_name = 'PM_ProcessManager';
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
          array('customCode'=>'<input title="{$MOD.LBL_DELETE_PROCESS_FILTER_ENTRIES}" accessKey="{$APP.LBL_MAILMERGE_KEY}" class="button" onclick="this.form.return_module.value=\'PM_ProcessManager\'; this.form.return_action.value=\'DetailView\';this.form.action.value=\'DeleteProcessFilterEntries\'" type="submit" name="button" value="{$MOD.LBL_DELETE_PROCESS_FILTER_ENTRIES}">'),
        ),
      ),
      'maxColumns' => '3',
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
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
         2 => 
          array (
            '' => '',
            '' => '',
          ),  
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'process_object',
            'label' => 'LBL_PROCESS_OBJECT',
          ),
          1 => 
          array (
            'name' => 'start_event',
            'label' => 'LBL_START_EVENT',
          ),
         2 => 
          array (
            '' => '',
            '' => '',
          ),    
        ),

        2=> 
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
         2 => 
          array (
            '' => '',
            '' => '',
          ),        
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),

      'Object Filter Field Values' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_field1',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD1',
          ),
          1 => 
          array (
            'name' => 'detail_view_operator1',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'detail_view_value1',
            'label' => 'LBL_PROCESS_OBJECT_FIELD1_VALUE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_field2',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD2',
          ),
          1 => 
          array (
            'name' => 'detail_view_operator2',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'detail_view_value2',
            'label' => 'LBL_PROCESS_OBJECT_FIELD2_VALUE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_field3',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD3',
          ),
          1 => 
          array (
            'name' => 'detail_view_operator3',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'detail_view_value3',
            'label' => 'LBL_PROCESS_OBJECT_FIELD3_VALUE',
          ),
        ),
         3 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_field4',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD4',
          ),
          1 => 
          array (
            'name' => 'detail_view_operator4',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'detail_view_value4',
            'label' => 'LBL_PROCESS_OBJECT_FIELD4_VALUE',
          ),
        ),
         4 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_field5',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD5',
          ),
          1 => 
          array (
            'name' => 'detail_view_operator5',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'detail_view_value5',
            'label' => 'LBL_PROCESS_OBJECT_FIELD5_VALUE',
          ),
        ),   
          5 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_andorfilterfields',
            'label' => 'LBL_PROCESS_OBJECT_AND_OR_FIELD',
          ),
          1 => 
          array (
            'name' => '',
            'label' => '',
          ),
          2 => 
          array (
            'name' => '',
            'label' => '',
          ),
        ),                                     
      ),
      
 


      'Process Cancel Information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'cancel_on_event',
            'label' => 'LBL_CANCEL_ON_EVENT',
          ),
          1 => 
          array (
            'name' => '',
            'label' => '',
          ),
          2 => 
          array (
            'name' => '',
            'label' => '',
          ),
        ),
          1 => 
        array (
          0 => 
          array (
            'name' => 'detail_view_process_object_cancel_field',
            'label' => 'LBL_PROCESS_OBJECT_CANCEL_FIELD',
          ),
          1 => 
          array (
            'name' => 'detail_view_process_object_cancel_field_operator',
            'label' => 'LBL_PROCESS_OBJECT_CANCEL_FIELD_OPERATOR',
          ),
          2 => 
          array (
            'name' => 'detail_view_process_object_cancel_field_value',
            'label' => 'LBL_PROCESS_OBJECT_CANCEL_FIELD_VALUE',
          ),
        ),                             
      ),      
      
    ),
  ),
)
);
?>
