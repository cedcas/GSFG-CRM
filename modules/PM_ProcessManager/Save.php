<?php
require_once('include/formbase.php');
require_once('modules/PM_ProcessManager/PM_ProcessManager.php');
require_once('log4php/LoggerManager.php');
require_once('data/SugarBean.php');
global $current_user;
$user_id = $current_user->id;
$processFilterFieldArray = array();
$processFilterListArray = array();
$processFilterFieldValueArray = array();
$thisSugarBean = new SugarBean();
$focus = new PM_ProcessManager();
$focus->retrieve($_POST['record']);
$return_val = array();
//In this custom save we need to pick up the following fields and add after the sugar save

$name =($_POST['name']);
$status =($_POST['status']);
$description =($_POST['description']);
$focus->name = $name;
$focus->status = $status;
$processObject =($_POST['process_object']);
$startEvent = ($_POST['start_event']);
$processOjectCancelEvent =($_POST['cancel_on_event']);
$processObjectCancelField =($_POST['process_object_cancel_field']);
$processObjectCancelFieldValue =($_POST['process_object_cancel_field_value']);
$processObjectCancelFieldOperator =($_POST['process_object_cancel_field_operator']);
$focus->save();

if ($processObjectCancelField == 'Please') {
	$processObjectCancelField = 'N/A';
}

//Now update the record with the name
//Now see if there any filter table entries and if so then add
$return_id = $focus->id;
$userId = 
$queryUpdate = "Update pm_processmanager set name = '$name', process_object = '$processObject', start_event = '$startEvent', description = '$description', "; 
$queryUpdate .= " cancel_on_event = '$processOjectCancelEvent', process_object_cancel_field = '$processObjectCancelField', "; 
$queryUpdate .= " process_object_cancel_field_value = '$processObjectCancelFieldValue', process_object_cancel_field_operator = '$processObjectCancelFieldOperator' , assigned_user_id = '$user_id' where id = '$return_id'";

$thisSugarBean->db->query($queryUpdate, true);
//TODO - Figure out if we need cancel on field name
$cancel_on_field_name = $_POST["cancel_on_field_name"];
$cancel_on_field_value = $_POST["cancel_on_field_value"];
//If either of these two fields are not blank then we have a process for change lead status or change sales stage
//For each of the 5 filter field/value combos we insert into the filter table
$andOrFilter = $_POST['andorfilterfield'];
for ($i=1; $i<6; $i++){
	$processFilterField = "process_filter_field" .$i;
	$processFilterList = "filter_list" .$i;
	$processFilterFieldValue = "process_object_field" .$i ."_value";
		
	$process_object_field = $_POST["$processFilterField"];
	$process_filter_list = $_POST["$processFilterList"];
	$process_object_field_value = $_POST["$processFilterFieldValue"];
		
	if ($process_object_field_value != "") {
		$processFilterFieldArray[$i] = $process_object_field;
		$processFilterListArray[$i] = $process_filter_list;
		$processFilterFieldValueArray[$i] = $process_object_field_value;
	}
}

//Now check to see if the arrays have any values
//if so build the insert for the process filter table entry
if(count($processFilterFieldArray) > 0){
	$countOfFilterValues = count($processFilterFieldArray);
	for ($i = 1; $i <= $countOfFilterValues; $i++ ){
		$procss_object_field = $processFilterFieldArray[$i];
		$process_filter_list = $processFilterListArray[$i];
		$proces_object_field_value = $processFilterFieldValueArray[$i];
				//As long as procee object field is not null or blank then insert
		if ($proces_object_field_value != "") {
			//Need to know if we are inserting a new entry or updating an existing one
				$processFilterTableId = checkIfProcessFilterTableExists($return_id,$i,$thisSugarBean);
				$lcrmProcessFilterTableId = create_guid();
				//convert less than greater than
				if($process_filter_list == '&lt;'){
					$process_filter_list = '<';
				}
				if($process_filter_list == '&gt;'){
					$process_filter_list = '>';
				}				
				$query_insert = "Insert into pm_process_filter_table set id = '" .$lcrmProcessFilterTableId ."'";
				$query_insert .= ", process_id = '" .$focus->id ."', field_name = '" .$procss_object_field ."', field_value = '" .$proces_object_field_value ."'";
				$query_insert .= ",  field_operator = '$process_filter_list', sequence = $i, andorfilterfields = '$andOrFilter' ";
			
				
				$query_update = "Update pm_process_filter_table set  field_name = '" .$procss_object_field ."', field_value = '" .$proces_object_field_value ."'" ;
				$query_update .= ", field_operator = '$process_filter_list', andorfilterfields = '$andOrFilter' ";
				$query_update .= " where id = '" .$processFilterTableId ."' and sequence = $i";											
				if ($processFilterTableId != '') {
					$focus->db->query($query_update, true);
				}
				else{
					$focus->db->query($query_insert, true);
				}
		}

	}
	
}

//Now chech to see if this is a custom module and if so make sure the logic hook file is present
	if (file_exists('custom/application/Ext/Include/modules.ext.php'))
	{

		include('custom/application/Ext/Include/modules.ext.php');
   		foreach ($beanFiles as $key=>$value){
 		//Now make sure the custom module has an entry for logic hooks 
 		//Parse the file looking for custom modules - 
 			//Now go and get the table name
 			require_once($value);
 			$customModule = new $key;
 			$customModuleTable = $customModule->table_name;
 			if ($processObject == $customModuleTable) {
 				$modulesDir = $customModule->module_dir;
 					if (!file_exists("custom/modules/$modulesDir/logic_hooks.php"))
					{
						//Copy the logic hooks file from 
						$directory = "custom/modules/$modulesDir/logic_hooks.php";
						copyLogicHooksFile($directory);
					}
					else{
						//Check to see if the logic hook for process manager is in place
						$directory = "custom/modules/$modulesDir/logic_hooks.php";
						$fileContents = file_get_contents($directory);
    						if (strstr($fileContents,"insertIntoPmEntryTable") === false) {
    							//Add the logic hook
    							addLogicHooksFile($directory,$fileContents);
    						}
					}
 			}
 			
    	}		
	}

//Now redirect to Edit View Page
handleRedirect($return_id, "PM_ProcessManager");
//Check to see if the entry in the process filter table already exists

function checkIfProcessFilterTableExists($processId,$sequence,$thisSugarBean){
	$processFilterTableId = '';
	$query = "Select id from pm_process_filter_table where process_id = '$processId' and sequence = $sequence";
	$result = $thisSugarBean->db->query($query, true);
	while($rowProcessFilterTable = $thisSugarBean->db->fetchByAssoc($result))
		{	
			$processFilterTableId = $rowProcessFilterTable['id'];	
		}

	return $processFilterTableId;
}

function copyLogicHooksFile($directory){
	$logicHooksFile = "<?php
	// Do not store anything in this file that is not part of the array or the hook version.  This file will	
	// be automatically rebuilt in the future. 
 	\$hook_version = 1; 
	\$hook_array = Array(); 
	// position, file, function 
	\$hook_array['after_save'] = Array(); 
	\$hook_array['after_save'][] = Array(1, 'INSERT_INTO_PM_ENTRY_TABLE', 'modules/PM_ProcessManager/insertIntoPmEntryTable.php','insertIntoPmEntryTable', 'setPmEntryTable'); 

	\$hook_array['after_delete'] = Array(); 
	\$hook_array['after_delete'][] = Array(1, 'INSERT_INTO_PM_ENTRY_TABLE', 'modules/PM_ProcessManager/insertIntoPmEntryTable.php','insertIntoPmEntryTable', 'setPmEntryTable'); 
?>";
	$fp = fopen($directory,"a+");
	fwrite($fp,$logicHooksFile);
	fclose($fp);
}

function addLogicHooksFile($directory,$fileContents){
	//Get the length of the current file contents
	$logicHooksFile = "
	// Do not store anything in this file that is not part of the array or the hook version.  This file will	
	// be automatically rebuilt in the future. 
 	\$hook_version = 1; 
	\$hook_array = Array(); 
	// position, file, function 
	\$hook_array['after_save'] = Array(); 
	\$hook_array['after_save'][] = Array(1, 'INSERT_INTO_PM_ENTRY_TABLE', 'modules/PM_ProcessManager/insertIntoPmEntryTable.php','insertIntoPmEntryTable', 'setPmEntryTable'); 
	\$hook_array['after_delete'] = Array(); 
	\$hook_array['after_delete'][] = Array(1, 'INSERT_INTO_PM_ENTRY_TABLE', 'modules/PM_ProcessManager/insertIntoPmEntryTable.php','insertIntoPmEntryTable', 'setPmEntryTable'); 
?>";	

	//Add the entry for Process Manager to the End of the File
		$fp = fopen($directory, 'r+');
		$str = "";
		while(1)
			{
				//read line
				$line = fgets($fp);
				//if end of file reached then stop reading anymore
				if($line == null)break;
				
				//Do first replacement
				if(preg_match("/\?>/", $line))
				{
					$new_line = preg_replace("/\?>/",$logicHooksFile,$line);
					$str .= $new_line;
				}
				else
				{
					//set file content to a string
					$str .= $line;
				}
				
			}
	rewind($fp);
	fclose($fp);		
	$handle = fopen($directory, 'w');
	fwrite($handle, $str);		
	fclose($handle); 
}




?>