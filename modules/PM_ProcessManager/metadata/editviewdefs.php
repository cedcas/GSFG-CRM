<?php
$module_name = 'PM_ProcessManager';
$viewdefs = array (
$module_name =>
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
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
        2 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
'javascript' => '<script type="text/javascript" src="include/javascript/popup_parent_helper.js?s={$SUGAR_VERSION}&c={$JS_CUSTOM_VERSION}"></script>
<script type="text/javascript">
document.getElementById("DIV_INFO").style.display="none";
var YUC = YAHOO.util.Connect;
function type_change() {ldelim}
var objectType = document.forms[\'EditView\'].process_object.options[document.forms[\'EditView\'].process_object.selectedIndex].text;
	var gURL = "getFilterFields.php?table=" + objectType;
	YUC.successEvent.subscribe(handleEvents.success, handleEvents);
    var callback = {ldelim}
        success: function(o) {ldelim}
            document.getElementById("DIV_INFO").innerHTML = o.responseText;       
        {rdelim}
    {rdelim} 
    var connectionObject = YUC.asyncRequest ("GET", gURL, callback);				
 {rdelim}

var handleEvents = {ldelim}
	start: function(eventType, args){ldelim}

    {rdelim},
 
	complete: function(eventType, args) {ldelim}
	{rdelim},
 
	success: function(eventType, args) {ldelim}
	load_filter_fields();
	{rdelim},
 
	failure: function(eventType, args) {ldelim}

	{rdelim},
 

	upload: function(eventType, args){ldelim}

	{rdelim},
 
	abort: function(eventType, args) {ldelim}

	{rdelim}
{rdelim}; 

function load_filter_fields() {ldelim}

//var objectType = document.forms[\'EditView\'].process_object.options[document.forms[\'EditView\'].process_object.selectedIndex].text;
var finalobjectType = "object_fields";
var elementid;
elementid = finalobjectType;
var lstToCopyFromArray = document.getElementsByName(elementid); 
var lstToCopyFrom = lstToCopyFromArray[0];
//Now setup all 5 filter lists vars
var lstToCopyToArray = document.getElementsByName("process_filter_field1"); 
var lstToCopyTo = lstToCopyToArray[0];
var lstToCopyToArray2 = document.getElementsByName("process_filter_field2"); 
var lstToCopyTo2 = lstToCopyToArray2[0];
var lstToCopyToArray3 = document.getElementsByName("process_filter_field3"); 
var lstToCopyTo3 = lstToCopyToArray3[0];
var lstToCopyToArray4 = document.getElementsByName("process_filter_field4"); 
var lstToCopyTo4 = lstToCopyToArray4[0];
var lstToCopyToArray5 = document.getElementsByName("process_filter_field5"); 
var lstToCopyTo5 = lstToCopyToArray5[0];

//Now the cancel list
var lstToCopyToCancelArray = document.getElementsByName("process_object_cancel_field"); 
var lstToCopyToCancel = lstToCopyToCancelArray[0];

var myTextField = document.getElementById(finalobjectType);
//Get the current values if this is an edit
var currentFilterFilter1 = document.forms[\'EditView\'].process_filter_field1.options[document.forms[\'EditView\'].process_filter_field1.selectedIndex].text;
var currentFilterFilter2 = document.forms[\'EditView\'].process_filter_field2.options[document.forms[\'EditView\'].process_filter_field2.selectedIndex].text;
var currentFilterFilter3 = document.forms[\'EditView\'].process_filter_field3.options[document.forms[\'EditView\'].process_filter_field3.selectedIndex].text;
var currentFilterFilter4 = document.forms[\'EditView\'].process_filter_field4.options[document.forms[\'EditView\'].process_filter_field4.selectedIndex].text;
var currentFilterFilter5 = document.forms[\'EditView\'].process_filter_field5.options[document.forms[\'EditView\'].process_filter_field5.selectedIndex].text;
var currentFilterFilterCancel = document.forms[\'EditView\'].process_object_cancel_field.options[document.forms[\'EditView\'].process_object_cancel_field.selectedIndex].text;

//First clear out the select list for all the lists
//Filter List 1 - only fill the list if it is currently Please Specify
//First clear out the select list for all the lists
	 	for (x = lstToCopyTo.length; x >= 0; x--)
			{ldelim}
				lstToCopyTo[x] = null;
	 		{rdelim}	 	
//Filter List 2 
	 	for (x = lstToCopyTo2.length; x >= 0; x--)
			{ldelim}
				lstToCopyTo2[x] = null;
	 		{rdelim}	
//Filter List 3	
	 	for (x = lstToCopyTo3.length; x >= 0; x--)
			{ldelim}
				lstToCopyTo3[x] = null;
	 		{rdelim}	
//Filter List 4	
	 	for (x = lstToCopyTo4.length; x >= 0; x--)
			{ldelim}
				lstToCopyTo4[x] = null;
	 		{rdelim}	
//Filter List 5	
	 	for (x = lstToCopyTo5.length; x >= 0; x--)
			{ldelim}
				lstToCopyTo5[x] = null;
	 		{rdelim} 
//Filter List Cancel	
	 	for (x = lstToCopyToCancel.length; x >= 0; x--)
			{ldelim}
				lstToCopyToCancel[x] = null;
	 		{rdelim}		 	 	
//Now fill list 1 - first fill all the lists with Please Specify

lstToCopyTo.options.add(new Option(\'Please Specify\',\'Please Specify\')); 
lstToCopyTo2.options.add(new Option(\'Please Specify\',\'Please Specify\')); 
lstToCopyTo3.options.add(new Option(\'Please Specify\',\'Please Specify\')); 
lstToCopyTo4.options.add(new Option(\'Please Specify\',\'Please Specify\')); 
lstToCopyTo5.options.add(new Option(\'Please Specify\',\'Please Specify\')); 

for (i=0;i<lstToCopyFrom.options.length;i++)
		{ldelim}
		var listValue = lstToCopyFrom.options[i].text; 
		 lstToCopyTo.options.add(new Option(listValue,listValue));
 	{rdelim}
//Now fill list 2
for (i=0;i<lstToCopyFrom.options.length;i++)
		{ldelim}
		var listValue = lstToCopyFrom.options[i].text; 
		 lstToCopyTo2.options.add(new Option(listValue,listValue));
 	{rdelim}
//Now fill list 3
for (i=0;i<lstToCopyFrom.options.length;i++)
		{ldelim}
		var listValue = lstToCopyFrom.options[i].text; 
		 lstToCopyTo3.options.add(new Option(listValue,listValue));
 	{rdelim}
 //Now fill list 4
for (i=0;i<lstToCopyFrom.options.length;i++)
		{ldelim}
		var listValue = lstToCopyFrom.options[i].text; 
		 lstToCopyTo4.options.add(new Option(listValue,listValue));
 	{rdelim}
  //Now fill list 5
for (i=0;i<lstToCopyFrom.options.length;i++)
		{ldelim}
		var listValue = lstToCopyFrom.options[i].text; 
		 lstToCopyTo5.options.add(new Option(listValue,listValue));
 	{rdelim}
  //Now fill list cancel
for (i=0;i<lstToCopyFrom.options.length;i++)
		{ldelim}
		var listValue = lstToCopyFrom.options[i].text; 
		 lstToCopyToCancel.options.add(new Option(listValue,listValue));
 	{rdelim} 					
{rdelim};
 
</script>',
    ),
    'panels' => 
    array (
      'DEFAULT' => 
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
          2 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'process_object',
            'label' => 'LBL_PROCESS_OBJECT',       
           'displayParams' => 
            array (
              'required' => true,
              'javascript' => 'onchange="type_change();"',
            ),
          ),
          1 => 
          array (
            'name' => 'start_event',
            'label' => 'LBL_START_EVENT',
          ),
          2 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
          1 => NULL,
          2 => NULL,
        ),
      ),
      'PROCESS FILTER FIELDS' => 
      array (
       0 => 
        array (
          0 => 
          array (
            'name' => 'process_filter_field1',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD1',
          ),
          1 => 
          array (
            'name' => 'filter_list1',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'process_object_field1_value',
            'label' => 'LBL_PROCESS_OBJECT_FIELD1_VALUE',
          ),
        ),
      1 =>
        array (
          0 => 
          array (
            'name' => 'process_filter_field2',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD2',
          ),
          1 => 
          array (
            'name' => 'filter_list2',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'process_object_field2_value',
            'label' => 'LBL_PROCESS_OBJECT_FIELD2_VALUE',
          ),
        ),
      2 =>
        array (
          0 => 
          array (
            'name' => 'process_filter_field3',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD3',
          ),
          1 => 
          array (
            'name' => 'filter_list3',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'process_object_field3_value',
            'label' => 'LBL_PROCESS_OBJECT_FIELD3_VALUE',
          ),
        ), 
      3 =>
        array (
          0 => 
          array (
            'name' => 'process_filter_field4',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD4',
          ),
          1 => 
          array (
            'name' => 'filter_list4',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'process_object_field4_value',
            'label' => 'LBL_PROCESS_OBJECT_FIELD4_VALUE',
          ),
        ),
      4 =>
        array (
          0 => 
          array (
            'name' => 'process_filter_field5',
            'label' => 'LBL_PROCESS_OBJECT_FILTER_FIELD5',
          ),
          1 => 
          array (
            'name' => 'filter_list5',
            'label' => 'LBL_CHOOSE_FILTER1',
          ),
          2 => 
          array (
            'name' => 'process_object_field5_value',
            'label' => 'LBL_PROCESS_OBJECT_FIELD5_VALUE',
          ),
        ),
      5 =>
        array (
          0 => 
          array (
            'name' => 'andorfilterfield',
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
       
      'PROCESS CANCEL EVENT' => 
      array (
       1 => 
        array (
          0 => 
          array (
            'name' => 'cancel_on_event',
            'label' => 'LBL_CANCEL_ON_EVENT',
          ),

        ),
      ),
      'PROCESS CANCEL FILTER-FIELD INFO' => 
      array (
       2 => 
        array (
          0 => 
          array (
            'name' => 'process_object_cancel_field',
            'label' => 'LBL_PROCESS_OBJECT_CANCEL_FIELD',
          ),
          1 => 
          array (
             'name' => 'process_object_cancel_field_operator',
            'label' => 'LBL_PROCESS_OBJECT_CANCEL_FIELD_OPERATOR',
          ),
          2 => 
          array (
             'name' => 'process_object_cancel_field_value',
            'label' => 'LBL_PROCESS_OBJECT_CANCEL_FIELD_VALUE',
          ),
        ),
      ),     
      
      'DIV_INFO' => 
      array (
       1 => 
        array (
          0 => 
          array (
            'name' => 'object_fields',
            'label' => 'LBL_PROJECT_FIELDS',
          ),

        ),
      ),
      
    ),
  ),
)
);
?>
