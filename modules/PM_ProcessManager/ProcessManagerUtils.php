<?php
/*********************************************************************************
 * Standard SierraCRM, LLC Copyright
 * General utility functions for ProcessManager
 * 
 *Author: Bill Convis
********************************************************************************/

require_once('modules/PM_ProcessManager/PM_ProcessManager.php');	

//Constructor
class ProcessManagerUtils extends PM_ProcessManager{
	
	function ProcessManagerUtils() {
		$GLOBALS['log'] = LoggerManager :: getLogger('SugarCRM');
		global $sugar_config;
		parent::SugarBean();

	}

function getTaskEmailTemplateDefs($focus){
	$query = "Select * from pm_process_task_email_defs where task_id = '$focus->id'";
	$resultTaskEmailDefs = $focus->db->query($query, true);
	$rowTaskEmailDefs = $focus->db->fetchByAssoc($resultTaskEmailDefs);
	return $rowTaskEmailDefs;
}	
	
function getProcessObjectField($focus,$field){
	$query = "Select $field from pm_processmanager where id = '$focus->id'";
	$resultProcessObject = $focus->db->query($query, true);
	$rowProcessObject = $focus->db->fetchByAssoc($resultProcessObject);
	$processObject = $rowProcessObject[$field];
	return $processObject;
}

function getFieldFromFilterTable($focus,$field){
	$focusid = $focus->id;
	$queryField = "Select $field from pm_process_filter_table where process_id = '$focusid'  ";
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

function getFieldBySequenceID($focus,$sequenceID,$field){
	$focusid = $focus->id;
	$queryField = "Select $field from pm_process_filter_table where process_id = '$focusid' and sequence = $sequenceID ";
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

function getFieldsFromTable($focus,$table,$processFilterField){
	$fields = '	<style=\"display:none;\" visiblitity="hidden"  name="sel1" size="10" multiple="multiple">';
	if ($processFilterField != '') {
		$fields .= "<option value=$processFilterField>$processFilterField</option>";
	}
//	else{
//		$fields .= "<option value='Please Select'>'Please Select'</option>";
//	}	
	$fields .= "<option value='Please Specify'>Please Specify</option>";
	$queryFieldList = 'show fields from ' .$table;
	$resultFieldList = $focus->db->query($queryFieldList, true);
	while($rowFieldList = $focus->db->fetchByAssoc($resultFieldList)){
		$fieldName = $rowFieldList['Field'];
		$fields .= '<option value="'.$fieldName .'">'.$fieldName .'</option>';
	}
return $fields;
	
}

	function duplicateProcessTask($taskId,$stageId){
		require_once('modules/PM_ProcessManagerStageTask/PM_ProcessManagerStageTask.php');
		$focusNewTask = new PM_ProcessManagerStageTask();
		$newFocusTaskId = create_guid();
		//First get the task
		$query = "Select * from pm_processmanagerstagetask where id = '" .$taskId ."'";
		$resultProcessTask = $focusNewTask->db->query($query, true);
		
		//Now get all the tasks for the stage so that we know which order this next one is
		$query_stage = "Select count(*) from pm_processmgerstagetask where pm_processmanagerstage_ida = '" .$stageId ."'";
		$resultProcessStage = $focusNewTask->db->query($query_stage, true);
		
		//Now get the actual fields from the task table
		$queryTableFields = "Show fields from pm_processmanagerstagetask";
		$resulteShowFields = $focusNewTask->db->query($queryTableFields, true);
		
		//The current count of the tasks for the stage is next
		$rowCurrentTaskCount = $focusNewTask->db->fetchByAssoc($resultProcessStage);
		$current_task_count = $rowCurrentTaskCount['count(*)'];
		$new_task_order = $current_task_count + 1;
		//Get all the fields  and duplicate and insert
		$rowTask = $focusNewTask->db->fetchByAssoc($resultProcessTask);
		$queryNewTaskInsert = "Insert into pm_processmanagerstagetask set id = '" .$newFocusTaskId ."', task_order = " .$new_task_order;
		$queryNewTaskInsert .= ", stage_id = '" .$stageId ."'";
		while($row_task_fields = $focusNewTask->db->fetchByAssoc($resulteShowFields))
		{
			$field = $row_task_fields['Field'];
			$value = $rowTask[$field];
			//If field is start_delay_minutes hours or days then dont add a quote
			if ($field == 'start_delay_minutes' || $field == 'start_delay_hours' || $field == 'start_delay_days' || $field == 'start_delay_months' || $field == 'start_delay_years') {
				if ($value != null) {
					$queryNewTaskInsert .= ", " .$field ."=" .$value;
				}
				else
					$queryNewTaskInsert .= ", " .$field ."= 0";
			}
			elseif ($field == 'date_entered' || $field == 'date_modified'){
				$today = date('Y-m-d H:i:s');
				$queryNewTaskInsert .= ", " .$field ."= '" .$today ."'";
			}
			elseif($field == 'task_order' || $field == 'id' || $field == 'stage_id') {
				//Do Nothing
			}
			else{
				
				$queryNewTaskInsert .= ", " .$field ."= '" .$value ."'";
			}
			//Here are are seeing if the field is email_template_defs_id, call or task and holding the value
			if ($field == 'email_template_defs_id') {
				if ($value != null) {
					$email_template_defs_id = $value;
				}
			}
			if ($field == 'task_defs_id') {
				if ($value != null) {
					$task_defs_id = $value;
				}
			}
			if ($field == 'calls_defs_id') {
				if ($value != null) {
					$calls_defs_id = $value;
				}
			}
			
		}
		$focusNewTask->db->query($queryNewTaskInsert, true);
		
		//Now figure out if the task is email, task or call and add a new defs file
		if ($email_template_defs_id != null) {
			$query = "Select * from pm_process_task_email_defs where id = '" .$email_template_defs_id ."'";
			$resultEmailTemplateDefs = $focusNewTask->db->query($query, true);
			$rowEmailTempalateDefs = $focusNewTask->db->fetchByAssoc($resultEmailTemplateDefs);
			$newEmailTemplateDefsId = create_guid();
			$query = "Insert into pm_process_task_email_defs set id = '" .$newEmailTemplateDefsId ."'";
			$query .= ",  email_template_name = '" .$rowEmailTempalateDefs['email_template_name'] ."' , task_id = '" .$newFocusTaskId ."'";
			$query .= ", email_template_id = '" .$rowEmailTempalateDefs['email_template_id'] ."'";
			$query .= ", contact_role = '" .$rowEmailTempalateDefs['contact_role'] ."'";
			$focusNewTask->db->query($query, true);
			
			//And finally update the new task with the new email defs id
			$query = "Update pm_processmanagerstagetask set email_template_defs_id = '" .$newEmailTemplateDefsId ."'";
			$query .= "where id = '" .$newFocusTaskId ."'";
			$focusNewTask->db->query($query, true);
		}
		if ($task_defs_id != null) {
			$query = "Select * from pm_process_task_task_defs where id = '" .$task_defs_id ."'";
			$resultTaskTemplateDefs = $focusNewTask->db->query($query, true);
			$rowTaskTempalateDefs = $focusNewTask->db->fetchByAssoc($resultTaskTemplateDefs);
			$newTaskTemplateDefsId = create_guid();
			$query = "Insert into pm_process_task_task_defs set id = '" .$newTaskTemplateDefsId ."'";
			$query .= ",  task_subject = '" .$rowTaskTempalateDefs['task_subject'] ."' , task_id = '" .$newFocusTaskId ."'";
			$query .= ",  task_priority = '" .$rowTaskTempalateDefs['task_priority'] ."' , due_date_delay_minutes = " .$rowTaskTempalateDefs['due_date_delay_minutes'];
			$query .= ",  due_date_delay_hours = " .$rowTaskTempalateDefs['due_date_delay_hours'] ." , due_date_delay_days = " .$rowTaskTempalateDefs['due_date_delay_days'];
			$query .= ",  due_date_delay_months = " .$rowTaskTempalateDefs['due_date_delay_months'] ." , due_date_delay_years = " .$rowTaskTempalateDefs['due_date_delay_years'];
			$query .= ",  due_date_delay_type = '" .$rowTaskTempalateDefs['due_date_delay_type'] ."'";
			$query .= ",  assigned_user_id_task = '" .$rowTaskTempalateDefs['assigned_user_id_task'] ."'";
			$focusNewTask->db->query($query, true);
			
			//And finally update the new task with the new task defs id
			$query = "Update pm_processmanagerstagetask set task_defs_id = '" .$newTaskTemplateDefsId ."'";
			$query .= "where id = '" .$newFocusTaskId ."'";
			$focusNewTask->db->query($query, true);
		}
		if ($calls_defs_id != null) {
			$query = "Select * from pm_process_task_call_defs where id = '" .$calls_defs_id ."'";
			$resultCallTemplateDefs = $focusNewTask->db->query($query, true);
			$rowCallTempalateDefs = $focusNewTask->db->fetchByAssoc($resultCallTemplateDefs);
			$newCallTemplateDefsId = create_guid();
			$query = "Insert into pm_process_task_call_defs set id = '" .$newCallTemplateDefsId ."'";
			$query .= ",  call_subject = '" .$rowCallTempalateDefs['call_subject'] ."' , task_id = '" .$newFocusTaskId ."'";
			$query .= ",  reminder_time = " .$rowCallTempalateDefs['reminder_time'] ." , start_delay_minutes = " .$rowCallTempalateDefs['start_delay_minutes'];
			$query .= ",  start_delay_hours = " .$rowCallTempalateDefs['start_delay_hours'] ." , start_delay_days = " .$rowCallTempalateDefs['start_delay_days'];
			$query .= ",  start_delay_months = " .$rowCallTempalateDefs['start_delay_months'] ." , start_delay_years = " .$rowCallTempalateDefs['start_delay_years'];
			$query .= ", description = '"  .$rowCallTempalateDefs['description'] ."'";
			$query .= ", start_delay_type = '"  .$rowCallTempalateDefs['start_delay_type'] ."'";
			$query .= ",  assigned_user_id_call = '" .$rowTaskTempalateDefs['assigned_user_id_call'] ."'";
			$focusNewTask->db->query($query, true);
			
			//And finally update the new task with the new task defs id
			$query = "Update pm_processmanagerstagetask set calls_defs_id = '" .$newCallTemplateDefsId ."'";
			$query .= "where id = '" .$newFocusTaskId ."'";
			$focusNewTask->db->query($query, true);
		}
		return $newFocusTaskId;
		
	}
	
	//taskid = stage id and stageId = processId	
	function duplicateProcessStage($stageId,$processId){
		$log = LoggerManager::getLogger('Link');
		require_once('modules/PM_ProcessManagerStage/PM_ProcessManagerStage.php');
		$focusNewStage = new PM_ProcessManagerStage();
		$newFocusStageId = create_guid();
		//First get the task
		$query = "Select * from pm_processmanagerstage where id = '" .$stageId ."'";
		$resultProcessStage = $focusNewStage->db->query($query, true);
		
		//Now get all the stages for the process so that we know which order this next one is
		$query_stage = "Select count(*) from pm_processmmanagerstage where pm_processmanager_ida = '" .$processId ."'";
		$resultProcessStageCount = $focusNewStage->db->query($query_stage, true);
		
		//Now get the actual fields from the task table
		$queryTableFields = "Show fields from pm_processmanagerstage";
		$resultShowFields = $focusNewStage->db->query($queryTableFields, true);
		
		//The current count of the tasks for the stage is next
		$rowCurrentStageCount = $focusNewStage->db->fetchByAssoc($resultProcessStageCount);
		$current_stage_count = $rowCurrentStageCount['count(*)'];
		$new_stage_order = $current_stage_count + 1;
		//Get all the fields  and duplicate and insert
		$rowStage = $focusNewStage->db->fetchByAssoc($resultProcessStage);
		$queryNewStageInsert = "Insert into pm_processmanagerstage set id = '" .$newFocusStageId ."', stage_order = " .$new_stage_order;
		$queryNewStageInsert .= ", process_id = '" .$processId ."'";
		while($row_stage_fields = $focusNewStage->db->fetchByAssoc($resultShowFields))
		{
			$field = $row_stage_fields['Field'];
			$value = $rowStage[$field];
			//If field is start_delay_minutes hours or days then dont add a quote
			if ($field == 'start_delay_minutes' || $field == 'start_delay_hours' || $field == 'start_delay_days' || $field == 'start_delay_months' || $field == 'start_delay_years') {
				$log->debug("Line 929  of link and field is $field and value is $value");
				if ($value != null) {
					$queryNewStageInsert .= ", " .$field ."=" .$value;
				}
				else
					$queryNewStageInsert .= ", " .$field ."= 0";
			}
			elseif ($field == 'date_entered' || $field == 'date_modified'){
				$today = date('Y-m-d H:i:s');
				$queryNewStageInsert .= ", " .$field ."= '" .$today ."'";
			}
			elseif($field == 'stage_order' || $field == 'id' || $field == 'process_id') {
				//Do Nothing
			}
			else{
				$log->debug("Line 937  of link and field is $field and value is $value");
				$queryNewStageInsert .= ", " .$field ."= '" .$value ."'";
			}
			
		}
		$focusNewStage->db->query($queryNewStageInsert, true);
		return $newFocusStageId;
		
	}

}

?>