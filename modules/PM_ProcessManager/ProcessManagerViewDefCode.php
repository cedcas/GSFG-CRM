<?PHP
//This next function will get the field from the process filter table
//Focus is the Process Manager - so focus id is the id
//field is the field defined in vardefs - detail view
function getDetailViewAndOrField($focus, $field, $value, $view){
	$focusid = $focus->id;
	$queryField = "Select andorfilterfields from pm_process_filter_table where process_id = '$focusid'  ";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList['andorfilterfields'];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = 'N/A';
	}
	return $fields;
}

function getAndOrFilterList($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed
	$fields = '<name="andorfilterfields" id="andorfilterfields"  size="10" multiple="multiple">';
		if ($view != 'DetailView') {	
			require_once('modules/PM_ProcessManager/ProcessManagerUtils.php');
			$processManagerUtil = new ProcessManagerUtils();
			if ($focus->id != "") {

					$andorfileterfield = $processManagerUtil->getFieldFromFilterTable($focus,"andorfilterfields");
					$fields .="<option value=\"$andorfileterfield\">$andorfileterfield</option>";
				
			}
		}	
	$fields .='<option value="and">and</option>
			   <option value="or">or</option>

		 		';
	return $fields;

}

function getFilterList($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed
	$fields = '<name="filterList"  size="10" multiple="multiple">';
		$sequenceID = substr($field,11);
		$filterListName = "filter_list" ."$sequenceID";
		if ($view != 'DetailView') {	
			require_once('modules/PM_ProcessManager/ProcessManagerUtils.php');
			$processManagerUtil = new ProcessManagerUtils();
			if ($focus->id != "") {
				if ($field == 'process_object_cancel_field_operator') {
					$fields .="<option value=\"$value\">$value</option>";
				}
				else{
					//Get the object that the Process is filtering against
					$processObject = $processManagerUtil->getProcessObjectField($focus,"process_object");
					//Now see if we have an existing entry for this sequence		
					$processFilterOperator = $processManagerUtil->getFieldBySequenceID($focus,$sequenceID,'field_operator');
					$fields .="<option value=\"$processFilterOperator\">$processFilterOperator</option>";
				}
			}
		}	
	$fields .='<option value="=">equal to</option>
			   <option value="!=">not equal to</option>
				<option value="<">less than</option>
				<option value=">">greater than</option>
				<option value="contains">contains</option>
				<option value="does not contain">does not contain</option>
		 		';
	return $fields;

}

function getObjectFields($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed
if ($field == 'contacts_fields') {
	$table = 'contacts';
}
if ($field == 'accounts_fields') {
	$table = 'accounts';
}
if ($field == 'bugs_fields') {
	$table = 'bugs';
}
if ($field == 'cases_fields') {
	$table = 'cases';
}
if ($field == 'opportunities_fields') {
	$table = 'opportunities';
}
if ($field == 'leads_fields') {
	$table = 'leads';
}
if ($field == 'project_fields') {
	$table = 'project';
}
if ($field == 'tasks_fields') {
	$table = 'tasks';
}
if ($field == 'process_filter_field1') {
	$table = 'contacts';
}

$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';
$fields .= '<option value=Please Specify>Please Specify</option>';	
$queryFieldList = 'show fields from ' .$table;
$resultFieldList = $focus->db->query($queryFieldList, true);
while($rowFieldList = $focus->db->fetchByAssoc($resultFieldList)){
		$fieldName = $rowFieldList['Field'];
		$fields .= '<option value="'.$fieldName .'">'.$fieldName .'</option>';
}
//Now go and see if there are any custom fields for the given module
$customTable = $table .'_cstm';
//get the database name 
	global $sugar_config;
$dbname=$sugar_config['dbconfig']['db_name'];
//If we are on windows then we need to set the dbname to lowercase for mysql on windows
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $dbname = strtolower($dbname);
} 

$columnName = 'Tables_in_' .$dbname;
$queryShowTables = 'show tables';
$resultShowTables = $focus->db->query($queryShowTables, true);
while($rowShowTables = $focus->db->fetchByAssoc($resultShowTables)){
		$tableName = $rowShowTables[$columnName];
		if ($customTable == $tableName) {
			//we have a custom table so go and get the custom fields and add to the field array
			$queryCustomTable = "show fields from $tableName";
			$resultCustomTable = $focus->db->query($queryCustomTable, true);
				while($rowCustomTable = $focus->db->fetchByAssoc($resultCustomTable)){
					$fieldName = $rowCustomTable['Field'];
    					$fields .= '<option value="'.$fieldName .'">'.$fieldName .'</option>';
    				}
			}
		
		}
		
return $fields;
}

//Function to get the email template names for the Process Stage task

function getEmailTemplates($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed

$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';
$queryFieldList = 'Select name from email_templates';
$resultFieldList = $focus->db->query($queryFieldList, true);
//First see if there is already an email template defs record
	if ($view != 'DetailView') {	
		require_once('modules/PM_ProcessManager/ProcessManagerUtils.php');
		$processManagerUtil = new ProcessManagerUtils();
		if ($focus->id != "") {
			//Get the object that the Process is filtering against
			$emailTemplateDefsRow = $processManagerUtil->getTaskEmailTemplateDefs($focus);
			//Now see if we have an existing entry for this sequence		
			$processTaskEmailTemplateName = $emailTemplateDefsRow['email_template_name'];
			if ($processTaskEmailTemplateName != '') {
	 				$fields .= '<option value="'.$processTaskEmailTemplateName .'">'.$processTaskEmailTemplateName .'</option>';
			 	}
			else{ 	 
				$fields .= '<option value=Please Specify>Please Specify</option>';	
			}		
		}
		else{
			$fields .= '<option value=Please Specify>Please Specify</option>';	
		}
	while($rowFieldList = $focus->db->fetchByAssoc($resultFieldList)){
		$fieldName = $rowFieldList['name'];
		$fields .= '<option value="'.$fieldName .'">'.$fieldName .'</option>';
	}
  }
return $fields;
}

//*************************************************
//SugarCRM 5.5 enhancement - support custom modules
//Not Used
//**************************************************

function getProcessObjects($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed

$fields = 'style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple" onchange="type_change();"';
$fields .= '<option value= > </option>';
$fields .= '<option value=leads>leads</option>';
$fields .= '<option value=opportunities>opportunities</option>';
$fields .= '<option value=accounts>accounts</option>';
$fields .= '<option value=contacts>contacts</option>';
$fields .= '<option value=cases>cases</option>';
$fields .= '<option value=bugs>bugs</option>';
$fields .= '<option value=project>project</option>';
$fields .= '<option value=tasks>tasks</option>';

//******************************************************************
//Custom Module Support - TBD
//SierraCRM Exemption Array
$sierraCRMProductArray = array();
$sierraCRMProductArray['PM_ProcessManager'] = 'PM_ProcessManager';
$sierraCRMProductArray['PM_ProcessManagerStage'] = 'PM_ProcessManagerStage';
$sierraCRMProductArray['PM_ProcessManagerStageTask'] = 'PM_ProcessManagerStageTask';


if (file_exists('custom/application/Ext/Include/modules.ext.php'))
{
    include('custom/application/Ext/Include/modules.ext.php');
    foreach ($beanFiles as $key=>$value){
 	//Now make sure the custom module has an entry for logic hooks 	
 	//Parse the file looking for custom modules - 

 	if (!in_array($key,$sierraCRMProductArray)) {
 			//Now go and get the table name
 			require_once($value);
 			$customModule = new $key;
 			$customModuleTable = $customModule->table_name;
 			$fields .= "<option value=$customModuleTable>$customModuleTable</option>";
 	}
    }		
}
//End Custom Module Support
//*******************************************************************
return $fields;
}

//Get the contact role for the edit view

function getContactRoles($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed

require_once('modules/PM_ProcessManager/ProcessManagerUtils.php');
require_once('include/utils.php');
global $app_list_strings;
$processManagerUtil = new ProcessManagerUtils();
$emailTemplateDefsRow = $processManagerUtil->getTaskEmailTemplateDefs($focus);
$contact_role = $emailTemplateDefsRow['contact_role'];
$fields =  get_select_options_with_id($app_list_strings['opportunity_relationship_type_dom'], $contact_role);
return $fields;
}

function getAssignedUserId($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed
	$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';
	//First see if there is already an assigned user for this task or call
	$focusid = $focus->id;
	if ($field == 'assigned_user_id_task') {
		$query = "Select assigned_user_id_task from pm_process_task_task_defs where task_id = '$focusid'";
	}
	elseif($field == 'assigned_user_id_call'){
		$query = "Select assigned_user_id_call from pm_process_task_call_defs where task_id = '$focusid'";
	}
	elseif ($field == 'assigned_user_id_meeting'){
		$query = "Select assigned_user_id_meeting from pm_process_task_meeting_defs where task_id = '$focusid'";
	}
	elseif ($field == 'assigned_user_id_create_object'){
		$query = "Select assigned_user_id_create_object from pm_process_task_create_object_defs where task_id = '$focusid'";
	}elseif ($field == 'assigned_user_id_project_task'){
		$query = "Select assigned_user_id_project_task from pm_process_project_task_defs where project_task_id = '$focusid'";
	}		
	$resultQueryField = $focus->db->query($query, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
		if ($field != '') {
			$fields .= '<option value="'.$field .'">'.$field .'</option>';
		}
		else{
			$fields .= "<option value='Please Specify'>Please Specify</option>";
		}	
$queryFieldList = 'Select user_name from users';
$resultFieldList = $focus->db->query($queryFieldList, true);
while($rowFieldList = $focus->db->fetchByAssoc($resultFieldList)){
		$fieldName = $rowFieldList['user_name'];
		$fields .= '<option value="'.$fieldName .'">'.$fieldName .'</option>';
}
return $fields;
}

function getProcessFilterField($focus, $field, $value, $view){
	//This function will check to see if this is an edit call or new
	//If Edit then $focus is set - so what we do is get the fields from the object table
	//Fill the array - then set the one that is current.	
	//Ignore if DetailView
	$sequenceID = substr($field,20);
	$filterListName = "process_filter_field" ."$sequenceID";
	$fields = '<name="filterList"  size="10" multiple="multiple">';
	if ($view != 'DetailView') {	
		require_once('modules/PM_ProcessManager/ProcessManagerUtils.php');
		$processManagerUtil = new ProcessManagerUtils();
		if ($focus->id != "") {
			//Get the object that the Process is filtering against
			$processObject = $processManagerUtil->getProcessObjectField($focus,"process_object");
			//Now see if we have an existing entry for this sequence		
			$processFilterField = $processManagerUtil->getFieldBySequenceID($focus,$sequenceID,'field_name');
			if ($processFilterField != '') {
			 	$fields = $processManagerUtil->getFieldsFromTable($focus,$processObject,$processFilterField);
			 	return $fields;			 	
			 	} 
		}
	}
	$fields .= '<option value=Please Specify>Please Specify</option>';
	return $fields;
}

function getProcessCancelField($focus, $field, $value, $view){
	$fields = '<name="filterList"  size="10" multiple="multiple">';
	if ($view != 'DetailView') {	
		require_once('modules/PM_ProcessManager/ProcessManagerUtils.php');
		$processManagerUtil = new ProcessManagerUtils();
		if ($focus->id == "") {
           $fields .= '<option value=Please Specify>Please Specify</option>';
		}
		else{
			$processCancelField = $processManagerUtil->getProcessObjectField($focus,"process_object_cancel_field");
			if ($processCancelField == '') {
				$fields .= '<option value=Please Specify>Please Specify</option>';
			}
			else{
				$fields .= "<option value=$processCancelField>$processCancelField</option>";
			}
		}
	}
	return $fields;
}

//This next function will get the field from the process filter table
//Focus is the Process Manager - so focus id is the id
//field is the field defined in vardefs - detail view
function getDetailViewField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$sequenceID = substr($field,17);
	$queryField = "Select field_name from pm_process_filter_table where process_id = '$focusid' and sequence = $sequenceID ";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList['field_name'];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = 'N/A';
	}
	return $fields;
}

function getDetailViewValue($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
    $sequenceID = substr($field,17);
	$focusid = $focus->id;
	
	$queryField = "Select field_value from pm_process_filter_table where process_id = '$focusid' and sequence = $sequenceID ";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList['field_value'];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}

function getEditViewValue($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
    $sequenceID = substr($field,20,1);
	$focusid = $focus->id;	
	$queryField = "Select field_value from pm_process_filter_table where process_id = '$focusid' and sequence = $sequenceID ";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList['field_value'];
	if ($fieldValue != '') {
		$fields = "<input type='text' name='$field' id='$field' size='30' maxlength='255' value='$fieldValue' title='' tabindex='6' > ";
	}
	else{
		$fields = "<input type='text' name='$field' id='$field' size='30' maxlength='255' value='' title='' tabindex='6' > ";
	}
	return $fields;
}
function getDetailViewOperator($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$sequenceID = substr($field,20);
	$queryField = "Select field_operator from pm_process_filter_table where process_id = '$focusid' and sequence = $sequenceID ";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList['field_operator'];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = 'N/A';
	}
	return $fields;
}

//***********************************************************
//Generic function to get a specific field for the detail view

function getDetailViewObjectField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	if ($field == 'detail_view_process_object_cancel_field') {
		$field = 'process_object_cancel_field';
	}
	if ($field == 'detail_view_process_object_cancel_field_value') {
		$field = 'process_object_cancel_field_value';
	}
	if ($field == 'detail_view_process_object_cancel_field_operator') {
		$field = 'process_object_cancel_field_operator';
	}		
	$sequenceID = substr($field,20);
	$queryField = "Select $field from pm_processmanager where id = '$focusid' ";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = 'N/A';
	}
	return $fields;
}


//***************************************************
//This next function will get Email Defs Values
//for PM Stage Task Detail View
function getDetailViewEmailDefField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
    $sequenceID = substr($field,17);
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_email_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}

//********************************************************************************
//This function will get the information for the detail view for the task defs
//getDetailViewTaskDefField
function getDetailViewTaskDefField($focus, $field, $value, $view){
	$focusid = $focus->id;
	$field = substr($field,12);
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_task_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}
//******************************************************************************

//**********************************************************************************
//Function to get the Call Defs Fields - 
function getDetailViewTaskField($focus, $field, $value, $view){
	$focusid = $focus->id;
	if ($field == 'detai_view_task_description') {
		$field = 'task_description';
	}
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_task_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}

//**********************************************************************************
//Function to get the Call Defs Fields - 
function getDetailViewCallField($focus, $field, $value, $view){
	$focusid = $focus->id;
     $field = substr($field,12);	
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_call_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}

//**********************************************************************************
//Function to get the Call Defs Fields - 
function getDetailViewCallDefField($focus, $field, $value, $view){
	$focusid = $focus->id;
	$field = substr($field,17);
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_call_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}
//**********************************************************************************
//This function gets the 1 - 0 value from a bool field and returns to set the correct
//value in a check box
//**********************************************************************************
function getTaskDetailViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	$queryField = "Select $field from pm_process_task_task_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
	return $fieldValue;
}

function getCallDetailViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	$queryField = "Select $field from pm_process_task_call_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
	return $fieldValue;
}

//Now do the same for the edit views

function getCallEditViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	$queryField = "Select is_escalatable_call from pm_process_task_call_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList['is_escalatable_call'];
	$on_or_off = 'CHECKED';
		if ( empty($fieldValue) ||  $fieldValue == '0')
		{
			$on_or_off = '';
		}
return "<input type='checkbox' name='is_escalatable_call_edit' id='is_escalatable_call_edit' $on_or_off >";
}


function getTaskEditViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	$queryField = "Select is_escalatable_task from pm_process_task_task_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList['is_escalatable_task'];
	$on_or_off = 'CHECKED';
		if ( empty($fieldValue) ||  $fieldValue == '0')
		{
			$on_or_off = '';
		}
	return "<input type='checkbox' name='is_escalatable_task_edit' id='is_escalatable_task_edit' $on_or_off >";
}
//******************************************************************************
//Function to get the task details in the edit view
function getTaskEditViewField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$queryField = "Select $field from pm_process_task_task_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
		$fields = "<input type=text name='$field' value='$fieldValue' size=50>";
	return $fields;
}

function getTaskEditViewFieldPriority($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed

	$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';
	$focusid = $focus->id;	
	$queryField = "Select task_priority from pm_process_task_task_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
		if ($field != '') {
		$fields .= '<option value="'.$field .'">'.$field .'</option>';
	}
	$fields .= '<option value="High">High</option>';
	$fields .= '<option value="Medium">Medium</option>';
	$fields .= '<option value="Low">Low</option>';
return $fields;
}

//PROJECT TASKS--added by Peter DeMartini
function getProjectTaskEditViewField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$queryField = "Select $field from pm_process_project_task_defs where project_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
	if($field == 'project_task_start_date' || $field == 'project_task_end_date' || $field == 'project_task_id'){
		$fields = "<input type=text name='$field' value='$fieldValue' size=5>";
	}else{
		$fields = "<input type=text name='$field' value='$fieldValue' size=50>";
	}
	return $fields;
}

function getProjectTaskEditViewFieldPriority($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed

	$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';
	$focusid = $focus->id;	
	$queryField = "SELECT project_task_priority FROM pm_process_project_task_defs WHERE project_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
		if ($field != '') {
		$fields .= '<option value="'.$field .'">'.$field .'</option>';
	}
	$fields .= '<option value="High">High</option>';
	$fields .= '<option value="Medium">Medium</option>';
	$fields .= '<option value="Low">Low</option>';
return $fields;
}

function getProjectTaskEditViewFieldStatus($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed

	$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';
	$focusid = $focus->id;	
	$queryField = "SELECT project_task_status FROM pm_process_project_task_defs WHERE project_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
		if ($field != '') {
		$fields .= '<option value="'.$field .'">'.$field .'</option>';
	}
	$fields .= '<option label="Not Started" value="Not Started">Not Started</option>';
	$fields .= '<option label="In Progress" value="In Progress">In Progress</option>';
	$fields .= '<option label="Completed" value="Completed">Completed</option>';
	$fields .= '<option label="Pending Input" value="Pending Input">Pending Input</option>';
	$fields .= '<option label="Deferred" value="Deferred">Deferred</option>';
return $fields;
}

function getProjectTaskEditViewFieldDescription($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_project_task_defs where project_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<textarea  name=$field value='$fieldValue' rows='6' cols='50'>$fieldValue</textarea>";
	return $fields;
}

function getDetailViewProjectTaskField($focus, $field, $value, $view){
	$focusid = $focus->id;
	if ($field == 'detai_view_project_task_description') {
		$field = 'project_task_description';
	}elseif($field == 'detail_view_project_task_subject'){
		$field = 'project_task_subject';
	}elseif($field == 'detail_view_project_task_id'){
		$field = 'project_task_id';
	}elseif($field == 'detail_view_project_task_status'){
		$field = 'project_task_status';
	}elseif($field == 'detail_view_project_task_priority'){
		$field = 'project_task_priority';
	}elseif($field == 'detail_view_project_task_start_date'){
		$field = 'project_task_start_date';
	}elseif($field == 'detail_view_project_task_end_date'){
		$field = 'project_task_end_date';
	}elseif($field == 'detail_view_assigned_user_id_project_task'){
		$field = 'assigned_user_id_project_task';
	}elseif($field == 'detail_view_project_task_milestone'){
		$field = 'project_task_milestone';
		$milestone = true;
	}elseif($field == 'detail_view_project_task_task_number'){
		$field = 'project_task_task_number';
	}elseif($field == 'detail_view_project_task_order'){
		$field = 'project_task_order';
	}

	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_project_task_defs where project_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if($milestone == true){
		if($field == 0){
			$field = 'OFF';
		}else{
			$field = 'ON';
		}	
	}
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}

function getProjectTaskEditViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	$queryField = "SELECT $field FROM pm_process_project_task_defs WHERE project_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList['project_task_milestone'];
	$on_or_off = 'CHECKED';
		if ( empty($fieldValue) ||  $fieldValue == '0')
		{
			$on_or_off = '';
		}
	return "<input type='checkbox' name='project_task_milestone' id='project_task_milestone' $on_or_off >";
}

//END PROJECT TASKS



//*****************************************************************************

//******************************************************************************
//Function to get the task details in the edit view
function getCallEditViewField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_call_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<input type=text name=$field value='$fieldValue' size=50>";
	return $fields;
}
//******************************************************************************
//Function to get the task details in the edit view
function getCallEditViewFieldDescription($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_call_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<textarea  name=$field value='$fieldValue' rows='6' cols='50'>$fieldValue</textarea>";
	return $fields;
}
//******************************************************************************
//Function to get the task details in the edit view
function getTaskEditViewFieldDescription($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_task_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<textarea  name=$field value='$fieldValue' rows='6' cols='50'>$fieldValue</textarea>";
	return $fields;
}
//*****************************************************************************
//Passed field is going to be task_due_date_delay_minutes, task_due_date_delay_hours - etc
//Get the string position and set the for loop accordingly
function getTaskEditViewDelayFields($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed
	$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';	
	$focusid = $focus->id;
	$delay = substr($field,20);
	$fieldQuery = substr($field,5);
	//Are we a task or a call?
	if (substr_count($field,'call') > 0) {
		//Replace due_date with start
		$fieldQuery = str_replace("due_date","start",$fieldQuery);
		$queryField = "Select $fieldQuery from pm_process_task_call_defs where task_id = '$focusid'";
	}
	else{
		$queryField = "Select $fieldQuery from pm_process_task_task_defs where task_id = '$focusid'";
	}
	//Now setup the case
	switch ($delay) {
	case 'minutes':
    	$delay = 60;
    	break;
	case 'hours':
    	$delay = 24;
    	break;
	case 'days':
    	$delay = 31;
    	break;
    case 'months':
    	$delay = 12;
    	break;
	case 'years':
    	$delay = 10;
    	break;    	
	}
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$fieldQuery];
		if ($field != '') {
		$fields .= '<option value="'.$field .'">'.$field .'</option>';
	}
	//Now build the rest of the option list with the remaing values
	//For loop used
	for($i = 0; $i < $delay; $i++){
		$fields .= "<option value='$i'>$i</option>";
	}
return $fields;
}

//*****************************************************************************
//FOLLOWING FUNCTIONS SUPPORT MEETINGS
//*****************************************************************************
function getMeetingEditViewField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_meeting_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<input type=text name=$field value='$fieldValue'>";
	return $fields;
}
//***********************************************************************************************
//Passed field is going to be meeting_due_date_delay_minutes, meeting_due_date_delay_hours - etc
//Get the string position and set the for loop accordingly
//************************************************************************************************
function getTaskEditViewDelayFieldsMeetings($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed
	$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';	
	$focusid = $focus->id;
	$delay = substr($field,20);
	$fieldQuery = substr($field,8);
	$queryField = "Select $fieldQuery from pm_process_task_meeting_defs where task_id = '$focusid'";
	//Now setup the case
	switch ($delay) {
	case 'minutes':
    	$delay = 60;
    	break;
	case 'hours':
    	$delay = 24;
    	break;
	case 'days':
    	$delay = 31;
    	break;
    case 'months':
    	$delay = 12;
    	break;
	case 'years':
    	$delay = 10;
    	break;    	
	}
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$fieldQuery];
		if ($field != '') {
		$fields .= '<option value="'.$field .'">'.$field .'</option>';
	}
	//Now build the rest of the option list with the remaing values
	//For loop used
	for($i = 0; $i < $delay; $i++){
		$fields .= "<option value='$i'>$i</option>";
	}
return $fields;
}
//******************************************************************************
//Function to get the task details in the edit view
function getMeetingEditViewFieldDescription($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_meeting_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<textarea  name=$field value='$fieldValue' rows='6' cols='50'>$fieldValue</textarea>";
	return $fields;
}

function getReminderTimeMeeting($focus, $field, $value, $view) {

	global $current_user, $app_list_strings;
	$reminder_t = $current_user->getPreference('reminder_time');
	if (!empty($focus->reminder_time)) {
		$reminder_t = $focus->reminder_time;
	}		

	if($view == 'EditView' || $view == 'MassUpdate') {
		global $app_list_strings;
        $html = '<select name="reminder_time_meeting">';
        $html .= get_select_options_with_id($app_list_strings['reminder_time_options'], $reminder_t);
        $html .= '</select>';
        return $html;
    }
 
    if($reminder_t == -1) {
       return "";	
    }
       
    return translate('reminder_time_options', '', $reminder_t);    
}

function getDetailViewMeetingDefField($focus, $field, $value, $view){
	$focusid = $focus->id;
	$field = substr($field,20);
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_meeting_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}

//*****************************************************************************
//END OF FUNCTIONS THAT SUPPORT MEETINGS
//*****************************************************************************

//************************************************************************
//NEW FUNCTIONS FOR INTERNAL EMAIL
//***********************************************************************

//******************************************************************************
//Function to get the task details in the edit view
function getEmailDefsEditViewField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$queryField = "Select $field from pm_process_task_email_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<textarea  name=$field value='$fieldValue' rows='6' cols='50'>$fieldValue</textarea>";
	return $fields;
}

function getEmailDefsDetailViewField($focus, $field, $value, $view){
	$focusid = $focus->id;
	$field = substr($field,12);
	$queryField = "Select $field from pm_process_task_email_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}

function getEmailDefsEditViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$queryField = "Select $field from pm_process_task_email_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}
function getEmailDefsDetailViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	if ($field == 'detail_view_internal_email') {
		$field = 'internal_email';
	}
	if ($field == 'detail_view_send_email_to_caseopp_account') {
		$field = 'send_email_to_caseopp_account';
	}	
	if ($field == 'detail_view_send_email_to_object_owner') {
		$field = 'send_email_to_object_owner';
	}		
	$queryField = "Select $field from pm_process_task_email_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}
//********************************************************************
//END INTERNAL EMAIL FUNCTIONS
//*******************************************************************

//******************************************************************************
//BEGIN NEW FUNCTIONS TO SUPPORT CREATE OBJECTS
//******************************************************************************
function getCreateObjectEditViewField($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_create_object_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<input type=text name=$field value='$fieldValue'>";
	return $fields;
}

function getCreateObjectEditViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_create_object_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList["$field"];
	if ($fieldValue == '' || $fieldValue == 0) {
		$CHECKED = '';
	}
	else{
		$CHECKED = 'CHECKED';
	}
    $fields = "<input name='$field' type='checkbox' class='checkbox' value='1' $CHECKED>";
	return $fields;
}

function getCreateObjectDetailViewFieldCheckBox($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$field = substr($field,26);
	$queryField = "Select $field from pm_process_task_create_object_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList["$field"];
	if ($fieldValue == '' || $fieldValue == 0) {
		$CHECKED = '';
	}
	else{
		$CHECKED = 'CHECKED';
	}
    $fields = "<input name='$field' type='checkbox' DISABLED class='checkbox' value='1' $CHECKED>";
	return $fields;
}



function getCreateObjectEditViewFieldDescription($focus, $field, $value, $view){
	$focusid = $focus->id;
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_create_object_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$fieldValue = $rowFieldList[$field];
    $fields = "<textarea  name=$field value='$fieldValue' rows='6' cols='50'>$fieldValue</textarea>";
	return $fields;
}

function getDetailViewCreateObjectDefField($focus, $field, $value, $view){
	$focusid = $focus->id;
	$field = substr($field,26);
	//Each $field will have 1,2,3,4,5 as the last element
	//So get the value and ask for that sequence id
	$focusid = $focus->id;	
	$queryField = "Select $field from pm_process_task_create_object_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList[$field];
	if ($field != '') {
		$fields = $field; 

	}
	else{
		$fields = '';
	}
	return $fields;
}

function getCreateObjectRecordType($focus, $field, $value, $view) { //This is the function that the field will run when it is displayed
	global $app_list_strings;
	$processObject = $app_list_strings['process_object'];
	$focusid = $focus->id;	
	$queryField = "Select create_object_type from pm_process_task_create_object_defs where task_id = '$focusid'";
	$resultQueryField = $focus->db->query($queryField, true);
	$rowFieldList = $focus->db->fetchByAssoc($resultQueryField);
	$field = $rowFieldList['create_object_type'];	
	$fields = '<name="create_object_type"  size="10" multiple="multiple">';

		if ($view != 'DetailView') {	
			require_once('modules/PM_ProcessManager/ProcessManagerUtils.php');
			$processManagerUtil = new ProcessManagerUtils();
			if ($focus->id != "") {
					$fields .="<option value=\"$field\">$field</option>";
			}
		}
		foreach ($processObject as $key=>$value){	
			$fields .="<option value=$key>$key</option>";
		}

	return $fields;

}

//*****************************************************************************
//END NEW FUNCTIONS TO SUPPORT CREATE OBJECTS
//*****************************************************************************



//
?>