<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
/*********************************************************************************
 * Copyright: SierraCRM, Inc. 2007
 * Portions created by SierraCRM are Copyright (C) SierraCRM, Inc.
 * The contents of this file are subject to the SierraCRM, Inc. End User License Agreement
 * You may not use this file except in compliance with the License. 
 * You may not rent, lease, lend, or in any way distribute or transfer any rights or this file or Process Manager
 * registrations (purchased licenses) to third parties without SierraCRM, Inc. written approval, and subject to
 * agreement by the recipient of the terms of this EULA.
 * Process Manager for SugarCRM is owned by SierraCRM, Inc. and is protected by international and local copyright laws and
 * treaties. You must not remove or alter any copyright notices on any copies of Process Manager for SugarCRM. 
 * You may not use, copy, or distribute Process Manager for SugarCRM, except as granted by SierraCRM, Inc.
 * without written authorization from SierraCRM, Inc. or its designated agents. Furthermore, this Copyright notice
 * does not grant you any rights in connection with any trademarks or service marks of SierraCRM, Inc. 
 * SierraCRM, Inc. reserves all intellectual property rights, including copyrights, and trademark rights of this software.
 ********************************************************************************/
/*********************************************************************************
 *SierraCRM, Inc
 *14563 Ward Court
 *Grass Valley, CA. 95945
 *www.sierracrm.com
 ********************************************************************************/

include_once('config.php');
require_once ('log4php/LoggerManager.php');
require_once('include/entryPoint.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/Emails/Email.php');
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('include/TimeDate.php');
require_once('modules/Users/User.php');
require_once ('sugar_version.php'); // provides $sugar_version, $sugar_db_version, $sugar_flavor
require_once ('include/database/DBManager.php');
require_once ('include/database/DBManagerFactory.php');
require_once ('include/javascript/jsAlerts.php');
require_once ('include/modules.php'); // provides $moduleList, $beanList, $beanFiles, $modInvisList, $adminOnlyList, $modInvisListActivities
require_once ('modules/ACL/ACLController.php');
require_once ('modules/Administration/Administration.php');
include_once ('modules/Administration/updater_utils.php');
require_once('modules/Tasks/Task.php');

global $previousTaskId;
global $runningProcessManager;

class ProcessManagerEngine extends SugarBean {

	var $object_name = "ProcessManageEngine";
	var $module_dir = 'ProcessManager';
    
	function ProcessManagerEngine() {
		$GLOBALS['log'] = LoggerManager :: getLogger('SugarCRM');
		global $sugar_config;
		parent::SugarBean();

	}

	var $new_schema = true;
function processManagerMain(){
	//Need to instatiate the Sugar Bean
	//session_start();
	global $current_user;
    $query = "Select id, object_id, object_type, object_event from pm_processmanager_entry_table";
	$result = $this->db->query($query,true);
	$queryDeleteEntryTable = "Delete from pm_processmanager_entry_table";
	$this->db->query($queryDeleteEntryTable);
    	while($row_process_entry_stage_table = $this->db->fetchByAssoc($result))
		{						
			$entryTableId = $row_process_entry_stage_table['id'];
			$focusObjectId = $row_process_entry_stage_table['object_id'];
			$focusObjectType = $row_process_entry_stage_table['object_type'];
			$focusObjectEvent = $row_process_entry_stage_table['object_event'];
			$this->startProcessManagerMainControlBlock($focusObjectId,$focusObjectType,$focusObjectEvent);

		}
	//Done with process_entry_stage_table - now we need to check the todo waiting tables for stages
	//So query these two tables and see if there any stages that have reached their time from 
	//object created dates.
	$query = "Select * from pm_process_stage_waiting_todo";
	$resultStageWaitingToDo = $this->db->query($query,false);
	//Get the current time in the users time zone converted to gmt time because we use gmt time
	$timezone = date('Z') / 3600;
	$timezone = substr($timezone,1);
	$today = gmdate('Y-m-d H:i:s');
	while($row_process_stage_waiting_todo = $this->db->fetchByAssoc($resultStageWaitingToDo))
		{
			//Get the start time from the row and if we are passed time to do it then do it
			$startTime = $row_process_stage_waiting_todo['start_time'];
			$startTimeToString = strtotime($startTime);
			$todayTimeToString = strtotime($today);
			
			if ($todayTimeToString > $startTimeToString) {
				//Run the stage tasks then delete the entry in the stage waiting table
				$rowId = $row_process_stage_waiting_todo['id'];
				$stage_id = $row_process_stage_waiting_todo['stage_id'];
				//Set the id of the row to the stage id because the function getStageTasks is expecting a row from the stage table
				$row_process_stage_waiting_todo['id'] = $stage_id;
				$process_id = $row_process_stage_waiting_todo['process_id'];
				$focusObjectId = $row_process_stage_waiting_todo['object_id'];
				$focusObjectType = $row_process_stage_waiting_todo['object_type'];
				$resultStageTaskIds = $this->getStageTasks($row_process_stage_waiting_todo);
				$resultStageTaskIdsCounter = $this->getStageTasks($row_process_stage_waiting_todo);
					if ($resultStageTaskIds != "") {
						$checkDefaultProcess = true;
						$this->doStageTasks($process_id,$stage_id,$resultStageTaskIds,$resultStageTaskIdsCounter,$focusObjectId,$focusObjectType);
						}
				$query = "Delete from pm_process_stage_waiting_todo where id = '" .$rowId."'";
				$this->db->query($query,false);
			}
			
		}
	//Check for escalation
	$this->performTaskEscalation();
	$this->performCallEscalation();						
}	
function insertIntoProcessMgrEntryTable($tableName,$objectId,$update_or_insert){
		
	$entryTableId = create_guid();
	$query = "Insert into pm_processmanager_entry_table set id = '" .$entryTableId ."'";
	$query .= ", object_id = '" .$objectId."', object_type = '" .$tableName ."'";
	$query .=", object_event = '" .$update_or_insert ."'";	
	$result = $this->db->query($query);	
	}


//*******************************************************************************
//Main Process for Process Manager - Called from Sugar Bean and external service
//The only passed value is $this - which means that we have been called
//from Save event from Sugar Bean - else if $this being passed in is null
//then we are coming from the service
//*******************************************************************************	


//*****************************************************************************
//This is the start of the process manager called from Sugar Bean
//Passing $this - which will be an object that has either been
//just created or just saved.
//*****************************************************************************
function startProcessManagerMainControlBlock($focusObjectId,$focusObjectType,$focusObjectEvent){
	//We work our way back from the completed process. We query the pm_process_completed
	//table to see if the current object is listed in this table. If so then we check the 
	//process that we are currently in and see if there is any more tasks or stages to do
	//otherwise we work our way over to the table pm_process_current and see if we are 
	//currently in a process - if so then see whats next. If not then see if there is a 
	//default process for the object.
	global $current_user;  
	//Check if the object has an active process
		//if($this->checkFocusProcess($focusObjectType)){
			$isObjectCurrentlyWaitingForTaskArray = array();
			$doesObjectHaveAnyDefaultProcessArray = array();	
			//Is the object type is a call then we need to see if the associated lead, contact or opportunity
			//is currently waiting for the next call
			if (($focusObjectType == 'calls' && $focusObjectEvent == "modify") || ($focusObjectType == 'tasks' && $focusObjectEvent == "modify")) {
				$waitingToDoTaskId = $this->checkIfTaskHasObjectWaitingOnTask($focusObjectId,$focusObjectType);
				return;
			}
			//Since we are here then we have an object of type leads or opportunities or calls or meetings
			//Then event will be create for all 4 and update for leads or opportunities - so we need to see
			//if there is any process's setup for the object type combo - ie: Lead/Create. First we check
			//to see if there are any default process's. These are process that run for every object type combo		
			$this->checkObjectProcess($focusObjectId,$focusObjectType,$focusObjectEvent,true);
		//}
		return;
	
}
//***************************************************************************
//This function checks to see if there are any leads, contacts or opps
//that are associated to the call - and if so then see if there are any
//tasks waiting to do. We pass in the focus id and type which will be a call or task
//***************************************************************************

function checkIfTaskHasObjectWaitingOnTask($focusObjectId,$focusObjectType){
	//First get the row from the calls table
	global $current_user;
	if ($focusObjectType == 'tasks') {
		$query = "Select parent_type, parent_id, contact_id, status from " .$focusObjectType ." where id = '" .$focusObjectId ."'";
	}
	else{
		$query = "Select parent_type, parent_id, status from " .$focusObjectType ." where id = '" .$focusObjectId ."'";
	}
	$result = $this->db->query($query);
	$rowTask = $this->db->fetchByAssoc($result);
	$parent_type = $rowTask['parent_type'];
	$parentID = $rowTask['parent_id'];
	//The Task Table has added contact_id but not calls so check for contacts
	if ($focusObjectType == 'tasks') {
		$contactId = $rowTask['contact_id'];
			if ($contactId != '') {
				if(($parent_type == 'Accounts') || ($parent_type == 'Contacts')){
					$parent_type = 'contacts';
					$parentID = $contactId;
				}
			}
	}
	//If focus object type is calls and parent_id is blank then we are dealing with a call for a Contact so go and get the contact id
	//From the calls_contacts table
	if ($focusObjectType == 'calls') {
		if ($parentID == '') {
			$queryCallsContacts = "select contact_id from calls_contacts where call_id = '$focusObjectId'";
			$resultCallsContacts = $this->db->query($queryCallsContacts);
			$rowCallsContacts = $this->db->fetchByAssoc($resultCallsContacts);
			$parentID = $rowCallsContacts['contact_id'];
			$parent_type = 'contacts';
		}
	}
	$isObjectCurrentlyWaitingForTaskArray = array();
	$parent_type = strtolower($parent_type);
		$isObjectCurrentlyWaitingForTaskArray = $this->checkIfObjectIsCurrentlyWaitingOnTask($parentID,$focusObjectType,$focusObjectId);
		if ($isObjectCurrentlyWaitingForTaskArray['count'] != 0) {
		//Is the task it is waiting on the completion of this call?
		//Get the previous task for this stage for this process - the array being returned is the task info
		//from the waiting table.	
			//At least one task is waiting to do something - is this task waiting on this call to be complete?
			$waitingOnId = $isObjectCurrentlyWaitingForTaskArray['waiting_on_id_1'];
			if ($isObjectCurrentlyWaitingForTaskArray['waiting_on_id_1'] == $focusObjectId) {
				//This next block is for calls
				$processID = $isObjectCurrentlyWaitingForTaskArray['process_id'];
				$stageID = $isObjectCurrentlyWaitingForTaskArray['stage_id'];
				//Patch 12/15/2009 - get TaskID and use in passing to removeTaskFromWaitingToDo
				$taskID = $isObjectCurrentlyWaitingForTaskArray['task_id_1'];
				if ($rowTask['status'] == 'Held' || $rowTask['status'] == 'Completed') {
					$previousWaitingOnCallId = $focusObjectId;
					//Get the pm_process_manager task for the next task to be created
					
					$rowTask = $this->getTask($isObjectCurrentlyWaitingForTaskArray['task_id_1']);			
					//So now we have the task row and we need to determine it we are to do a call or task
					if ($rowTask['email_template_defs_id'] != "") {
						$this->runEmailTask($isObjectCurrentlyWaitingForTaskArray['task_id_1'],$focusObjectId,$focusObjectType);
					}
					if ($rowTask['calls_defs_id'] != "") {
						//Patch 12/10/2009 - add focusobjecttype
						$newTaskCallId = $this->runScheduleCallTaskThatWasWaiting($focusObjectId,$focusObjectType,$processID,$stageID,$rowTask,$parentID,$parent_type);
					}
					if ($rowTask['task_defs_id'] != "") {
						$newTaskCallId = $this->runScheduleTaskTaskThatWasWaiting($focusObjectId,$focusObjectType,$processID,$stageID,$rowTask,$parentID,$parent_type);	
					}
					//Patch 12/15/2009 - pass waiting on id to removeTaskFromWaitingToDo
					$this->removeTaskFromWaitingToDo($taskID,$waitingOnId);						
				}
				//Now see if there was acutally more than one task waiting to do. If so then we need to update the 
				//task # 2 in the order with the id of the newly created call or task.
				if ($isObjectCurrentlyWaitingForTaskArray['count'] > 1) {
					//Update task_id_2 with the new id
					$taskId = $isObjectCurrentlyWaitingForTaskArray['task_id_2'];					
										
					$queryUpdateTaskWaitingToDo = "Update pm_process_task_waiting_todo set waiting_on_id = '" .$newTaskCallId ."' where object_id = '" .$parentID ."' and process_id = '$processID' and stage_id = '$stageID' ";
					$this->db->query($queryUpdateTaskWaitingToDo);
				}
				
			}
		
		//This means that there is more than one task waiting to do on completion of the previous task
		//But since the new task or call has not been created we dont know the task or call id - so
		//we need to update the process task waiting to do and place the new id in the field
	
		}	
}
//*************************************************************************
//Remove a task from the waiting to do table
//Patch 12/15/2009 - Update this function to include waiting on id
//************************************************************************
function removeTaskFromWaitingToDo($taskID,$waitingOnId){
	$query = "Delete from pm_process_task_waiting_todo where task_id = '" .$taskID ."' and waiting_on_id = '$waitingOnId' ";
	$result = $this->db->query($query);
}

//**************************************************************************
//This function is a helper function that gets the row from the task table
//passed info is task id
//*************************************************************************

function getTask($taskID){
	$query = "Select * from pm_processmanagerstagetask where id = '" .$taskID ."'";
	$result = $this->db->query($query);
	$rowTask = $this->db->fetchByAssoc($result);
	return $rowTask;
}

//****************************************************************************
//This function is called by Main Control Block to see if the 
//object id object type combination is currently in process - if so then
//we return an array with either 0 in count field or 1 or more process id's
//****************************************************************************
//Patch 12/08/2009 - enable mixing of both calls and task for feature - From Completion of Previous Tasks
//Remove Task_Type from the query

function checkIfObjectIsCurrentlyWaitingOnTask($parentID,$focusObjectType,$focusObjectId){
	$resultArray = array();
	$query = "Select process_id, stage_id, task_id, waiting_on_id, task_order from pm_process_task_waiting_todo where object_id = '" .$parentID ."' and waiting_on_id = '$focusObjectId' order by task_order ASC ";
	$result = $this->db->query($query,true);	
	//Are there more than one process's currently running against the object?
	$counter = 1;
		while($rowCurrentInProcess = $this->db->fetchByAssoc($result))
			{			
				$process_id = 'process_id';
				$stage_id = 'stage_id';
				$task_id = 'task_id_' .$counter;
				$waiting_on_id = 'waiting_on_id_' .$counter;
				$resultArray[$process_id] = $rowCurrentInProcess['process_id'];
				$resultArray[$stage_id] = $rowCurrentInProcess['stage_id'];
				$resultArray[$task_id] = $rowCurrentInProcess['task_id'];
				$resultArray[$waiting_on_id] = $rowCurrentInProcess['waiting_on_id'];
				$counter = $counter + 1;
				
			}
		if ($counter == 1) {
			$resultArray['count'] = 0;
		}
		else{		
			$resultArray['count'] = $counter;
		}
		return $resultArray;	
}

//*****************************************************************************	
//This function is called from SugarBean and checks to see if there is a default process 
//for the given object for an initial Create of the object
//*****************************************************************************

	function checkObjectProcess($focusObjectId,$focusObjectType,$focusObjectEvent,$isDefault){		
		global $current_user;	
	    require_once('modules/PM_ProcessManager/ProcessManagerEngine1.php');
		$processManagerEngine1 = new ProcessManagerEngine1();
		$checkDefaultProcess = false;
		$resultStageTaskIds = array();
		//If the focus object event is an insert then we look for a create event
		if ($focusObjectEvent == 'insert') {
			$query = "Select id from pm_processmanager where process_object = '" .$focusObjectType ."'";
			$query .="  and start_event = 'Create' and status = 'Active' and deleted = 0";
			$result = $this->db->query($query);
			//If we have a default process then first make sure we have not already done the process
			//If not done then kick it off
			$counter = 0;
			$andOrFilterFieldsArray = array();
			while($row = $this->db->fetchByAssoc($result)){										
				$process_id = $row['id'];
				$isDefaultProcessAlreadyDone = $this->checkIfDefaultProcessAlreadyDone($focusObjectId,$process_id,$this);		
				if (!$isDefaultProcessAlreadyDone) {		
					//Are we a new contact? If so then check to see if this contact has a role
					//that has a process					
					$resultProcessFilterTable = $this->getProcessFilterTableEntry($process_id);			
					$passFilterTest = true;
					while($rowProcessFilterTable =  $this->db->fetchByAssoc($resultProcessFilterTable)){						
						$rowProcessFilterTableID = $rowProcessFilterTable['id'];
						//If we have a process filter table entry then see if the field value pair is equal
						//to the focus object field value pair and if so then run the process - else exit
						//We use the function getFocusObjectFields
						if($rowProcessFilterTableID != ''){	
							//Get the and/or filter fields entry
							$andOrFilterFields = $rowProcessFilterTable['andorfilterfields'];						
							$focusFieldsArray = array();
							$field = $rowProcessFilterTable['field_name'];
							$value = $rowProcessFilterTable['field_value'];
							//Get the Filter Operator: equal, not equal, less than, greater than
							$fieldOperator = $rowProcessFilterTable['field_operator'];							
							$focusFieldsArray[$field] = $field;
							//If we are checking for a custom field then call the function to get the 
							//custom fields for the object - else call the original getFocusObjectFields						
							//Dont do anything is both the field name and value are blank
							if ($field != '' ) {
								$passFilterTest = $this->getFilterTestResult($passFilterTest,$field,$fieldOperator,$value,$focusObjectId,$focusObjectType,$focusFieldsArray);
							}

						}
						//If this is an or'ing then we are going to hold the value of the fileter test in the array
						if ($andOrFilterFields == 'or') {
							$andOrFilterFieldsArray[$counter] = $passFilterTest;			
						}
						$counter = $counter + 1;
					}							
					//So we have a default process - now we are going to check to see if 
					//we are finally ready to enter the steps to check for stages and tasks
					//If there were any filters and all filter conditions passed then we are true
					//otherwise we would be false
					
					//Here we are going to see if the filter fields were or'd and if there are any trues then we pass
					if ($andOrFilterFields == 'or') {	
						if (in_array("1",$andOrFilterFieldsArray)) {
							$passFilterTest = true;
						}
					}
					
					if ($passFilterTest) {
						$processStagesResult = $this->getProcessStages($process_id);
						$processStagesResultCount = $this->getCount("Select pm_processmanagerstage_idb from pm_processmmanagerstage where pm_processmanager_ida = '" .$process_id ."' and deleted = 0");
						//Now call the function to get the first Stage for the process
						//If there is no delay and there is a row then we get the row					
						$resultOrderedStages = $this->getOrderedStages($processStagesResult,$processStagesResultCount);
						if ($resultOrderedStages != '') {
							//Here we have a result set of ordered stages for the process
							//We get each row and see if there is a start delay if so then we place the stage info
							//in the pm_process_stage_waiting_todo
							while($row_stage = $this->db->fetchByAssoc($resultOrderedStages)){
								if ($row_stage['start_delay_minutes'] != 0 || $row_stage['start_delay_hours'] != 0 || $row_stage['start_delay_days'] != 0 || $row_stage['start_delay_months'] != 0 || $row_stage['start_delay_years'] != 0) {
									$checkDefaultProcess = true;
									$this->loadDelayedStage($focusObjectId,$focusObjectType,$process_id,$row_stage);
								}
								else{
									$stage_id = $row_stage['id'];
									$resultStageTaskIds = $this->getStageTasks($row_stage);
									$resultStageTaskIdsCounter = $this->getStageTasks($row_stage);
										if ($resultStageTaskIds != "") {
											$checkDefaultProcess = true;
											$this->doStageTasks($process_id,$stage_id,$resultStageTaskIds,$resultStageTaskIdsCounter,$focusObjectId,$focusObjectType);
									}
								}
							}
							//So now we have completed this process so make an entry into the pm_process_completed_process table
							$this->insertIntoProcessCompleted($focusObjectId,$process_id);
						}
				//End of if block for pass filter test
				}
			}
			$counter = $counter + 1;
		}
	}
	//Event is an update so see if there is a process for Modify for the given object
	else{		
		$processManagerEngine1->processManagerMain1($focusObjectId,$focusObjectType,$focusObjectEvent,$isDefault,$this);
	}
}

//********************************************************************
//This function checks to see if the field is from a custom table
//First see if the table exists by taking the 
//********************************************************************

function  isFieldFromCustomTable($focusObjectType,$field,$thisPM){
	$isCustomField = FALSE;
	$table = $focusObjectType ."_cstm";
	global $sugar_config;
	$config = $sugar_config['dbconfig'];
	$dbName = $config['db_name'];
	$dbName = strtolower($dbName);
	$columnName = 'Tables_in_';
	$columnName .= $dbName;
	$queryAllTables = "show tables from $dbName";
	$resultQueryAllTables = $thisPM->db->query($queryAllTables, true);
	while($rowQueryAllTables = $thisPM->db->fetchByAssoc($resultQueryAllTables)){
		$tableName = $rowQueryAllTables[$columnName];
		if ($tableName == $table) {
			//Now go and get the fields for the custom table
			$queryFieldList = 'show fields from ' .$table;
			$resultFieldList = $thisPM->db->query($queryFieldList, true);
			while($rowFieldList = $thisPM->db->fetchByAssoc($resultFieldList)){
				$fieldName = $rowFieldList['Field'];
 				//Don't show id_c
    				if ($fieldName == $field) {
    					$isCustomField = TRUE;
    				}
			}
		}
	}
	return $isCustomField;
}

//*********************************************************************
//This process checks to see if the default process for the object/event
//has already been done and if not then we kick it off
//*********************************************************************

function checkIfDefaultProcessAlreadyDone($focusObjectId,$process_id,$thisPM){
	$queryProcessAlreadyDone = "Select id from pm_process_completed_process where object_id = '" .$focusObjectId ."' and process_id = '";
	$queryProcessAlreadyDone .= $process_id ."' and process_complete = 1";
	$resultProcessComplete = $thisPM->db->query($queryProcessAlreadyDone);
	$rowProcessComplete = $thisPM->db->fetchByAssoc($resultProcessComplete);
	if ($rowProcessComplete) {
		return true;
	}
	else{
		return false;
	}
}

//*********************************************************************
//This function inserts an entry into the pm_process_completed_process
//table such that we know that we have completed this process and we dont
//need to do it again
//*********************************************************************

function insertIntoProcessCompleted($focusObjectId,$process_id){
	$id = create_guid();
	$query = "Insert into pm_process_completed_process set id = '" .$id ."' , object_id = '" .$focusObjectId ."', process_id = '" .$process_id ."', process_complete = 1";
	$this->db->query($query);
	
}

//*********************************************************************************
//This function loads a delayed stage into the table pm_process_stage_waiting_todo
//We need to determine if the stage is tied to a process that is a create event or
//a non create event - this will be the key to how we set the start_time field
//**********************************************************************************

function loadDelayedStage($focusObjectId,$focusObjectType,$process_id,$row_stage){
	$stageWaitingId = create_guid();
	$focusFieldsArray = array();
	$focusFieldsArray['date_entered'] = 'date_entered';
	$focusFieldsArray['date_modified'] = 'date_modified';
	$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
	$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_entered'];
	$focusObjectModifiedDate = $arrayFieldsFromFocusObject['date_modified'];
	
	//Now find out if the process is a create or non create
	$processCreateModifyType = $this->getProcessCreateModifyType($process_id);
	
	
	
	if ($processCreateModifyType == "Create") {
		
		$calculatedStartTime = $this->getNewCallTimeStartDelayedStage($focusObjectCreateDate,$row_stage['start_delay_days'],$row_stage['start_delay_hours'],$row_stage['start_delay_minutes'],$row_stage['start_delay_months'],$row_stage['start_delay_years']); 
	}
	else{
		$calculatedStartTime = $this->getNewCallTimeStartDelayedStage($focusObjectModifiedDate,$row_stage['start_delay_days'],$row_stage['start_delay_hours'],$row_stage['start_delay_minutes'],$row_stage['start_delay_months'],$row_stage['start_delay_years']); 
	}
	$query = "Insert into pm_process_stage_waiting_todo set id = '" .$stageWaitingId ."'";
	$query .= ", object_id = '" .$focusObjectId ."', object_type = '" .$focusObjectType ."'";
	$query .= ", process_id = '" .$process_id ."'" .", stage_id = '" .$row_stage['id'] ."'";
	$query .= ", start_delay_type = 'Create' , start_time = '" .$calculatedStartTime ."'";
	$result = $this->db->query($query); 
	
}

//******************************************************************
//This little function will return the process create/modify type
//for the LoadDelaytedStage
//***************************************************************
function getProcessCreateModifyType($process_id){
		$query = "Select start_event from pm_processmanager where id = '" .$process_id ."'";
		$result = $this->db->query($query);
		$rowProcess = $this->db->fetchByAssoc($result);
		$processStartType = $rowProcess['start_event']; 
		return $processStartType;

}

//**********************************************************************
//This function is called to retrieve the stages for the given process's
//**********************************************************************
	function getProcessStages($processID){
		$query = "Select pm_processmanagerstage_idb from pm_processmmanagerstage where pm_processmanager_ida = '" .$processID ."' and deleted = 0";
		$result = $this->db->query($query);
		$num_rows_result = count($result);	
		if ($num_rows_result == 0) {
			//There are no stages so do nothing
			return null;
			}
		else{
			return $result;			
		}
		
	}
//*************************************************************************************
//This function will take in the result list of all the stage id for the process and we
//are going to get all the stages ordered by stage_orders
//*************************************************************************************
function getOrderedStages($result,$resultCount){
	$queryStageOrder1 = "Select * from pm_processmanagerstage where id = ";
	//Now loop thru the result adding all the results
	$num_rows_result = count($result);
	$counter = 1;
	while($row_stage_list = $this->db->fetchByAssoc($result))
		{
			$queryStageOrder1 .= "'";
			$stage_id = $row_stage_list["pm_processmanagerstage_idb"];
			if($counter < $resultCount ){
				$queryStageOrder1 .= $stage_id ."'  or id = ";
			}
			else{
				$queryStageOrder1 .= $stage_id ."' order by stage_order ASC";
			}
			$counter = $counter + 1;
		}
	$resultOrderedStages = $this->db->query($queryStageOrder1);
	
	$num_rows_result_stage_1 = count($resultOrderedStages);
	if ($num_rows_result_stage_1 == 0) {
			//There is no stage 1 - so exit
			return '';
		}
	else{
		return $resultOrderedStages;
	}
	
}

//*************************************************************************
//This function will be passed a row from pm_process_mgr_stage and will
//get the stage one tasks.
//*************************************************************************
function getStageTasks($row_stage_1){

		$stageId = $row_stage_1['id'];
		$query = "Select pm_processmanagerstagetask_idb from pm_processmgerstagetask where pm_processmanagerstage_ida = '" .$stageId ."' and deleted = 0";
		$result = $this->db->query($query);
		$num_rows_result = count($result);
		
		if ($num_rows_result == 0) {
			//There are no stages so do nothing
			$result = "";
			return $result;
			}
		else{
			return $result;			
		}
	
}

//**************************************************************************
//This function is passed in a result set of task ids and is the call
//to do these tasks
//**************************************************************************

function doStageTasks($processID, $stageID, $resultStageTaskIds,$resultStageTaskIdsCounter,$focusObjectId,$focusObjectType){
    $num_rows_result = 0;
    while($row = $this->db->fetchByAssoc($resultStageTaskIdsCounter)){
    	$num_rows_result = $num_rows_result + 1;
    }
	global $current_user;
	//If there are more than one task then we need to order the tasks
	$taskTableQuery = "Select * from pm_processmanagerstagetask where id = '";
	$counter = 1;
	if ($num_rows_result == 1) {
		$row_task_id = $this->db->fetchByAssoc($resultStageTaskIds);
		$taskTableQuery .= $row_task_id['pm_processmanagerstagetask_idb'];
		$taskTableQuery .= "'";
	}
	else{	
		while($row_task_id = $this->db->fetchByAssoc($resultStageTaskIds))
		{
			//$row_task_id = $this->db->fetchByAssoc($resultStageTaskIds);		
			if($counter < $num_rows_result ){
				$taskTableQuery .= $row_task_id['pm_processmanagerstagetask_idb'] ."' or id ='";
			}
			else{
				$taskTableQuery .= $row_task_id['pm_processmanagerstagetask_idb'] ."' ORDER by task_order ASC";
			}
			$counter = $counter + 1;
		}
		
	}
	$result = $this->db->query($taskTableQuery);
	//Now we have all the tasks in order - so get the first row and do the task - whatever it is
	//First check to see if the first task has a delay start
	while($rowTask = $this->db->fetchByAssoc($result))
		{
			//Is there a delay - old code from 4.0
			//$start_delay_minutes = $rowTask['start_delay_minutes'];
			//$start_delay_hours = $rowTask['start_delay_hours'];
			//$start_delay_days = $rowTask['start_delay_days'];
			//if ($start_delay_minutes == 0 && $start_delay_hours == 0 && $start_delay_days == 0) {
			//No Delay so go ahead and call function to run the first task for stage 1
				$this->runTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType);
				unset($rowTask);
			//}

		}
		//This means that there is a delay on this task - so is this delay a fixed time delay from 
		//object event or is this delay a delay from previous task close/completed
		 		
	
}



//*****************************************************************************
//This function is called with a single row from the task table
//and is the function that will actually do the task
//*****************************************************************************

function runTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType){
	//What kind of task are we?
	//Since we are here then we need to set the user information so that pm
	//knows who the owner of the focus object is

	require_once('modules/Users/User.php');
	global $current_user;
	$current_user = new User();
	$rowUser = $this->getFocusOwner($focusObjectType,$focusObjectId);	
	//For version 4.5.1 we get the user preferences from the user_preference table
	$userId = $rowUser['id'];
	$rowUserPreferenceContents = $this->getUserPreferenceRow($userId);
	$current_user->id = $rowUser['id'];
	$current_user->user_name = $rowUser['user_name'];
	//$user_preferences = $rowUser['user_preferences'];
	$user_name = $rowUser['user_name'];
	$_SESSION[$current_user->user_name . '_PREFERENCES']['global'] = unserialize(base64_decode($rowUserPreferenceContents));				
	$current_user->user_preferences['global'] = unserialize(base64_decode($rowUserPreferenceContents));	
	//**********************************************************************
	//Set the session to run the email programs 
	//Time zone needed for email templates
	//**********************************************************************
	//This piece of code here mimics the login process where the User objects array
	//setPreference fields are set - we care mostly about emails here
	if ($rowTask['task_type'] == 'Send Email') {
		$taskId = $rowTask['id'];
		$this->runEmailTask($taskId,$focusObjectId,$focusObjectType);
	}
		if ($rowTask['task_type'] == 'Create Task') {
		$taskId = $rowTask['id'];
		$this->runCreateTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType);
	}
			if ($rowTask['task_type'] == 'Create Project Task') {
				$taskId = $rowTask['id'];
				$this->runCreateProjectTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType);
	}
				if ($rowTask['task_type'] == 'Schedule Call') {
					$taskId = $rowTask['id'];
					$this->runScheduleCallTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType);
	}
						if ($rowTask['task_type'] == 'Custom Script') {
						$taskId = $rowTask['id'];
						$this->runCustomScript($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType);
	}
							if ($rowTask['task_type'] == 'Schedule Meeting') {
							$taskId = $rowTask['id'];
							$this->runScheduleMeetingTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType);
	}

								if ($rowTask['task_type'] == 'Create New Record') {
									$processManagerEngine1 = new ProcessManagerEngine1();
									$taskId = $rowTask['id'];
									$processManagerEngine1->createRecord($focusObjectType,$focusObjectId,$rowTask);
	}
	
	
}
//****************************************************************************
//This function returns the row from the user table for the focus owner
//****************************************************************************

function getFocusOwner($focusObjectType,$focusObjectId){
	$query = "Select assigned_user_id from " .$focusObjectType ." where id = '" .$focusObjectId ."'";
	$result = $this->db->query($query, true);
	$rowLeads = $this->db->fetchByAssoc($result);
	
	$assigned_user_id = $rowLeads['assigned_user_id'];
	
	$queryUsers = "Select id, user_name from users where id = '" .$assigned_user_id ."'";
	$result = $this->db->query($queryUsers, true);
	$rowUser = $this->db->fetchByAssoc($result);
	
	return $rowUser;
	
}

//****************************************************************************
//This function returns the row from the user table for the focus owner
//****************************************************************************

function getUserPreferenceRow($userId){
	$query = "Select contents from user_preferences where assigned_user_id = '" . $userId ."' and category = 'global'";
	$result = $this->db->query($query, true);
	$rowUserPreference = $this->db->fetchByAssoc($result);
	$contents = $rowUserPreference['contents'];
	return $contents;
	
}

//******************************************************************************
//This function will retrieve the email address and id from the contact table
//for the given opp. 
//*****************************************************************************

function getContactOppEmails($focusObjectId,$focusObjectType,$contact_role){
	$query_opps_contacts = "Select contact_id from opportunities_contacts where opportunity_id  = ";
	$query_opps_contacts .= "'";
	$query_opps_contacts .= $focusObjectId;
	$query_opps_contacts .= "' and deleted = 0";
	if ($contact_role == '') {
		$query_opps_contacts .= " and contact_role IS NULL ";
	}
	
	$result_opps_contacts =& $this->db->query($query_opps_contacts, true);
	$row = $this->db->fetchByAssoc($result_opps_contacts);
	if ($row) {
		$queryContact = "Select id from contacts where id = '" .$row['contact_id'] ."'";
		$resultContacts = $this->db->query($queryContact, true);
		$rowContact = $this->db->fetchByAssoc($resultContacts);
		if ($rowContact) {
			return $rowContact;
		}
	}
	
}

//******************************************************************************
//This function will retrieve the email address and id from the account table
//for the given case
//*****************************************************************************

function getAccountEmailForCases($focusObjectId,$focusObjectType){
	$query_accounts_cases = "Select account_id from cases where id  = ";
	$query_accounts_cases .= "'";
	$query_accounts_cases .= $focusObjectId;
	$query_accounts_cases .= "'";
	$result_accounts_cases = $this->db->query($query_accounts_cases, true);
	$row = $this->db->fetchByAssoc($result_accounts_cases);
	return $row;
	
}

//*************************************************************************
//This function runs a custom script created by the end user
//This script lives in the ProcessManager folder called customScripts
//*************************************************************************

function runCustomScript($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType){
	
	//First get the name of the script
	$scriptName = $rowTask['custom_script'];
	require_once("modules/PM_ProcessManager/customScripts/$scriptName");
	//Script name - strip the .php from the custom script name and 
	//call the constructor
	$scriptName = str_replace(".php","",$scriptName);
	$customScript = new $scriptName($focusObjectId,$focusObjectType);

	
	
}

//***************************************************************************
//This is the schedule call task that was waiting
//Patch 12/10/2009 - pass $previousFocusObjectType
//**************************************************************************
function runScheduleCallTaskThatWasWaiting($previousWaitingOnCallId,$previousFocusObjectType,$processID,$stageID,$rowTask,$focusObjectId,$focusObjectType){
		//Go and get the defs record from pm_process_task_call_defs
		global $current_user;
		$taskId = $rowTask['id'];
		$queryTaskCallDefs = "Select * from pm_process_task_call_defs where task_id = '" .$taskId ."'";
		$resultTaskCallDefs = $this->db->query($queryTaskCallDefs);
		$rowTaskCallDefs = $this->db->fetchByAssoc($resultTaskCallDefs);
		if ($rowTaskCallDefs) {
			//Calculate the start time which is entered into the table as date_start and time_start
			//The edit view only allows times to show at 00,15,39,45 minutes after the hours
			//The field start_delay_type will hold the type of delay - from object creation or previous
			//task complete.
				//Get the create date from the function that gets focus fields
				$focusFieldsArray = array();
				$focusFieldsArray['date_modified'] = 'date_modified';
				//Patch 12/10/2009 replace "calls" with $previousFocusObjectType
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($previousWaitingOnCallId,$previousFocusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_modified'];
				//If the task start_delay_days =  0 then we use the focus object create date for the call 
				//date_start.
	
				$newCallTimeStart = $this->getNewCallTimeStart($focusObjectCreateDate,$rowTaskCallDefs['start_delay_years'],$rowTaskCallDefs['start_delay_months'],$rowTaskCallDefs['start_delay_days'],$rowTaskCallDefs['start_delay_hours'],$rowTaskCallDefs['start_delay_minutes']);

				$newCallTaskId = $this->createNewCallTask($focusObjectId,$focusObjectType,$rowTaskCallDefs,$newCallTimeStart,$focusObjectCreateDate);
			
			//If this task is to be done when the previous task is complete then queue it up
			return $newCallTaskId;
		}
		
	}
	
//***************************************************************************
//This is the schedule task task that was waiting
//**************************************************************************
function runScheduleTaskTaskThatWasWaiting($focusObjectID,$focusObjectTypeCallLead,$processID,$stageID,$rowTask,$focusObjectId,$focusObjectType){
		//Go and get the defs record from pm_process_task_call_defs
		global $current_user;
		$taskId = $rowTask['id'];
		$queryTaskTaskDefs = "Select * from pm_process_task_task_defs where task_id = '" .$taskId ."'";
		$resultTaskTaskDefs = $this->db->query($queryTaskTaskDefs);
		$rowTaskTaskDefs = $this->db->fetchByAssoc($resultTaskTaskDefs);
		if ($rowTaskTaskDefs) {
			//Calculate the start time which is entered into the table as date_start and time_start
			//The edit view only allows times to show at 00,15,39,45 minutes after the hours
			//The field start_delay_type will hold the type of delay - from object creation or previous
			//task complete.
				//Get the create date from the function that gets focus fields
				$focusFieldsArray = array();
				$focusFieldsArray['date_modified'] = 'date_modified';
				//Now was the previous task a task or call? We need to know this to get the date modified
				
				if ($focusObjectTypeCallLead == 'calls') {
					$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectID,"calls",$focusFieldsArray);
					$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_modified'];
				}
				if ($focusObjectTypeCallLead == 'tasks'){
					$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectID,"tasks",$focusFieldsArray);
					$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_modified'];
				}
				
				//If the task start_delay_days =  0 then we use the focus object create date for the call 
				//date_start.
	
				$newCallTimeStart = $this->getNewCallTimeStart($focusObjectCreateDate,$rowTaskTaskDefs['due_date_delay_years'],$rowTaskTaskDefs['due_date_delay_months'],$rowTaskTaskDefs['due_date_delay_days'],$rowTaskTaskDefs['due_date_delay_hours'],$rowTaskTaskDefs['due_date_delay_minutes']);
				$newTaskTaskId = $this->createNewTaskTask($focusObjectId,$focusObjectType,$rowTaskTaskDefs,$newCallTimeStart);
							
			//If this task is to be done when the previous task is complete then queue it up
			return $newTaskTaskId;
		}
		
	}	
//***************************************************************************
//This is the create a new task.
//**************************************************************************
function runCreateTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType){
		//Go and get the defs record from pm_process_task_task_defs
		global $current_user;
		$taskId = $rowTask['id'];
		$taskOrder = $rowTask['task_order'];
		$queryTaskTaskDefs = "Select * from pm_process_task_task_defs where task_id = '" .$taskId ."'";
		$resultTaskTaskDefs = $this->db->query($queryTaskTaskDefs);
		$rowTaskTaskDefs = $this->db->fetchByAssoc($resultTaskTaskDefs);
		if ($rowTaskTaskDefs) {
			//Calculate the due datetime which is entered into the table as date_start and time_start
			//If there is no delay then there is no due date so just create the task
			//If this task is to be done when the previous task is complete then queue it up
			if ($rowTaskTaskDefs['due_date_delay_type'] == 'From Completion of Previous Task'){	
				$this->insertTaskIntoWaitingTable($processID,$stageID,$focusObjectId,$focusObjectType,$taskId,$taskOrder,'tasks');
			    return;
			}
			//Here means that it is a create or modify delay so go and get either date
			$delay_type = $rowTaskTaskDefs['due_date_delay_type'];
			$focusFieldsArray = array();
			if ($delay_type == "Create") {
				$focusFieldsArray['date_entered'] = 'date_entered';
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_entered'];
			}
			else{
				$focusFieldsArray['date_modified'] = 'date_modified';
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_modified'];
			}			
				//If the task start_delay_days =  0 then we use the focus object create date for the call 
				//date_start.
				$newCallTimeStart = $this->getNewCallTimeStart($focusObjectCreateDate,$rowTaskTaskDefs['due_date_delay_years'],$rowTaskTaskDefs['due_date_delay_months'],$rowTaskTaskDefs['due_date_delay_days'],$rowTaskTaskDefs['due_date_delay_hours'],$rowTaskTaskDefs['due_date_delay_minutes']);
				$this->createNewTaskTask($focusObjectId,$focusObjectType,$rowTaskTaskDefs,$newCallTimeStart);			
		}
		
	}
//***************************************************************************
//This is the create a new Project task.
//**************************************************************************
function runCreateProjectTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType){
		//Go and get the defs record from pm_process_task_task_defs
		global $current_user;
		$taskId = $rowTask['id'];
		$queryProjectTaskDefs = "Select * from pm_process_project_task_defs where project_id = '" .$taskId ."'";
		$resultProjectTaskDefs = $this->db->query($queryProjectTaskDefs);
		$rowProjectTaskDefs = $this->db->fetchByAssoc($resultProjectTaskDefs);
		$focusFieldsArray = array();
		if ($delay_type == "Create") {
				$focusFieldsArray['date_entered'] = 'date_entered';
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_entered'];
		}
		else{
				$focusFieldsArray['date_modified'] = 'date_modified';
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_modified'];
		}
		$newCallTimeStart = $this->getNewCallTimeStart($focusObjectCreateDate,0,0,$rowTaskTaskDefs['due_date_delay_days'],0,0);
		if ($rowProjectTaskDefs) {
				$this->createNewProjectTask($focusObjectId,$focusObjectType,$rowProjectTaskDefs,$newCallTimeStart);			
		}
		
}
//***************************************************************************
//This is the schedule call task
//**************************************************************************
function runScheduleCallTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType){
		//Go and get the defs record from pm_process_task_call_defs
		global $current_user;
		global $previousTaskId;
		$taskId = $rowTask['id'];
		$taskOrder = $rowTask['task_order'];
		$queryTaskCallDefs = "Select * from pm_process_task_call_defs where task_id = '" .$taskId ."'";
		$resultTaskCallDefs = $this->db->query($queryTaskCallDefs);
		$rowTaskCallDefs = $this->db->fetchByAssoc($resultTaskCallDefs);
		if ($rowTaskCallDefs) {
			//Calculate the start time which is entered into the table as date_start and time_start
			//The edit view only allows times to show at 00,15,39,45 minutes after the hours
			//The field start_delay_type will hold the type of delay - from object creation or previous
			//task complete.
			if($rowTaskCallDefs['start_delay_type'] == 'From Completion of Previous Task'){	
				$this->insertTaskIntoWaitingTable($processID,$stageID,$focusObjectId,$focusObjectType,$taskId,$taskOrder,'calls');
				return;
			}			
			//Get the delay type
			$focusFieldsArray = array();
			$delay_type = $rowTaskCallDefs['start_delay_type'];
			if ($rowTaskCallDefs['start_delay_type'] == 'Create') {
				$focusFieldsArray['date_entered'] = 'date_entered';
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_entered'];
			}
			else{
				$focusFieldsArray['date_modified'] = 'date_modified';
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_modified'];
			}			
				$newCallTimeStart = $this->getNewCallTimeStart($focusObjectCreateDate,$rowTaskCallDefs['start_delay_years'],$rowTaskCallDefs['start_delay_months'],$rowTaskCallDefs['start_delay_days'],$rowTaskCallDefs['start_delay_hours'],$rowTaskCallDefs['start_delay_minutes']);
				$this->createNewCallTask($focusObjectId,$focusObjectType,$rowTaskCallDefs,$newCallTimeStart,$focusObjectCreateDate);			
		}		
	}

//**************************************************************************
//This function inserts a task into the task waiting table/queue.
//This table is the pm_process_task_waiting_todo
//These are the tasks that are waiting to do something based on an event
//happening against an object.
//**************************************************************************

function insertTaskIntoWaitingTable($processID,$stageID,$focusObjectId,$focusObjectType,$taskId,$taskOrder,$taskType){
	global $previousTaskId;
	$newTaskWaitingTodoId = create_guid();
	$newTaskWaitingTodoQuery = "Insert into pm_process_task_waiting_todo set id = '" .$newTaskWaitingTodoId ."'";
	$newTaskWaitingTodoQuery .= ", object_id = '" .$focusObjectId ."'";
	$newTaskWaitingTodoQuery .= ", object_type = '" .$focusObjectType ."'";
	$newTaskWaitingTodoQuery .= ", task_id = '" .$taskId ."'";
	$newTaskWaitingTodoQuery .= ", process_id = '" .$processID ."'";
	$newTaskWaitingTodoQuery .= ", stage_id = '" .$stageID ."'";
	$newTaskWaitingTodoQuery .= ", waiting_on_id = '" .$previousTaskId ."'";
	$newTaskWaitingTodoQuery .= ", task_order = $taskOrder ";
	$newTaskWaitingTodoQuery .= ", task_type = '$taskType' ";
	$this->db->query($newTaskWaitingTodoQuery);
	
	
	
}

//***************************************************************************
//This function loads a new task into the task table
//***************************************************************************

function createNewTaskTask($focusObjectId,$focusObjectType,$rowTaskTaskDefs,$dueDate){
	global $previousTaskId;
	global $current_user;
	$current_user->name = "SugarCRM Administrator";
	$newTask = new Task();
	//Mods to fix date start issue
	$timedate=new TimeDate();
	$timezone = date('Z') / 3600;
	$timezone = substr($timezone,1);
	$today = date('Y-m-d H:i:s', time() + $timezone * 60 * 60);
	$newCallUserid = create_guid();
	if ($dueDate != '') {
		//First parse the start time
		$spaceLocation = strpos($dueDate," ");
		$taskDueDate = substr($dueDate,0,$spaceLocation);
		$taskDueTime = substr($dueDate,$spaceLocation);
	}
	else{
		$taskDueDate = "000-00-00";
		$taskDueTime = "00:00:00";
	}
	
	$dateDue = $timedate->to_display_date_time($dueDate);
	if ($focusObjectType == 'leads') {
		$focusObjectType = 'Leads';
	}
	
	if ($focusObjectType == 'opportunities') {
		$focusObjectType = 'Opportunities';
	}
	if ($focusObjectType == 'cases') {
		$focusObjectType = 'Cases';
	}
	if ($focusObjectType == 'project') {
		$focusObjectType = 'Project';
	}	
    //Check to see if this is a custom module
    $focusObjectType = $this->checkIfCustomModule($focusObjectType);
	
	$newTask->name = $rowTaskTaskDefs['task_subject'];
	$newTask->status = 'Not Started';
	$newTask->date_due_flag = 0;
	$newTask->date_due = $dateDue;
	$newTask->date_start_flag = 1;
	$newTask->priority = $rowTaskTaskDefs['task_priority'];
	//Are we a contact focus object?
	if ($focusObjectType == 'contacts') {
		$newTask->parent_type = 'Accounts';
		$newTask->contact_id = $focusObjectId;
	}
	elseif ($focusObjectType == 'accounts'){
		$newTask->parent_type = 'Accounts';
		$newTask->parent_id = $focusObjectId;
	}
	elseif ($focusObjectType == 'tasks'){
		$fieldsArray = array();
		$fieldsArray['parent_type'] = 'parent_type';
		$fieldsArray['parent_id'] = 'parent_id';
		$fieldsArray['contact_id'] = 'contact_id';
		$fieldsArray['is_pm_created_task'] = 'is_pm_created_task';
		$resultFields = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$fieldsArray);
		$newTask->parent_type = $resultFields['parent_type'];
		$newTask->parent_id = $resultFields['parent_id'];
		$newTask->contact_id = $resultFields['contact_id'];
		$isPMCreatedTask = $resultFields['is_pm_created_task'];
		if ($isPMCreatedTask == 1) {
			return;
		}
	}	
	else{
		$newTask->parent_type = $focusObjectType;
		$newTask->parent_id = $focusObjectId;
	}	

	$newTask->description = $rowTaskTaskDefs['task_description'];
	//Is the assigned_user_task_id set in the def record?
	if($rowTaskTaskDefs['assigned_user_id_task'] != ""){
		$assignedUserTaskId = $rowTaskTaskDefs['assigned_user_id_task'];
		//Now get the actual user id from the users table with the given name
		$assignedUserTaskId = $this->getUserIdByName($assignedUserTaskId);
		$newTask->assigned_user_id = $assignedUserTaskId;
		$newTask->modified_user_id = $assignedUserTaskId;
	}
	else{		
		$newTask->assigned_user_id = $current_user->id;
		$newTask->modified_user_id = $current_user->id;		
	}
	
	//Now save the task
	$newTask->save(TRUE);
	$newTaskid = $newTask->id;
	$previousTaskId = $newTaskid;
	//Begin Escalation Mods
	$is_escalatable = $rowTaskTaskDefs['is_escalatable_task'];
	$escalation_delay_minutes = $rowTaskTaskDefs['escalation_delay_minutes_task'];
	if ($escalation_delay_minutes == '') {
		$escalation_delay_minutes = 0;
	}
	$newTaskQuery = "Update tasks set is_escalatable_task ='" . $is_escalatable ."'";
	$newTaskQuery .= ", escalation_delay_minutes = '" . $escalation_delay_minutes ."'";
	$newTaskQuery .= ", is_pm_created_task = 1" ;
	$newTaskQuery .= ", initial_date_due = '" .$taskDueDate ."'";
	$newTaskQuery .= ", initial_time_due = '" .$taskDueTime ."'";
	$newTaskQuery .= " where id = '$newTaskid'";
	//End - Escalation
	$this->db->query($newTaskQuery);
	
	return $newTaskid;

}

//***************************************************************************
//This function loads a new task into the task table
//***************************************************************************

function createNewProjectTask($focusObjectId,$focusObjectType,$rowProjectTaskDefs,$dueDate){
	//global $previousTaskId;
	global $current_user;
	$current_user->name = "SugarCRM Administrator";
	$newProjectTask = new ProjectTask();
	
	//Date Function
	$timestamp = date('Y-m-d H:i:s', time());
	$startDelayDays = $rowProjectTaskDefs['project_task_start_date'];
	$endDelayDays = $rowProjectTaskDefs['project_task_end_date'];
	fwrite($fp, "Line " . __LINE__ . " of PM: Start Delay Days: $startDelayDays. \n");
	fwrite($fp, "Line " . __LINE__ . " of PM: End Delay Days: $endDelayDays \n");
	fwrite($fp, "Line " . __LINE__ . " of PM: Due Date: $dueDate \n");
	$startDate = $this->getNewDateTimeStartDelayedStage($dueDate,$startDelayDays, 0, 0, 0, 0);
	$endDate = $this->getNewDateTimeStartDelayedStage($dueDate,$endDelayDays, 0, 0, 0, 0);
	fwrite($fp, "Line " . __LINE__ . " of PM: startDate: $startDate \n");
	fwrite($fp, "Line " . __LINE__ . " of PM: endDate: $endDate \n");
	
	$timedate=new TimeDate();
	$dateDue = $timedate->to_display_date_time($dueDate);
	if ($focusObjectType == 'leads') {
		$focusObjectType = 'Leads';
	}
	
	if ($focusObjectType == 'opportunities') {
		$focusObjectType = 'Opportunities';
	}
	if ($focusObjectType == 'cases') {
		$focusObjectType = 'Cases';
	}
	if ($focusObjectType == 'project') {
		$focusObjectType = 'Project';
	}	
    //Check to see if this is a custom module
    $focusObjectType = $this->checkIfCustomModule($focusObjectType);
    
	$newProjectTask->id = $newCallUserid;
	$newProjectTask->name = $rowProjectTaskDefs['project_task_subject'];
	$newProjectTask->status = $rowProjectTaskDefs['project_task_status'];
	$newProjectTask->date_start = $startDate;
	$newProjectTask->date_finish = $endDate;
	$newProjectTask->priority = $rowProjectTaskDefs['project_task_priority'];
	$newProjectTask->project_task_id = $rowProjectTaskDefs['project_task_id'];
	$newProjectTask->project_id = $focusObjectId;	
	$newProjectTask->description = $rowTaskTaskDefs['project_task_description'];
	$idCheck = $this->checkProjectAlreadyCreated($focusObjectId);
	if($idCheck){
		$newProjectTask->date_entered = $timestamp;
	}else{
		$newProjectTask->date_modified = $timestamp;
	}
	
	//Is the assigned_user_task_id set in the def record?
	if($rowTaskTaskDefs['assigned_user_id_project_task'] != ""){
		$assignedUserTaskId = $rowProjectTaskDefs['assigned_user_id_project_task'];
		//Now get the actual user id from the users table with the given name
		$assignedUserTaskId = $this->getUserIdByName($assignedUserTaskId);
		$newProjectTask->assigned_user_name = $assignedUserTaskId;
	}
	else{		
		$newProjectTask->assigned_user_name = $current_user->id;	
	}
	
	//Now save the task
	$newProjectTask->save(TRUE);
	$newProjectTaskid = $newProjectTask->id;
	
	return $newProjectTaskid;

}

function checkProjectAlreadyCreated($focusObjectId){
	$queryProjectTaskID = "Select * from pm_process_project_task_defs where project_id = '" .$focusObjectId ."'";
	$result = $this->db->query($queryProjectTaskID);
	$row = $this->db->fetchByAssoc($result);
	if($row['id'] != ''){
		$status = true;
	}else{
	  	$status = false;
	}
	return $status;
}
//***************************************************************************
//This function is called by runScheduleCallTask to insert the new call data
//We pass the focus object and focus type, the row of call defs and also
//call time start.
//Iff the call is for a lead then we set parent type and parent id
//If the call is for a contact then we relate with calls_contacts table
//Also we parse the call start time to get the date and time 
//***************************************************************************

function createNewCallTask($focusObjectId,$focusObjectType,$rowTaskCallDefs,$newCallTimeStart,$focusObjectCreateDate){
	global $previousTaskId;
	global $current_user;
	//Mods to fix date start issue
	$timedate=new TimeDate();
	//
	$current_user->name = "SugarCRM Administrator";
	require_once('modules/Calls/Call.php');
	$newCall = new Call();
	$timezone = date('Z') / 3600;
	$timezone = substr($timezone,1);
	$today = date('Y-m-d H:i:s', time() + $timezone * 60 * 60);

	$newCallUserid = create_guid();
	$spaceLocation = strpos($newCallTimeStart," ");
	$callStartDate = substr($newCallTimeStart,0,$spaceLocation);
	$callStartTime = substr($newCallTimeStart,$spaceLocation);	

	//Format the y-m-d of the new call time start to reflect what the user would have used
	$dateStart = $timedate->to_display_date_time($newCallTimeStart);
	if ($focusObjectType == 'leads') {
		$focusObjectType = 'Leads';
	}
	if ($focusObjectType == 'opportunities') {
		$focusObjectType = 'Opportunities';
	}
	if ($focusObjectType == 'cases') {
		$focusObjectType = 'Cases';
	}
	if ($focusObjectType == 'accounts') {
		$focusObjectType = 'Accounts';
	}
	if ($focusObjectType == 'project') {
		$focusObjectType = 'Project';
	}

	//Custom Module Support
    $focusObjectType = $this->checkIfCustomModule($focusObjectType);
	//Is the assigned_user_call_id set in the def record?
	if($rowTaskCallDefs['assigned_user_id_call'] != ""){
		$assignedUserCallId = $rowTaskCallDefs['assigned_user_id_call'];
		$assignedUserCallId = $this->getUserIdByName($assignedUserCallId);
	}
	else{		
		$assignedUserCallId	= $current_user->id;
	}			
	//Build the Call object and Save
	$newCall->name = $rowTaskCallDefs['call_subject'];
	//Set the correct parent type
	$ii = 1;
	if ($focusObjectType == 'contacts') {
		$newCall->parent_type = 'Accounts';
	}elseif ($focusObjectType == 'calls'){
		$ii = $ii++;
		$fieldsArray = array();
		$fieldsArray['parent_type'] = 'parent_type';
		$fieldsArray['parent_id'] = 'parent_id';
		$fieldsArray['is_pm_created_call'] = 'is_pm_created_call';
		$resultFields = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$fieldsArray);
		$newCall->parent_type = $resultFields['parent_type'];
		$newCall->parent_id = $resultFields['parent_id'];
		$isPMCreatedCall = $resultFields['is_pm_created_call'];
		if ($isPMCreatedCall == 1 OR $ii > 1) {
			return;
		}
	}else{
	$newCall->parent_type = $focusObjectType;
	}
	$newCall->reminder_time = $rowTaskCallDefs['reminder_time'];
	$newCall->description = $rowTaskCallDefs['call_description'];
	$newCall->duration_hours = 0;
	$newCall->duration_minutes = 15;
	$newCall->direction = "Inbound";
	$newCall->assigned_user_id = $assignedUserCallId;
	$newCall->notify_inworkflow = true;
	$newCall->date_start = $dateStart;
	//Escalation
	$newCall->save(TRUE);
	$newCallId = $newCall->id;
	$previousTaskId = $newCall->id;
	//If the focus object is a call then we need to insert an entry in calls_contacts
	//We also need to go and get the account related to the contact
	//This used in the call insert: parent_type = Accounts and parent_id is account id
	if ($focusObjectType == 'contacts') {
		$focusObjectType = 'Accounts';
		$this->loadCallsContacts($focusObjectId,$newCallId,$today);
		//Now go and get the account for the contact
		$accountContactId = $this->getAccountContactId($focusObjectId);
		$focusObjectId = $accountContactId;	
	}
	//Begin Escalation Mods
	$is_escalatable = $rowTaskCallDefs['is_escalatable_call'];
	$escalation_delay_minutes = $rowTaskCallDefs['escalation_delay_minutes_call'];
	if ($escalation_delay_minutes == '') {
		$escalation_delay_minutes = 0;
	}

	//Now update the call with the non baseline fields that are new for Process Manager
	$newCallQuery = "Update calls set parent_id = '$focusObjectId', ";
	$newCallQuery .= " is_escalatable_call = $is_escalatable, escalation_delay_minutes = '$escalation_delay_minutes',";
	$newCallQuery .= " is_pm_created_call = 1, initial_date_start = '$callStartDate', initial_time_start = '$callStartTime',";
	$newCallQuery .= " assigned_user_id = '$assignedUserCallId', modified_user_id = '$assignedUserCallId' where id = '$newCallId'";
	$this->db->query($newCallQuery);
	
	//Now insert into calls_user
	$newCallUserQuery = "Insert into calls_users set id = '" .$newCallUserid ."'";
	$newCallUserQuery .= ", call_id = '" .$newCallId ."', user_id = '$assignedUserCallId'";
	$newCallUserQuery .= ", required = 1";
	$newCallUserQuery .= ", accept_status = 'accept'";
	$newCallUserQuery .= ", date_modified = '" .$today ."'";
	$newCallUserQuery .= ", deleted = 0";
	$this->db->query($newCallUserQuery);
	//Finally for Sugar 5.1 - new link table called calls_leads
	if ($focusObjectType == 'Leads') {
		$newCallLeadQuery = "Insert into calls_leads set id = '" .$newCallUserid ."'";
		$newCallLeadQuery .= ", call_id = '" .$newCallId ."', lead_id = '$focusObjectId'";
		$newCallLeadQuery .= ", required = 1";
		$newCallLeadQuery .= ", accept_status = 'accept'";
		$newCallLeadQuery .= ", date_modified = '" .$today ."'";
		$newCallLeadQuery .= ", deleted = 0";
		$this->db->query($newCallLeadQuery);
	}		
	return $newCall->id;
}

//BEGIN CUSTOM MODULE FUNCTIONS
//***************************************************************************
//Custom Module - Function to determine if this is a custom module
//If so then return the focus object type to be the module
//**************************************************************************

function checkIfCustomModule($focusObjectType){
		//Custom Module Support
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
 			if ($focusObjectType == $customModuleTable) {
 				$focusObjectType = $customModule->object_name;
 			}
 			}
    	}		
	}
	
	return $focusObjectType;
}



//*************************************************************************
//Custom Module - Check if Custom Module
//*************************************************************************

function isCustomModule($focusObjectType){
	//Custom Module Support
	//SierraCRM Exemption Array
	$isCustomModule = false;
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
 			if ($focusObjectType == $customModuleTable) {
 				$isCustomModule = true;
 			}
 			}
    	}		
	}
	return $isCustomModule;
}

//*************************************************************************
//Custom Module - This function checks to see if there is a meta data	
//***************************************************************************

function checkCustomModuleRelationship($relationshipArray){
	$file = $relationshipArray['file'];
	$relationship = $relationshipArray['relationship'];
	if (file_exists("custom/metadata/$file"))
	{
		//Open the file
		include("custom/metadata/$file");
   		foreach ($dictionary as $key=>$value){
   			$relationshipArrayInfo = $dictionary[$relationship]['relationships'][$relationship];
			return $relationshipArrayInfo;
   		}
	}
	
}

//This function returns the id of the related contact or account
function getLinkTableId($joinTable,$contactAccountJoinTableColumn,$customModuleJoinTableColumn,$focusObjectId){
		$query .= " select $contactAccountJoinTableColumn from $joinTable where $customModuleJoinTableColumn = '$focusObjectId' and deleted = 0";
		$resultContactAccountId = $this->db->query($query);
		$rowContactAccountId = $this->db->fetchByAssoc($resultContactAccountId);
		$contactAccountId = $rowContactAccountId[$contactAccountJoinTableColumn];
		return $contactAccountId;
}

//END CUSTOM MODULE FUNCTIONS
//****************************************************************************
	
//***************************************************************************
//This function gets a custom field from the objects cstm field table
//**************************************************************************
function getFocusObjectCustomFields($focusObjectId,$focusObjectType,$focusFieldsArray){
	//The focusObjectType holds the object like lead, contacts, etc.
	$table = $focusObjectType;
	$table .= '_cstm';	
	$counter = 1;
	$query = "Select ";
		//Get the count of array fields so we know when to not add the ,
		$countOfArrayElements = count($focusFieldsArray);		
		foreach($focusFieldsArray as $field)
			{				
				// Copy the relevant fields
				$fieldName = $field;				
				$query .= $fieldName;
				if ($counter < $countOfArrayElements) {
					$query .= " ,";	
				}	
				$counter ++;
			}
		$query .= " from " .$table ." where id_c = '" .$focusObjectId ."'";
		$resultFieldValues = $this->db->query($query);
		$rowFieldValues = $this->db->fetchByAssoc($resultFieldValues);
		//Now build the array with the values and send back
		foreach($focusFieldsArray as $field)
			{
				// Copy the relevant fields
				$fieldName = $field;
				$fieldValue = $rowFieldValues[$fieldName];
				$focusFieldsArray[$fieldName] = $fieldValue;
			}
		return $focusFieldsArray;	 	
}
//****************************************************************************
//This is a generic function that is passed an array of fields and returns
//the values of the fields.
//***************************************************************************

function getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray){
	
	//Here we check to first see if the 
	
	$counter = 1;
	$query = "Select ";
		//Get the count of array fields so we know when to not add the ,
		$countOfArrayElements = count($focusFieldsArray);
		
		foreach($focusFieldsArray as $field)
			{
				
				// Copy the relevant fields
				$fieldName = $field;
				
				$query .= $fieldName;
				if ($counter < $countOfArrayElements) {
					$query .= " ,";	
				}	
				$counter ++;

			}
		$query .= " from " .$focusObjectType ." where id = '" .$focusObjectId ."'";
		$resultFieldValues = $this->db->query($query);
		$rowFieldValues = $this->db->fetchByAssoc($resultFieldValues);
		//Now build the array with the values and send back

		foreach($focusFieldsArray as $field)
			{
				// Copy the relevant fields
				$fieldName = $field;
				$fieldValue = $rowFieldValues[$fieldName];
				$focusFieldsArray[$fieldName] = $fieldValue;

			}
		return $focusFieldsArray;
	 
	
}
	
//****************************************************************************
//											
//***********************--- TIME FUNCTIONS --********************************
//
//****************************************************************************	
	
function getNewCallTimeStart($focusObjectCreateDate,$startDelayYears,$startDelayMonths,$startDelayDays,$startDelayHours,$startDelayMinutes){

	//First thing to do is to see if the delay is in days - if so add that many days to 
		
	//Date Entered for Focus Object in this format - 2005-11-23 18:34:00
	$timedate = new TimeDate();
	$timezone = date('Z') / 3600;	
	//So we know the users timezone - and this value is a value like -8
	//So remove the - and get the offset.
	$timezone = substr($timezone,1);	
	$focusObjectCreateDateNew = date($focusObjectCreateDate, time() - 8 * 60 * 60);	 
	list ($year, $month, $day, $hour, $min, $sec) = split ('[- :]', $focusObjectCreateDate);	
	if ($startDelayYears != 0) {
		$year = $year + $startDelayYears;
	}	
	if ($startDelayMonths != 0) {
		$month = $month + $startDelayMonths;
	}	
	if ($startDelayDays != 0) {
		$day = $day + $startDelayDays;
	}
	if ($startDelayHours != 0) {
		$hour = $hour + $startDelayHours;
	}
	
	if ($startDelayMinutes != 0) {
		$min = $min + $startDelayMinutes;
	}
	//Make seconds = 00
	$sec = '00';
	$callStartDateWhole = date("Y-m-d H:i:s",mktime ($hour, $min, $sec, $month, $day, $year));
	return $callStartDateWhole;	
}

//****************************************************************************
//											
//***********************--- TIME FUNCTIONS --********************************
//
//****************************************************************************	
	
function getNewCallTimeStartDelayedStage($focusObjectCreateDate,$startDelayDays,$startDelayHours,$startDelayMinutes,$startDelayMonths,$startDelayYears){

	//First thing to do is to see if the delay is in days - if so add that many days to 
	//Create Date
	if ($startDelayDays != 0) {
		$hour = $hour + $startDelayHours;
	}
	
	
	//Date Entered for Focus Object in this format - 2005-11-23 18:34:00
	$timedate = new TimeDate();
	$user = new User();
	$timezone = date('Z') / 3600;
	
	//So we know the users timezone - and this value is a value like -8
	//So remove the - and get the offset.
	$timezone = substr($timezone,1);
	
	$focusObjectCreateDateNew = date($focusObjectCreateDate, time() - 8 * 60 * 60);

	$year = 0;
	$hour = 0;
	$min = 0;
	$sec = 0;
	$month = 0;
	$day = 0;
	
	list ($year, $month, $day, $hour, $min, $sec) = split ('[- :]', $focusObjectCreateDate);
	
	if ($startDelayDays != 0) {
		$day = $day + $startDelayDays;
	}
	if ($startDelayHours != 0) {
		$hour = $hour + $startDelayHours;
	}
	
	if ($startDelayMinutes != 0) {
		$min = $min + $startDelayMinutes;
	}
	if ($startDelayMonths != 0) {
		$month = $month + $startDelayMonths;
	}
	if ($startDelayYears != 0) {
		$year = $year + $startDelayYears;
	}
	//Make seconds = 00
	$sec = '00';
	$callStartDateWhole = date("Y-m-d H:i:s",mktime ($hour, $min, $sec, $month, $day, $year));	
	return $callStartDateWhole;	
}

function getNewDateTimeStartDelayedStage($focusObjectCreateDate,$startDelayDays,$startDelayHours,$startDelayMinutes,$startDelayMonths,$startDelayYears){

	//First thing to do is to see if the delay is in days - if so add that many days to 
	//Create Date
	if ($startDelayDays != 0) {
		$hour = $hour + $startDelayHours;
	}
	
	
	//Date Entered for Focus Object in this format - 2005-11-23 18:34:00
	$timedate = new TimeDate();
	$user = new User();
	$timezone = date('Z') / 3600;
	
	//So we know the users timezone - and this value is a value like -8
	//So remove the - and get the offset.
	$timezone = substr($timezone,1);
	
	$focusObjectCreateDateNew = date($focusObjectCreateDate, time() - 8 * 60 * 60);

	$year = 0;
	$hour = 0;
	$min = 0;
	$sec = 0;
	$month = 0;
	$day = 0;
	
	list ($year, $month, $day, $hour, $min, $sec) = split ('[- :]', $focusObjectCreateDate);
	
	if ($startDelayDays != 0) {
		$day = $day + $startDelayDays;
	}
	if ($startDelayHours != 0) {
		$hour = $hour + $startDelayHours;
	}
	
	if ($startDelayMinutes != 0) {
		$min = $min + $startDelayMinutes;
	}
	if ($startDelayMonths != 0) {
		$month = $month + $startDelayMonths;
	}
	if ($startDelayYears != 0) {
		$year = $year + $startDelayYears;
	}
	//Make seconds = 00
	$sec = '00';
	$callStartDateWhole = date("Y-m-d",mktime ($hour, $min, $sec, $month, $day, $year));	
	return $callStartDateWhole;	
}

//*************************************************************************
//This function will get the contacts related account id and return 
//For when the task is a call

function getAccountContactId($focusObjectId){
	
	$queryAccountContacts = "Select account_id from accounts_contacts where contact_id = '" .$focusObjectId ."'";
	$result = $this->db->query($queryAccountContacts);
	$row = $this->db->fetchByAssoc($result);
	if ($row) {
		$accountId = $row['account_id'];
	}
	return $accountId;
	
}

//****************************************************************************
//This function is called to run an email task and is passed the task id
//object id and object type
//***************************************************************************

function runEmailTask($taskId,$focusObjectId,$focusObjectType){
	require_once('modules/Users/User.php');
	global $current_user;
	$current_user = new User();
	$rowUser = $this->getFocusOwner($focusObjectType,$focusObjectId);	
	//For version 4.5.1 we get the user preferences from the user_preference table
	$userId = $rowUser['id'];
	$rowUserPreferenceContents = $this->getUserPreferenceRow($userId);
	$current_user->id = $rowUser['id'];
	$current_user->user_name = $rowUser['user_name'];
	$user_name = $rowUser['user_name'];
	$_SESSION[$current_user->user_name . '_PREFERENCES']['global'] = unserialize(base64_decode($rowUserPreferenceContents));				
	$current_user->user_preferences['global'] = unserialize(base64_decode($rowUserPreferenceContents));
	$sendEmailToOppAccount = 0;
	//**********************************************************************
	//Set the session to run the email programs 
	//Time zone needed for email templates
	//**********************************************************************
       //$_SESSION[$current_user->user_name.'_PREFERENCES']['global']['timezone'] = 'America/Los_Angeles';
	require_once('modules/Emails/Email.php');
	require_once('modules/EmailTemplates/EmailTemplate.php');
	if (isset($GLOBALS['beanList']) && isset($GLOBALS['beanFiles'])) {
				global $beanFiles;
				global $beanList;
			} else {
				require_once('include/modules.php');
			}
	$new_email = new Email();
	$emailTemplate = new EmailTemplate();
	//Get contact or lead data depending on what the focus object type is:
	$queryEmailDefsTable = "Select * from pm_process_task_email_defs where task_id = '" .$taskId ."'";

	$result = $this->db->query($queryEmailDefsTable);
	$row = $this->db->fetchByAssoc($result);

	if ($row) {
		//Check to see if this is an internal email - is so then skip the rest 
		if ($row['internal_email'] == 1) {
			$to_address = $row['internal_email_to_address'];
		}
		elseif ($row['send_email_to_object_owner'] == 1){
			//Get id of assigned user 
			$rowUser = $this->getFocusOwner($focusObjectType,$focusObjectId);	
			$userId = $rowUser['id'];
			$rowEmailAddressOptOut = $this->getEmailAddress($userId,"Users");
			$to_address = $rowEmailAddressOptOut['email_address'];
			$emailOptOut = $rowEmailAddressOptOut['opt_out'];
			$focusObjectIdContactOpp = $focusObjectId;
				if ($to_address == "") {
						return;
				}
				if ($emailOptOut != "0") {
						return;
				}
		}
		else{
			if ($focusObjectType == "contacts") {
				$rowEmailAddressOptOut = $this->getEmailAddress($focusObjectId,$focusObjectType);
				$to_address = $rowEmailAddressOptOut['email_address'];
				$emailOptOut = $rowEmailAddressOptOut['opt_out'];
				$focusObjectIdContactOpp = $focusObjectId;
				if ($to_address == "") {
						return;
				}
				if ($emailOptOut != "0") {
						return;
				}
			}
			
			//For Opportunities go and see if there is a related contact to the opportunity
			//Must have a releated contact and an email to send an email
			if ($focusObjectType == "opportunities") {	
				$sendEmailToOppAccount = $row['send_email_to_caseopp_account'];
				if ($sendEmailToOppAccount == 1) {
						$rowAccountOpp = $this->getAccountOppEmails($focusObjectId,$focusObjectType,$contact_role);
						$focusObjectIdAccount = $rowAccountOpp['id'];
						$rowEmailAddressOptOut = $this->getEmailAddress($focusObjectIdAccount,"accounts"); 
						$focusObjectType = 'accounts';
						$opportunityID = $focusObjectId; 			
					}
				else{					
						$contact_role = $row['contact_role'];
						//if ($contact_role != "") {
						$rowContactOpp = $this->getContactOppEmails($focusObjectId,$focusObjectType,$contact_role);
						$focusObjectType = 'contacts';
						$focusObjectIdContact = $rowContactOpp['id']; 
						$rowEmailAddressOptOut = $this->getEmailAddress($focusObjectIdContact,"contacts"); 
				}
					$to_address = $rowEmailAddressOptOut['email_address'];
					$emailOptOut = $rowEmailAddressOptOut['opt_out'];
					if ($to_address == "") {
							return;
					}
					if ($emailOptOut != "0") {
							return;
					}
				$focusObjectType = 'opportunities';			
				}
						
			//}
			//Get the Leads email address
			if ($focusObjectType == "leads") {
				$rowEmailAddressOptOut = $this->getEmailAddress($focusObjectId,$focusObjectType);
				$to_address = $rowEmailAddressOptOut['email_address'];
				$emailOptOut = $rowEmailAddressOptOut['opt_out'];
				if ($to_address == "") {
						return;
				}
				if ($emailOptOut != "0") {
						return;
				}
			}
			//Get the accounts email address
			if ($focusObjectType == "accounts") {

				$rowEmailAddressOptOut = $this->getEmailAddress($focusObjectId,$focusObjectType);
				$to_address = $rowEmailAddressOptOut['email_address'];
					$focusObjectIdContactOpp = $focusObjectId;
				if ($to_address == "") {
						return;
				}
			}
			//For cases - get the email address for the related account			
			if ($focusObjectType == "cases") {			
				$case_id = 	$focusObjectId;				
				$rowAccountCases = $this->getAccountEmailForCases($focusObjectId,$focusObjectType);
				$focusObjectType = 'accounts';
				$focusObjectId = $rowAccountCases['account_id'];
				$rowEmailAddress = $this->getEmailAddress($focusObjectId,$focusObjectType);
				$to_address = $rowEmailAddress['email_address'];
				$emailOptOut = $rowEmailAddress['opt_out'];
					if ($to_address == "") {
						return;
					}
				if ($emailOptOut != "0") {
					return;
				}
			$focusObjectId = $case_id;				
			$focusObjectType = 'cases';
			}
			/*******************************************************************/
			//If Task then send email :: WORK DONE BY PETER DEMARTINI --3/3/2011
			/*******************************************************************/
			if($focusObjectType == "task"){
				$getTaskSQL = "SELECT * FROM tasks WHERE id = '{$focusObjectId}'";
				$taskQuery = $this->db->query($getTaskSQL);
				$row_task_query = $this->db->fetchByAssoc($taskQuery);
				
				if(isset($row_task_query['contact_id'])){
					$getContactEmailSQL = "SELECT * FROM email_addresses WHERE id = '". $row_task_query['contact_id'] . "', opt_out = '0'";
					$contactEmailQuery = $this->db->query($getContactEmailSQL);
					$row_contact_email_query = $this->db->fetchByAssoc($contactEmailQuery);	
					$to_address = $row_contact_email_query['email_address'];
				}
				if($row_task_query['parent_type'] == 'Accounts' || $row_task_query['parent_type'] == 'Leads' ){
					$taskParentID = $row_task_query['parent_id'];
					if($row_task_query['parent_type'] == 'Accounts'){
						$getAccountEmailSQL = "SELECT * FROM email_addresses, accounts WHERE email_addresses.id = accounts.contact_id , email_addresses.opt_out = '0'";
						$accountEmailQuery = $this->db->query($getAccountEmailSQL);
						$row_account_email_query = $this->db->fetchByAssoc($accountEmailQuery);	
						$to_address = $row_account_email_query['email_address'];
					}elseif($row_task_query['parent_type'] == 'Leads'){
						$getLeadsEmailSQL = "SELECT * FROM email_addresses, leads WHERE email_addresses.id = leads.contact_id , email_addresses.opt_out = '0'";
						$leadsEmailQuery = $this->db->query($getAccountEmailSQL);
						$row_leads_email_query = $this->db->fetchByAssoc($accountEmailQuery);	
						$to_address = $row_leads_email_query['email_address'];
					}
				}
			}
			
			//***************************************************************************
			//Custom Module support for Emails
			//Check to see if this is a custom module and if so then first
			//Check to see if there is a relationship to the contacts - if so get that email
			//Otherwise look for relationship to Accounts and get that.
			//***************************************************************************
			if ($this->isCustomModule($focusObjectType)) {
				//See if the custom module is related to the contact module
				//Get the Module name for the custom module to set the parent type in the email bean
				$foundEmailAddress = false;
				$customModuleName = $this->checkIfCustomModule($focusObjectType);	
				$customModuleId = $focusObjectId;
					$relationshipArray = array();
				$file = $focusObjectType ."_contactsMetaData.php";
				$relationship = $focusObjectType ."_contacts";
				$relationshipArray['file']=$file;
				$relationshipArray['relationship']=$relationship;
				$relationshipArrayInfo = $this->checkCustomModuleRelationship($relationshipArray);
					if (array_key_exists('join_table',$relationshipArrayInfo)) {
						//Get the Join Table and the key and find the related contact
						//An example is the focusobject type would be at_autos
						foreach ($relationshipArrayInfo as $key=>$value) {
							if ($value == $focusObjectType) {
								if ($key == 'lhs_table') {
									$customModuleJoinTableColumn = $relationshipArrayInfo['join_key_lhs'];
									$contactAccountJoinTableColumn = $relationshipArrayInfo['join_key_rhs'];
								}
								else{
									$customModuleJoinTableColumn = $relationshipArrayInfo['join_key_rhs'];
									$contactAccountJoinTableColumn = $relationshipArrayInfo['join_key_lhs'];
								}
							}
						}
						//Now that we have the join table and correct keys go and get the contact id
						$joinTable = $relationshipArrayInfo['join_table'];
						$contactAccountId = $this->getLinkTableId($joinTable,$contactAccountJoinTableColumn,$customModuleJoinTableColumn,$focusObjectId);
						$rowEmailAddressOptOut = $this->getEmailAddress($contactAccountId,'contacts');
						$to_address = $rowEmailAddressOptOut['email_address'];
						$emailOptOut = $rowEmailAddressOptOut['opt_out'];
						$focusObjectId = $contactAccountId;				
						$focusObjectType = "contacts";						
						if ($to_address == "") {
							return;
						}
						if ($emailOptOut != "0") {
							return;
						}
						$foundEmailAddress = true;
					}
					//If we have not found the Email address then try reversing the way we look up the contact to custom module
					if ($foundEmailAddress == false) {
						//Possible reverse of the custom module relationship to accounts
						//$file = $focusObjectType ."_accountsMetaData.php";
						$file = "contacts_" .$focusObjectType ."MetaData.php";
						//$relationship = $focusObjectType ."_accounts";
						$relationship = "contacts_" .$focusObjectType;
						$relationshipArray['file']=$file;
						$relationshipArray['relationship']=$relationship;
						$relationshipArrayInfo = $this->checkCustomModuleRelationship($relationshipArray);
						if (array_key_exists('join_table',$relationshipArrayInfo)) {
							//Get the Join Table and the key and find the related contact
							//An example is the focusobject type would be at_autos
							foreach ($relationshipArrayInfo as $key=>$value) {
								if ($value == $focusObjectType) {
									if ($key == 'lhs_table') {
										$customModuleJoinTableColumn = $relationshipArrayInfo['join_key_lhs'];
										$contactAccountJoinTableColumn = $relationshipArrayInfo['join_key_rhs'];
									}
									else{
										$customModuleJoinTableColumn = $relationshipArrayInfo['join_key_rhs'];
										$contactAccountJoinTableColumn = $relationshipArrayInfo['join_key_lhs'];

									}
					}
							}
							//Now that we have the join table and correct keys go and get the contact id
							$joinTable = $relationshipArrayInfo['join_table'];
							$contactAccountId = $this->getLinkTableId($joinTable,$contactAccountJoinTableColumn,$customModuleJoinTableColumn,$focusObjectId);
							$rowEmailAddressOptOut = $this->getEmailAddress($contactAccountId,'contacts');
							$to_address = $rowEmailAddressOptOut['email_address'];
							$emailOptOut = $rowEmailAddressOptOut['opt_out'];
							$focusObjectId = $contactAccountId;				
							$focusObjectType = "contacts";						
							if ($to_address == "") {
								return;
							}
							if ($emailOptOut != "0") {
								return;
							}
							$foundEmailAddress = true;
						}
					}
					//If we still have not found the email address then see if the relationship is for custom module to accounts
					//NOW TRY ACCOUNTS SINCE NO CONTACTS
					if($foundEmailAddress == false){
						$customModuleName = $this->checkIfCustomModule($focusObjectType);	
						$customModuleId = $focusObjectId;
						$relationshipArray = array();
						$file = $focusObjectType ."_accountsMetaData.php";
						$relationship = $focusObjectType ."_accounts";
						$relationshipArray['file']=$file;
						$relationshipArray['relationship']=$relationship;
						$relationshipArrayInfo = $this->checkCustomModuleRelationship($relationshipArray);
							if (array_key_exists('join_table',$relationshipArrayInfo)) {
								//Get the Join Table and the key and find the related contact
								//An example is the focusobject type would be at_autos
								foreach ($relationshipArrayInfo as $key=>$value) {
									if ($value == $focusObjectType) {
										if ($key == 'lhs_table') {
											$customModuleJoinTableColumn = $relationshipArrayInfo['join_key_lhs'];
											$contactAccountJoinTableColumn = $relationshipArrayInfo['join_key_rhs'];
										}
										else{
											$customModuleJoinTableColumn = $relationshipArrayInfo['join_key_rhs'];
											$contactAccountJoinTableColumn = $relationshipArrayInfo['join_key_lhs'];
										}
									}
								}
								//Now that we have the join table and correct keys go and get the contact id
								$joinTable = $relationshipArrayInfo['join_table'];
								$contactAccountId = $this->getLinkTableId($joinTable,$contactAccountJoinTableColumn,$customModuleJoinTableColumn,$focusObjectId);
								$rowEmailAddressOptOut = $this->getEmailAddress($contactAccountId,"accounts");
								$to_address = $rowEmailAddressOptOut['email_address'];
								$emailOptOut = $rowEmailAddressOptOut['opt_out'];
								if ($to_address == "") {
										return;
								}
								if ($emailOptOut != "0") {
									return;
								}
								$focusObjectId = $contactAccountId;				
								$focusObjectType = "accounts";	
							}						
					       else{									                 	
					       		//Possible reverse of the custom module relationship to accounts
									//$file = $focusObjectType ."_accountsMetaData.php";
									$file = "accounts_" .$focusObjectType ."MetaData.php";
									//$relationship = $focusObjectType ."_accounts";
									$relationship = "accounts_" .$focusObjectType;
									$relationshipArray['file']=$file;
									$relationshipArray['relationship']=$relationship;
									$relationshipArrayInfo = $this->checkCustomModuleRelationship($relationshipArray);
									if (array_key_exists('join_table',$relationshipArrayInfo)) {
										//Get the Join Table and the key and find the related contact
										//An example is the focusobject type would be at_autos
										foreach ($relationshipArrayInfo as $key=>$value) {
											if ($value == $focusObjectType) {
												if ($key == 'lhs_table') {
													$customModuleJoinTableColumn = $relationshipArrayInfo['join_key_lhs'];
													$contactAccountJoinTableColumn = $relationshipArrayInfo['join_key_rhs'];
												}
												else{
													$customModuleJoinTableColumn = $relationshipArrayInfo['join_key_rhs'];
													$contactAccountJoinTableColumn = $relationshipArrayInfo['join_key_lhs'];

												}
											}
										}
										//Now that we have the join table and correct keys go and get the contact id
										$joinTable = $relationshipArrayInfo['join_table'];
										$contactAccountId = $this->getLinkTableId($joinTable,$contactAccountJoinTableColumn,$customModuleJoinTableColumn,$focusObjectId);
										$rowEmailAddressOptOut = $this->getEmailAddress($contactAccountId,"accounts");
										$to_address = $rowEmailAddressOptOut['email_address'];
										$emailOptOut = $rowEmailAddressOptOut['opt_out'];
										if ($to_address == "") {
												return;
										}
										if ($emailOptOut != "0") {
											return;
										}
										$focusObjectId = $contactAccountId;				
										$focusObjectType = "accounts";
								  }
					       }
					}
				
			}
			//END CUSTOM MODULE SUPPORT
			//***************************************************************************************
		}	
	
	$email_template_id = $row['email_template_id'];
	$query_template = "SELECT * from email_templates where id = ";
	$query_template .= "'";
	$query_template .=  $email_template_id;
	$query_template .= "'";
	$result_email_template = $this->db->query($query_template);
	$row_email_template = $this->db->fetchByAssoc($result_email_template);
		//Get the row from the email template table
		//$row_email_template = getEmailTemplate($email_template_id,$this);
		//$row_email_template = $this->db->fetchByAssoc($email_template);
		//Are we html or text email?
		if ($row_email_template["body_html"] != "") {
			$email_template_body = $row_email_template["body_html"];
			$email_type = 'HTML';
		}
		else{
			$email_template_body = $row_email_template["body"];
		}
		$email_template_subject = $row_email_template["subject"];
		if ($focusObjectType == 'leads') {
			$object_arr_leads = array();
			$object_arr_leads['Leads'] = $focusObjectId;	 
			$email_template_body = $emailTemplate->parse_template($email_template_body,$object_arr_leads);
			$email_template_subject = $emailTemplate->parse_template($email_template_subject,$object_arr_leads);
			//Now clear the array
			$object_arr_leads['Leads'] = null;
			$lead_id = $focusObjectId;
		}
		if ($focusObjectType == 'accounts') {
			$object_arr_leads = array();
			$object_arr_leads['Accounts'] = $focusObjectId;	 
			$email_template_body = $emailTemplate->parse_template($email_template_body,$object_arr_leads);
			$email_template_subject = $emailTemplate->parse_template($email_template_subject,$object_arr_leads);
			//Now clear the array
			$object_arr_leads['Accounts'] = null;
			$account_id = $focusObjectId;
		}
		if ($focusObjectType == 'contacts') {
			$object_arr_leads = array();
				$object_arr_leads['Contacts'] = $focusObjectId;
				$email_template_body = $emailTemplate->parse_template($email_template_body,$object_arr_leads);
				$email_template_subject = $emailTemplate->parse_template($email_template_subject,$object_arr_leads);
				//Now clear the array
				$object_arr_leads['Contacts'] = null;
				$contact_id = $focusObjectId;		
		}
		
		//Patch for 7/14/2009 - fix issue for getting the email template data for a focus object of type opportunities
		if ($focusObjectType == 'opportunities') {
			$object_arr_leads = array();
				if ($sendEmailToOppAccount == 1) {
					$object_arr_leads['Accounts'] = $focusObjectIdAccount;
					$email_template_body = $emailTemplate->parse_template($email_template_body,$object_arr_leads);
					$email_template_subject = $emailTemplate->parse_template($email_template_subject,$object_arr_leads);
					//Now clear the array
					$object_arr_leads['Accounts'] = null;
					$account_id = $focusObjectIdAccount;
				}
				else{
					$object_arr_leads['Contacts'] = $focusObjectIdContact;
					$email_template_body = $emailTemplate->parse_template($email_template_body,$object_arr_leads);
					$email_template_subject = $emailTemplate->parse_template($email_template_subject,$object_arr_leads);
					//Now clear the array
					$object_arr_leads['Contacts'] = null;
					$contact_id = $focusObjectIdContact;
				}		
		}
		//End Patch for 7/14/2009		
		//Last item to parse is the user so go and get the user object
			$object_arr_leads = array();			
			$object_arr_leads['Users'] = $current_user->id;
			
			$email_template_body = $emailTemplate->parse_template($email_template_body,$object_arr_leads);
			$email_template_subject = $emailTemplate->parse_template($email_template_subject,$object_arr_leads);
			//Now clear the array
			
			
		
		
		//Now we have the parsed email body:
			//Get the email address of the contact or lead
			
			$new_email->cc_addrs_arr = array();
			$new_email->bcc_addrs_arr = array();
			$new_email->to_addrs = $to_address;
			$new_email->to_addrs_arr = $new_email->parse_addrs($to_address, $_REQUEST['to_addrs_ids'], $_REQUEST['to_addrs_names'], $_REQUEST['to_addrs_emails']);
			$new_email->name = $email_template_subject;
			$new_email->type = 'out';
			if ($focusObjectType == 'leads') {
				$new_email->parent_type = "Leads";
				$new_email->parent_id = $focusObjectId;
			}
			elseif ($focusObjectType == 'cases'){
				$new_email->parent_type = "Cases";
				$new_email->parent_id = $focusObjectId;
			}
			elseif ($focusObjectType == 'opportunities'){
				$new_email->parent_type = "Opportunities";
				$new_email->parent_id = $focusObjectId;
			}
			//Custom Module Support for Parent ID and Parent Type
			elseif ($customModuleName != ''){
				$new_email->parent_type = $customModuleName;
				$new_email->parent_id = $customModuleId;
				$focusObjectType = $customModuleName;
				$focusObjectId = $customModuleId;
			}			
			else{
				$new_email->parent_type = "Accounts";
				$new_email->parent_id = $focusObjectId;
			}
			
			//Custom Module Support
			//Are we html?
			if ($email_type == "HTML")
			{
				$new_email->description_html = $email_template_body;
				$new_email->isHtml = true;	
			}
			else{
				$new_email->description = $email_template_body;
				$new_email->isHtml = false;	
			}
			//Support for Sending Email to Opportunity Account
			if ($sendEmailToOppAccount == 1){
				$new_email->parent_type = "Opportunities";
				$new_email->parent_id = $opportunityID;
				$focusObjectType = "Opportunities";
				$focusObjectId = $opportunityID;
			}
			//The from account is the id from the table inbound email - so query this table with the current_user id
			//Map the focus object type so that we can properly create the email_bean record
			if ($focusObjectType == 'leads') {
				$focusObjectType = "Leads";
			}
			if ($focusObjectType == 'contacts') {
				$focusObjectType = "Contacts";
			}
			if ($focusObjectType == 'cases') {
				$focusObjectType = "Cases";
			}
			if ($focusObjectType == 'opportunities') {
				$focusObjectType = "Opportunities";
			}						
			$fromAccountID = $this->getEmailAccountForSending($userId);
			$request = array();
			$request['sendSubject'] = $email_template_subject;
			$request['sendDescription'] = $email_template_body;
			$request['sendTo'] = $to_address;
			//Account information is from inbound_email table
			$request['fromAccount'] = $fromAccountID;
			$request['addressFrom1'] = $fromAccountID;
			//Set parent id to be focus id
			$request['parent_id'] = $focusObjectId;
			$request['parent_type'] = $focusObjectType;
			$request['saveToSugar'] = "1";
			$request['addressTo1'] = $to_address;
			
			//******************************************************
			$_REQUEST['sendSubject'] = $email_template_subject;
			//This is the body of the email
			$_REQUEST['sendDescription'] = $email_template_body;
			$_REQUEST['sendTo'] = $to_address;
			$_REQUEST['setEditor'] = "1";
			$_REQUEST['sendCharset'] = "ISO-8859-1";
			$_REQUEST['addressTo2'] = $to_address;
			$_REQUEST['emailUIAction'] = "sendEmail";
			$_REQUEST['addressFrom1'] = $fromAccountID;
			$_REQUEST['fromAccount'] = $fromAccountID;
			$_REQUEST['saveToSugar'] = "1";
			$_REQUEST['addressTo1']  = $to_address;
			$_REQUEST['parent_id'] = $focusObjectId;
			$_REQUEST['parent_type'] = $focusObjectType;
			
		
			//Now check to see if there are any attachments
			//From the email_templates id we check table notes on parent_id and if there is a notes then we
			//add the notes id to the request - we already have the email template id and it is $email_template_id
			$attachmentId = $this->getTemplateAttachments($email_template_id);
			if ($attachmentId != '') {
				$_REQUEST['templateAttachments'] = $attachmentId;
				$request['templateAttachments'] = $attachmentId;
			}
			$new_email->email2init();
			$new_email->email2Send($request);
				
	}
	unset($new_email);
}

//This function will return the id from the inbound_email table for the current user
function getEmailAccountForSending($currentUserId){	
	$query = "Select id from inbound_email where created_by = '$currentUserId' and is_personal = 1";
	$resultEmailAccount = $this->db->query($query, true);
    $rowEmailAccount= $this->db->fetchByAssoc($resultEmailAccount);
    $accountId = $rowEmailAccount['id'];
    return $accountId;	
}

//This function will check to see if there is an attachment on the template
function getTemplateAttachments($email_template_id){
	$query = "Select id from notes where parent_id = '$email_template_id' and deleted = 0";
	$resultAttachment = $this->db->query($query, true);
    $rowAttachment = $this->db->fetchByAssoc($resultAttachment);
    $attachmentId = $rowAttachment['id'];
    return $attachmentId;
}

//****************************************************************************
//This function is passed the object type and id and we get the email address
//for sending an email - old function. New function 
//****************************************************************************

function getEmailAddress($focusObjectId,$focusObjectType){
//Return the row of data with email address and opt out if leads or contacts
if ($focusObjectType == 'leads') {
		$focusObjectType = 'Leads';
	}
if ($focusObjectType == 'contacts') {
		$focusObjectType = 'Contacts';
	}
if ($focusObjectType == 'accounts') {
		$focusObjectType = 'Accounts';
	}		
//email_addr_bean_rel - table that holds the pointer to the new email address table
$queryAddrBeanRel = "Select email_address_id from email_addr_bean_rel where bean_id = '$focusObjectId' and primary_address = 1 and bean_module = '$focusObjectType' and deleted = 0";
$resultAddrBeanRel =& $this->db->query($queryAddrBeanRel, true);
$rowAddrBeanRel= $this->db->fetchByAssoc($resultAddrBeanRel);

$emailAddressId = $rowAddrBeanRel['email_address_id'];
//Now query the email_addresses table
$queryemail_addresses = "Select * from email_addresses where id = '$emailAddressId'";
$result_email =& $this->db->query($queryemail_addresses, true);
$row_email= $this->db->fetchByAssoc($result_email);
  //$toAddress = $row_email['email1'];
$GLOBALS['log']->debug($row_email);
  return $row_email;
}

//******************************************************************************
//Check for any tasks that are eligible for escalation 
//******************************************************************************

function performTaskEscalation(){

	$query = "select tasks.id as taskId, tasks.initial_date_due as due_date, tasks.initial_time_due as time_due, 
			tasks.assigned_user_id as assigned_user_id, 
			tasks.is_escalatable_task as is_escalatable, tasks.escalation_delay_minutes as escalation_delay_minutes,
			users.reports_to_id  as reports_to_id 
		 	from  tasks INNER JOIN users ON tasks.assigned_user_id = users.id 
		 	where  users.reports_to_id is not null 
		 	and tasks.is_pm_created_task = 1 and tasks.is_escalatable_task = 1 and tasks.escalation_delay_minutes != '0'
		 	and (UNIX_TIMESTAMP(TIMESTAMP(tasks.initial_date_due , tasks.initial_time_due)) + tasks.escalation_delay_minutes * 60) < UNIX_TIMESTAMP(TIMESTAMP(UTC_DATE(), UTC_TIME()))
		 	and tasks.status != 'Completed'";

	$result = $this->db->query($query,true);
   	while($row_task = $this->db->fetchByAssoc($result))
	{	

		$taskId = 	$row_task['taskId'];
		$reportsToId = $row_task['reports_to_id'];
		$task_update_query = " update tasks set assigned_user_id = '".$reportsToId."' where id = '".$taskId."'";
		$this->db->query($task_update_query,true);
	}
}

//*********************************************************************
//Check for Calls Eligible for Escalation
//*********************************************************************
function performCallEscalation(){

	$query = "select calls.id as callId, calls.initial_date_start as date_start, calls.initial_time_start as time_start, 
			calls.assigned_user_id as assigned_user_id, 
			calls.is_escalatable_call as is_escalatable, calls.escalation_delay_minutes as escalation_delay_minutes,
			users.reports_to_id  as reports_to_id 
		 	from  calls INNER JOIN users ON calls.assigned_user_id = users.id 
		 	where  users.reports_to_id is not null 
		 	and calls.is_pm_created_call = 1 and calls.is_escalatable_call = 1 and calls.escalation_delay_minutes != '0'
		 	and (UNIX_TIMESTAMP(TIMESTAMP(calls.initial_date_start , calls.initial_time_start)) + calls.escalation_delay_minutes * 60) < UNIX_TIMESTAMP(TIMESTAMP(UTC_DATE(), UTC_TIME()))
		 	and calls.status != 'Held'";

	$result = $this->db->query($query,true);
   	while($row_call = $this->db->fetchByAssoc($result))
	{	

		$taskId = 	$row_call['callId'];
		$reportsToId = $row_call['reports_to_id'];
		$task_update_query = " update calls set assigned_user_id = '".$reportsToId."' where id = '".$taskId."'";
		$this->db->query($task_update_query,true);
	}
}
//***********************************************************
//Get the id from the user table based on the user_name
//***********************************************************
function getUserIdByName($username){
	$query = "Select id from users where user_name = '$username'";
	$result = $this->db->query($query);
	$row = $this->db->fetchByAssoc($result);
	$userId = $row['id'];
	return $userId;
	
}

//******************************************************************
//This function checks to see if there is an active process for 
//the focus object.
//******************************************************************

function checkFocusProcess($focusObjectType){
	require_once ('config.php'); // provides $sugar_config
	global $sugar_config;
	$configOptions = $sugar_config['dbconfig'];
	$dbName = $configOptions['db_name'];
	$queryProcess = "Select value from config where name = 'pm_version'";
	$result = $this->db->query($queryProcess);
	$row = $this->db->fetchByAssoc($result);
	$name = $row['value'];
	if ($name == $dbName) {		
		return true;
	}
	else{	
		return false;
	}
	
	
}

//************************************************************
//This function will insert a record into the call_contacts
//table for a new call
//************************************************************

function loadCallsContacts($focusObjectId,$newCallid,$today){
$newCallsContactsId = create_guid();
$newCallsContactsQuery = "Insert into calls_contacts set id = '" .$newCallsContactsId ."'";
$newCallsContactsQuery .= ", call_id = '" .$newCallid ."'";
$newCallsContactsQuery .= ", contact_id = '" .$focusObjectId ."'";
$newCallsContactsQuery .= ", required = 1, accept_status = 'none'";
$newCallsContactsQuery .= ",date_modified ='" . $today ."'";
$newCallsContactsQuery .= ", deleted = 0";
$this->db->query($newCallsContactsQuery);
}
//***********************************************************************************
//This function saves an email object against the contact or lead
//*************************************************************************************
//***********************************************************************************
//This function saves an email object against the contact or lead
//*************************************************************************************
function loadEmail($lead_id,$contact_id,$opportunity_id,$account_id,$case_id,$description,$subject,$to_address){
	global $current_user;
	$new_email = new Email();
	$new_email_leads = new Email();
	$today = date("Y-m-d");
	$dateTimeNow = gmdate('Y-m-d H:i:s');
	$dateStart = gmdate('Y-m-d');
	$timeStart = gmdate('H:i:s');
	$hour = date("H");
	$minute = date("i");
	$second = date("s");
	$day = date("d");
	$month = date("m");
	$year = date("Y");
	$hour = $hour + 5;
	$today_date = date("H:i:s", mktime($hour,$minute,$second,$month,$day,$year));
	//Get the id of the new email object
	$new_email->id = create_guid();
	
	$queryInsertEmail = "INSERT into emails set id = '";
	$queryInsertEmail .= $new_email->id;
	$queryInsertEmail .= "' , parent_type = '"; 
	if ($lead_id != "") {
			$queryInsertEmail .= "Leads' , parent_id = '";
			$queryInsertEmail .= $lead_id;
			$queryInsertEmail .= "'";
		}
	elseif($contact_id != ""){
			$queryInsertEmail .= "Accounts' , parent_id = '";
			$queryInsertEmail .= $contact_id;
			$queryInsertEmail .= "'";
		}
		elseif($case_id != ""){
			$queryInsertEmail .= "Cases' , parent_id = '";
			$queryInsertEmail .= $case_id;
			$queryInsertEmail .= "'";
		}

	$queryInsertEmail .= ", date_entered = '" .$dateTimeNow ."'";
	$queryInsertEmail .= ", date_modified = '" .$dateTimeNow ."'";
	$queryInsertEmail .= ", date_sent = '" .$dateStart ."'";
	$queryInsertEmail .= ", assigned_user_id = '" .$current_user->id ."'";
	$queryInsertEmail .= ", modified_user_id = '" .$current_user->id ."'";
	$queryInsertEmail .= ", created_by = '" .$current_user->id ."'";
	$queryInsertEmail .= ", deleted = 0";
	$queryInsertEmail .= ", message_id = ''";
	$queryInsertEmail .= ", name = '" .$subject ."'";
	$queryInsertEmail .= ", type = 'out'";
	$queryInsertEmail .= ", status = 'sent'";
    $queryInsertEmail .= ", intent = ''";
    $queryInsertEmail .= ", mailbox_id = ''";
	$new_email->db->query($queryInsertEmail, true);
	
	//Now update the leads_emails table
	if ($lead_id != "") {
			$new_email_leads->id = create_guid();
			$queryInsertLeadsEmail = "INSERT into emails_leads set id = '";
			$queryInsertLeadsEmail .= $new_email_leads->id;
			$queryInsertLeadsEmail .= "'";
			$queryInsertLeadsEmail .= ", email_id = '" .$new_email->id ."'";
			$queryInsertLeadsEmail .= ", lead_id = '" .$lead_id ."'";
			$queryInsertLeadsEmail .= ", date_modified = '" .$dateTimeNow ."'";
			$queryInsertLeadsEmail .= ", deleted = 0";
			$new_email_leads->db->query($queryInsertLeadsEmail, true);
	}
	
	
	$new_email->load_relationship('contacts');
	//$new_email->set_emails_user_invitee_relationship($new_email->id,$current_user->id);
	if ($contact_id != "") {
			$new_email_leads->id = create_guid();
			$queryInsertLeadsEmail = "INSERT into emails_contacts set id = '";
			$queryInsertLeadsEmail .= $new_email_leads->id;
			$queryInsertLeadsEmail .= "'";
			$queryInsertLeadsEmail .= ", email_id = '" .$new_email->id ."'";
			$queryInsertLeadsEmail .= ", contact_id = '" .$contact_id ."'";
			$queryInsertLeadsEmail .= ", date_modified = '" .$dateTimeNow ."'";
			$queryInsertLeadsEmail .= ", deleted = 0";
			$new_email_leads->db->query($queryInsertLeadsEmail, true);
				}
	if ($case_id != "") {
		$new_email_leads->id = create_guid();
		$queryInsertLeadsEmail = "INSERT into emails_cases set id = '";
		$queryInsertLeadsEmail .= $new_email_leads->id;
		$queryInsertLeadsEmail .= "'";
		$queryInsertLeadsEmail .= ", email_id = '" .$new_email->id ."'";
		$queryInsertLeadsEmail .= ", case_id = '" .$case_id ."'";
		$queryInsertLeadsEmail .= ", date_modified = '" .$dateTimeNow ."'";
		$queryInsertLeadsEmail .= ", deleted = 0";
		$new_email_leads->db->query($queryInsertLeadsEmail, true);
			}			
	
}
//***************************************************************************************************
//This function will get the entry in the filter table for the given process
//***************************************************************************************************

function getProcessFilterTableEntry($process_id){
	$query = "Select * from pm_process_filter_table where process_id = '" .$process_id ."' ORDER BY sequence ASC";
	$result = $this->db->query($query);
	return $result;
}

//**********************************************************************************
//Generic Count Function
//*********************************************************************************
function getCount($sql){
	$counter = 0;
	$result = $this->db->query($sql);
	while($row = $this->db->fetchByAssoc($result))
		{
			$counter = $counter + 1;
		}
	return $counter;
}

//***************************************************************************
//Gets the info for Scheduling a Meeting
//***************************************************************************
function runScheduleMeetingTask($processID,$stageID,$rowTask,$focusObjectId,$focusObjectType){
		//Go and get the defs record from pm_process_task_call_defs
		global $current_user;
		global $previousTaskId;
		$taskId = $rowTask['id'];
		$taskOrder = $rowTask['task_order'];
		$queryTaskMeetingDefs = "Select * from pm_process_task_meeting_defs where task_id = '" .$taskId ."'";
		$resultTaskMeetingDefs = $this->db->query($queryTaskMeetingDefs);
		$rowTaskMeetingDefs = $this->db->fetchByAssoc($resultTaskMeetingDefs);
		if ($rowTaskMeetingDefs) {
			if($rowTaskMeetingDefs['start_delay_type'] == 'From Completion of Previous Task'){	
				$this->insertTaskIntoWaitingTable($processID,$stageID,$focusObjectId,$focusObjectType,$taskId,$taskOrder,'meetings');
				return;
			}			
			//Get the delay type
			$focusFieldsArray = array();
			$delay_type = $rowTaskCallDefs['start_delay_type'];
			if ($rowTaskCallDefs['start_delay_type'] == 'Create') {
				$focusFieldsArray['date_entered'] = 'date_entered';
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_entered'];
			}
			else{
				$focusFieldsArray['date_modified'] = 'date_modified';
				$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
				$focusObjectCreateDate = $arrayFieldsFromFocusObject['date_modified'];
			}			
				$newCallTimeStart = $this->getNewCallTimeStart($focusObjectCreateDate,$rowTaskMeetingDefs['start_delay_years'],$rowTaskMeetingDefs['start_delay_months'],$rowTaskMeetingDefs['start_delay_days'],$rowTaskMeetingDefs['start_delay_hours'],$rowTaskMeetingDefs['start_delay_minutes']);
				$this->createNewMeetingTask($focusObjectId,$focusObjectType,$rowTaskMeetingDefs,$newCallTimeStart,$focusObjectCreateDate);			
		}		
	}
	
//*************************************************************************

//***************************************************************************
//This function is called by runScheduleCallTask to insert the new call data
//We pass the focus object and focus type, the row of call defs and also
//call time start.
//Iff the call is for a lead then we set parent type and parent id
//If the call is for a contact then we relate with calls_contacts table
//Also we parse the call start time to get the date and time 
//***************************************************************************

function createNewMeetingTask($focusObjectId,$focusObjectType,$rowTaskMeetingDefs,$newMeetingTimeStart,$focusObjectCreateDate){
	global $previousTaskId;
	global $current_user;
	require_once('modules/Meetings/Meeting.php');
	$newMeeting = new Meeting();
	$timezone = date('Z') / 3600;
	$timezone = substr($timezone,1);
	$today = date('Y-m-d H:i:s', time() + $timezone * 60 * 60);

	$newMeetingUserid = create_guid();
	$spaceLocation = strpos($newMeetingTimeStart," ");
	$meetingStartDate = substr($newMeetingTimeStart,0,$spaceLocation);
	$meetingStartTime = substr($newMeetingTimeStart,$spaceLocation);
	if ($focusObjectType == 'leads') {
		$focusObjectType = 'Leads';
	}
	if ($focusObjectType == 'opportunities') {
		$focusObjectType = 'Opportunities';
	}
	if ($focusObjectType == 'cases') {
		$focusObjectType = 'Cases';
	}
	if ($focusObjectType == 'quotes') {
		$focusObjectType = 'Quotes';
	}	
		//Is the assigned_user_call_id set in the def record?
	if($rowTaskMeetingDefs['assigned_user_id_meeting'] != ""){
		$assignedUserMeetingId = $rowTaskMeetingDefs['assigned_user_id_meeting'];
		$assignedUserMeetingId = $this->getUserIdByName($assignedUserMeetingId);
	}
	else{		
		$assignedUserMeetingId	= $current_user->id;
	}
	//Build the Meeting object and Save
	$newMeeting->name = $rowTaskMeetingDefs['meeting_subject'];
	//Description
	$newMeeting->description = $rowTaskMeetingDefs['meeting_description'];
	//Location
	$newMeeting->location = $rowTaskMeetingDefs['meeting_location'];
	//Duration
	$newMeeting->duration_hours = 0;
	$newMeeting->duration_minutes = 15;
	$newMeeting->date_start = $newCallTimeStart;
	$newMeeting->date_end = $meetingStartDate;
	$newMeeting->parent_type = $focusObjectType;
	$newMeeting->status = "Planned";
	$newMeeting->parent_id = $focusObjectId;
	$newMeeting->assigned_user_id = $assignedUserMeetingId;	
	$newMeeting->save(true);
	$newMeetingId = $newMeeting->id;
	$previousTaskId = $newMeeting->id;

	//Due to unforseen circumstances we have to update the new record for meetings for the date start/time
	$queryUpdateMeetings = "update meetings set date_start = '$newMeetingTimeStart', date_end = '$meetingStartDate' where id = '$newMeetingId'";
	$this->db->query($queryUpdateMeetings);
	
	//Now insert into meetings_user
	$newMeetingUserQuery = "Insert into meetings_users set id = '" .$newMeetingUserid ."'";
	$newMeetingUserQuery .= ", meeting_id = '" .$newMeetingId ."', user_id = '$assignedUserMeetingId'";
	$newMeetingUserQuery .= ", required = 1";
	$newMeetingUserQuery .= ", accept_status = 'accept'";
	$newMeetingUserQuery .= ", date_modified = '" .$today ."'";
	$newCallUserQuery .= ", deleted = 0";
	$this->db->query($newMeetingUserQuery);	
	return $newMeeting->id;
}

//***********************************************************************************************
//This function is used to determine if we pass the filter test
//***********************************************************************************************

function getFilterTestResult($passFilterTest,$field,$fieldOperator,$value,$focusObjectId,$focusObjectType,$focusFieldsArray){	
	$subStringCount = 0;
	$subStringCount = substr_count ($field,"_c");
	//Determine if this is a custom field - first get the length
	$lengthOfField = strlen($field);
	$start = 	$lengthOfField - 2;
	$checkCustomField = substr($field,$start);
	$table = $focusObjectType;
	if ($checkCustomField == '_c') {
		//So now we have a custom field								    
			$arrayFieldsFromFocusObject = $this->getFocusObjectCustomFields($focusObjectId,$focusObjectType,$focusFieldsArray);
			$fieldValueFromFocusObect = $arrayFieldsFromFocusObject[$field];
			$table .= '_cstm';
			
	}
	else{
			$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
			$fieldValueFromFocusObect = $arrayFieldsFromFocusObject[$field];
	}	
	
    //Now compare the values based on the field operator - mods on 3/24/2009
	//Now determine if the $fieldValueFromFocusObect is a date field
	//Patch 12/4/2009
	//Get the Data Type
	$dataType = $this->getDataType($table,$field);
	if ($dataType == 'datetime') {
		//Return just the y-m-d
		$fieldValueFromFocusObect = strtotime($fieldValueFromFocusObect);
		$value = strtotime($value);
	}
    if ($fieldOperator == '=') {
		if($value != $fieldValueFromFocusObect){
			$passFilterTest = false;		
		}
    }
    if ($fieldOperator == '!=') {
    			if($value == $fieldValueFromFocusObect){	
				$passFilterTest = false;		
			}
    }
    //Patch 12/4/2009
    if ($fieldOperator == '&lt;') {
    			if($value < $fieldValueFromFocusObect){	
				$passFilterTest = false;		
			}
    }
    //Patch 12/4/2009
    if ($fieldOperator == '&gt;') {
    			if($value > $fieldValueFromFocusObect){	
				$passFilterTest = false;		
			}
    }
    //Patch 03/26/2010
    if ($fieldOperator == 'contains') {
    		if (strstr($fieldValueFromFocusObect, $value) === false) {
    			$passFilterTest = false;
    		}
    }
	//Patch 03/26/2010
    if ($fieldOperator == 'does not contain') {
    		if (strstr($fieldValueFromFocusObect, $value) !== false) {
    			$passFilterTest = false;
    		}
    }
	return $passFilterTest;
}

//Return the data type
function getDataType($table,$field){
	$query = "show fields from  $table";
	$result = $this->db->query($query,true);
	while($row = $this->db->fetchByAssoc($result))
		{
			$fieldName = $row['Field'];
			if ($fieldName == $field) {
				$dataType = $row['Type'];
			}
		}
	return $dataType;
}

//******************************************************************************
//This function will retrieve the email address and id from the contact table
//for the given opp. 
//*****************************************************************************

function getAccountOppEmails($focusObjectId,$focusObjectType,$contact_role){
	$GLOBALS['log']->info("ProcessManager - Getting Account Opp Emails for focus object id  " .$focusObjectId);
	$query_opps_accounts = "Select account_id from accounts_opportunities where opportunity_id  = ";
	$query_opps_accounts .= "'";
	$query_opps_accounts .= $focusObjectId;
	$query_opps_accounts .= "' and deleted = 0";

	$result_opps_accounts =& $this->db->query($query_opps_accounts, true);
	$row = $this->db->fetchByAssoc($result_opps_accounts);
	if ($row) {
		$queryAccount = "Select id from accounts where id = '" .$row['account_id'] ."'";
		$resultAccount = $this->db->query($queryAccount, true);
		$rowAccount = $this->db->fetchByAssoc($resultAccount);
		if ($rowAccount) {
			return $rowAccount;
		}
	}
	
}


}

?>
