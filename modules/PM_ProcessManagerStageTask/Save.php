<?php
require_once('include/formbase.php');
require_once('modules/PM_ProcessManagerStageTask/PM_ProcessManagerStageTask.php');
require_once('data/SugarBean.php');
global $current_user;
$user_id = $current_user->id;
$thisSugarBean = new SugarBean();
$focus = new PM_ProcessManagerStageTask();
$focus->retrieve($_POST['record']);
$return_val = array();
$name =($_POST['name']);
$taskType =($_POST['task_type']);
$description =($_POST['description']);
$startDelayType = ($_POST['start_delay_type']);
$customScript = ($_POST['custom_script']);
$taskOrder = ($_POST['task_order']);
$focus->name = $name;
$focus->task_type = $taskType;
$focus->description = $description;
$focus->start_delay_type = $start_delay_type;
$focus->custom_script = $customScript;
$focus->task_order = $taskOrder;
$focus->save();
 
//Check the Task Order
if ($taskOrder == '') {
	$taskOrder = 0;
}

//Now see if there any filter table entries and if so then add
$return_id = $focus->id;
$taskType =($_POST['task_type']);
$description =($_POST['description']);
$startDelayType = ($_POST['start_delay_type']);
$queryUpdate = "Update pm_processmanagerstagetask set task_order =  $taskOrder, start_delay_type = '$startDelayType', description = '$description' , name = '$name', task_type = '$taskType', assigned_user_id = '$user_id' where id = '$return_id'";

$thisSugarBean->db->query($queryUpdate, true);
//Now check to see what associated defs file needs to be updated
//The field is task type which will be Send Email, Schedule Call, Schedule Meeting, Create Task, Create Note

if ($taskType == "Send Email") {
    $result = array();
	//First see if there already is an associated email template
	$query = "Select * from pm_process_task_email_defs where task_id = '" .$focus->id ."'";
	$result = $focus->db->query($query, true);
	$row_email_defs = $focus->db->fetchByAssoc($result);
	$id = $row_email_defs["id"];
    $email_template = $_POST['email_templates'];
	$contact_role = $_POST['contact_roles'];
	$internalEmail = $_POST['internal_email'];
	$internalEmailToAddress = $_POST['internal_email_to_address']; 
	//Update 04/17/2010 - Add email to caseopp account
	$send_email_to_caseopp_account = $_POST['send_email_to_caseopp_account'];
	//Custom SierraCRM - Send Email to Object Owner
	$send_email_to_object_owner = $_POST['send_email_to_object_owner'];
	if ($send_email_to_object_owner == '') {
		$send_email_to_object_owner = 0;
	}	
	if ($send_email_to_caseopp_account == '') {
		$send_email_to_caseopp_account = 0;
	}	
	if($id != ''){
			updateTaskEmailTemplates($focus,$id,$email_template,$contact_role,$internalEmail,$internalEmailToAddress,$send_email_to_caseopp_account,$send_email_to_object_owner);
		}
	else{			
			insertTaskEmailTemplateDefs($focus,$focus->id,$email_template,$contact_role,$internalEmail,$internalEmailToAddress,$send_email_to_caseopp_account,$send_email_to_object_owner);
		}
}

if($taskType == "Create Project Task"){
	$focusId = $focus->id;
	$query = "Select * from pm_process_project_task_defs where project_id = '" .$focusId ."'";
	$result = $thisSugarBean->db->query($query, true);
	$row_task_task_defs = $focus->db->fetchByAssoc($result);
	$id = $row_task_task_defs["id"];
	if($id != ''){
			updateProjectTaskDefs($focus,$id);
	}
	else{			
			insertProjectTaskDefs($focus,$focus->id);
	}
}

//This is for the create task
if ($taskType == "Create Task") {
	//First see if there already is an associated email template
	$focusId = $focus->id;
	$query = "Select * from pm_process_task_task_defs where task_id = '" .$focusId ."'";
	$result = $thisSugarBean->db->query($query, true);
	$row_task_task_defs = $focus->db->fetchByAssoc($result);
	$id = $row_task_task_defs["id"];
	if($id != ''){
			updateTaskTaskDefs($focus,$id);
	}
	else{			
			insertTaskTaskDefs($focus,$focus->id);
	}
}

//This is for the Schedule Call
if ($taskType == "Schedule Call") {
    $result = array();
	//First see if there already is an associated call defs
	$query = "Select * from pm_process_task_call_defs where task_id = '" .$focus->id ."'";
	$result = $focus->db->query($query, true);
	$rowCallDefs = $focus->db->fetchByAssoc($result);
	$id = $rowCallDefs["id"];
	if($id != ''){	
			updateTaskCallDefs($focus,$id);
		}
	else{			
			insertTaskCallDefs($focus,$focus->id);
		}
}

//NEW FUNCTIONS TO SUPPORT MEETINGS
//This is for the Schedule Meetings Section
if ($taskType == "Schedule Meeting") {
    $result = array();
	//First see if there already is an associated call defs
	$query = "Select * from pm_process_task_meeting_defs where task_id = '" .$focus->id ."'";
	$result = $focus->db->query($query, true);
	$rowCallDefs = $focus->db->fetchByAssoc($result);
	$id = $rowCallDefs["id"];
	if($id != ''){	
			updateTaskMeetingDefs($focus,$id);
		}
	else{			
			insertTaskMeetingDefs($focus,$focus->id);
		}
}
//END NEW FUNCTIONS FOR MEETINGS

if ($taskType == "Create New Record") {
    $result = array();
	//First see if there already is an associated call defs
	$query = "Select * from pm_process_task_create_object_defs where task_id = '" .$focus->id ."'";
	$result = $focus->db->query($query, true);
	$rowCallDefs = $focus->db->fetchByAssoc($result);
	$id = $rowCallDefs["id"];
	if($id != ''){	
			updateCreateObjectDefs($focus,$id);
		}
	else{			
			insertCreateObjectDefs($focus,$focus->id);
		}
}

//Now redirect to Edit View Page
handleRedirect($return_id, "PM_ProcessManagerStageTask");
//Check to see if the entry in the process filter table already exists

//***************************************************************************
//Insert the Email Task Def record
//***************************************************************************
function insertTaskEmailTemplateDefs($focus,$id,$email_template_name,$contact_role,$internalEmail,$internalEmailToAddress,$send_email_to_caseopp_account,$send_email_to_object_owner){
	//Get the id of the template
	if ($internalEmail == '') {
		$internalEmail = 0;
	}
	$query = "Select id from email_templates where name = '" .$email_template_name ."'";
	$result_email_template_name = $focus->db->query($query,true);
	$row_email_template_name = $focus->db->fetchByAssoc($result_email_template_name);
	if($row_email_template_name){
		$email_template_id = $row_email_template_name['id'];
		$email_template_defs_id = create_guid();
		$query = "Insert into pm_process_task_email_defs set id = '" .$email_template_defs_id ."', email_template_name = '" .$email_template_name ."', ";
		$query .= " email_template_id ='" .$email_template_id ."', task_id = '" .$id ."', internal_email = '" .$internalEmail ."', internal_email_to_address = '" .$internalEmailToAddress ."'";
		$query .= ", contact_role = '" .$contact_role ."',  send_email_to_caseopp_account = $send_email_to_caseopp_account, send_email_to_object_owner = $send_email_to_object_owner ";
		$focus->db->query($query,true);
		
		//Now update the related id in the task table
		$query = "Update pm_processmanagerstagetask set email_template_defs_id = '" .$email_template_defs_id ."' where id = '" .$id ."'";
		$focus->db->query($query,true);
	}
	
}

function updateTaskEmailTemplates($focus,$email_template_defs_id,$email_template_name,$contact_role,$internalEmail,$internalEmailToAddress,$send_email_to_caseopp_account,$send_email_to_object_owner){
	if ($internalEmail == '') {
		$internalEmail = 0;
	}
	//Go and get the id for the email template first
	$query = "Select id from email_templates where name = '" .$email_template_name ."'";
	$result =& $focus->db->query($query, true);
	$row_email_template = $focus->db->fetchByAssoc($result);
	$emailTemplateId = $row_email_template['id'];
	
	$queryUpdate = "Update pm_process_task_email_defs set email_template_name = '" .$email_template_name ."', email_template_id = '" .$emailTemplateId ."'";
	$queryUpdate .= " , contact_role = '" .$contact_role ."', internal_email = '" .$internalEmail ."', internal_email_to_address = '" .$internalEmailToAddress ."', send_email_to_caseopp_account = $send_email_to_caseopp_account , send_email_to_object_owner = $send_email_to_object_owner  ";
	$queryUpdate .= " where id = '" .$email_template_defs_id ."'";
	$result =& $focus->db->query($queryUpdate, true);
}
//********************************************************************************
//Insert or Update the Task Task Defs file
//********************************************************************************
function updateTaskTaskDefs($focus,$id){
	//Get the fields from the post/request
	$taskSubject = $_POST['task_subject'];
	$taskPriority = $_POST['task_priority'];
	$taskDescription = $_POST['task_description'];
	$taskDueDateDelayMinutes = $_POST['task_due_date_delay_minutes'];
	$taskDueDateDelayHours = $_POST['task_due_date_delay_hours'];
	$taskDueDateDelayDays = $_POST['task_due_date_delay_days'];
	$taskDueDateDelayMonths = $_POST['task_due_date_delay_months'];
	$taskDueDateDelayYears = $_POST['task_due_date_delay_years'];
	$taskAssignedUserIdTask = $_POST['assigned_user_id_task'];
	$taskIsEscalatable = $_POST['is_escalatable_task_edit'];
	$taskEscalationDelayMinutes = $_POST['escalation_delay_minutes_task'];
	if ($taskAssignedUserIdTask == 'Please Specify') {
		$taskAssignedUserIdTask = '';
	}
	if ($taskIsEscalatable == 'on') {
		$taskIsEscalatable = 1;
	}
	else{
		$taskIsEscalatable = 0;
	}
	if ($assignedUserIdTask == 'Please Specify') {
		$assignedUserIdTask = '';
	}		
	if ($taskDueDateDelayMinutes == '') {
		$taskDueDateDelayMinutes = 0;
	}
	if ($taskDueDateDelayHours == '') {
		$taskDueDateDelayHours = 0;
	}
	if ($taskDueDateDelayDays == '') {
		$taskDueDateDelayDays = 0;
	}
	if ($taskDueDateDelayMonths == '') {
		$taskDueDateDelayMonths = 0;
	}
	if ($taskDueDateDelayYears == '') {
		$taskDueDateDelayYears = 0;
	}		
	$startDelayType = $_POST['start_delay_type'];
	//Get the id of the template
	$query = "Update pm_process_task_task_defs set task_subject = '" .$taskSubject ."'";
	$query .= ", task_priority = '" .$taskPriority ."' , due_date_delay_minutes = " .$taskDueDateDelayMinutes;
	$query .= ", due_date_delay_hours = " .$taskDueDateDelayHours .", due_date_delay_days = " .$taskDueDateDelayDays;
	$query .= ", due_date_delay_months = " .$taskDueDateDelayMonths .", due_date_delay_years = " .$taskDueDateDelayYears;
	$query .= ", due_date_delay_type = '" .$startDelayType ."'";
	$query .= ", assigned_user_id_task = '" .$taskAssignedUserIdTask ."'";
	$query .= ", is_escalatable_task = '" .$taskIsEscalatable ."'";
	$query .= ", escalation_delay_minutes_task = '" .$taskEscalationDelayMinutes ."'";
	$query .= ", task_description = '" .$taskDescription ."'";
	$query .= " where id = '" .$id ."'";
	$focus->db->query($query,true);
	
}

function insertTaskTaskDefs($focus,$focusID){
	$process_task_task_defs_id = create_guid();
//Get the fields from the post/request
	$taskSubject = $_POST['task_subject'];
	$taskPriority = $_POST['task_priority'];
	$taskDescription = $_POST['task_description'];
	$taskDueDateDelayMinutes = $_POST['task_due_date_delay_minutes'];
	$taskDueDateDelayHours = $_POST['task_due_date_delay_hours'];
	$taskDueDateDelayDays = $_POST['task_due_date_delay_days'];
	$taskDueDateDelayMonths = $_POST['task_due_date_delay_months'];
	$taskDueDateDelayYears = $_POST['task_due_date_delay_years'];
	$startDelayType = $_POST['start_delay_type'];
	$assignedUserIdTask = $_POST['assigned_user_id_task'];
	if ($assignedUserIdTask == 'Please Specify') {
		$assignedUserIdTask = '';
	}
	$taskIsEscalatable = $_POST['is_escalatable_task_edit'];
	$taskEscalationDelayMinutes = $_POST['escalation_delay_minutes_task'];
	if ($taskIsEscalatable == 'on') {
		$taskIsEscalatable = 1;
	}
	else{
		$taskIsEscalatable = 0;
	}
	if($taskEscalationDelayMinutes == ''){
		$taskEscalationDelayMinutes = 0;	
	}
	//Get the id of the template
	$query = "INSERT into pm_process_task_task_defs set task_subject = '" .$taskSubject ."'";
	$query .= ", task_priority = '" .$taskPriority ."'";
	if ($taskDueDateDelayMinutes != '') {
		$query .= ", due_date_delay_minutes = " .$taskDueDateDelayMinutes;
	}
	if ($taskDueDateDelayHours != '') {
		$query .= ", due_date_delay_hours = " .$taskDueDateDelayHours;
	}
	if ($taskDueDateDelayDays != '') {
		$query .= ", due_date_delay_days = " .$taskDueDateDelayDays;
	}
	if ($taskDueDateDelayMonths != '') {
		$query .= ", due_date_delay_months = " .$taskDueDateDelayMonths;
	}
	if ($taskDueDateDelayYears != '') {
		$query .= ", due_date_delay_years = " .$taskDueDateDelayYears;
	}		
	$query .= ", due_date_delay_type = '" .$startDelayType ."'";
	$query .= ", assigned_user_id_task = '" .$assignedUserIdTask ."'";
	$query .= ", is_escalatable_task = '" .$taskIsEscalatable ."'";
	$query .= ", escalation_delay_minutes_task = '" .$taskEscalationDelayMinutes ."'";
	$query .= ", task_description = '" .$taskDescription ."'";
	$query .= ", id =  '" .$process_task_task_defs_id ."' , task_id = '" .$focusID ."'";
	$focus->db->query($query,true);
	
	//Now update the related id in the task table
		$query = "Update pm_processmanagerstagetask set task_defs_id = '" .$process_task_task_defs_id ."' where id = '" .$focusID ."'";
		$focus->db->query($query,true);
}
//********************************************************************************
//Insert or Update the Project Task Defs file
//********************************************************************************
function updateProjectTaskDefs($focus,$id){
	//Get the fields from the post/request
	$projectTaskSubject = $_POST['project_task_subject'];
	$projectTaskId = $_POST['project_task_id'];
	$projectTaskStatus = $_POST['project_task_status'];
	$projectTaskPriority = $_POST['project_task_priority'];
	$projectTaskStartDate = $_POST['project_task_start_date'];
	$projectTaskEndDate = $_POST['project_task_end_date'];
	$projectTaskAssignedUser = $_POST['assigned_user_id_project_task'];
	$projectTaskMilestone = $_POST['project_task_milestone'];
	$projectTaskTaskNumber = $_POST['project_task_task_number'];
	$projectTaskOrder = $_POST['project_task_order'];
	$projectTaskDescription = $_POST['project_task_description'];
	
	//Get the id of the template
	$query = "Update pm_process_project_task_defs project_task_subject = '" .$projectTaskSubject ."'";
	$query .= ", project_task_id = '" .$projectTaskId ."' , project_task_status = '" .$projectTaskStatus . "'";
	$query .= ", project_task_priority = '" .$projectTaskPriority ."' , project_task_start_date = '" .$projectTaskStartDate . "'";
	$query .= ", project_task_end_date = '" .$projectTaskEndDate ."', assigned_user_id_project_task = '" .$projectTaskAssignedUser . "'";
	$query .= ", project_task_milestone = '" .$projectTaskMilestone ."'";
	$query .= ", project_task_task_number = '" .$projectTaskTaskNumber ."'";
	$query .= ", project_task_order = '" .$projectTaskOrder ."'";
	$query .= ", project_task_description = '" .$projectTaskDescription ."'";
	$query .= " where id = '" .$id ."'";
	$focus->db->query($query,true);
	
}

function insertProjectTaskDefs($focus,$focusID){
	$process_project_task_defs_id = create_guid();
//Get the fields from the post/request
$projectTaskSubject = $_POST['project_task_subject'];
	$projectTaskId = $_POST['project_task_id'];
	$projectTaskStatus = $_POST['project_task_status'];
	$projectTaskPriority = $_POST['project_task_priority'];
	$projectTaskStartDate = $_POST['project_task_start_date'];
	$projectTaskEndDate = $_POST['project_task_end_date'];
	$projectTaskAssignedUser = $_POST['assigned_user_id_project_task'];
	$projectTaskMilestone = $_POST['project_task_milestone'];
	$projectTaskTaskNumber = $_POST['project_task_task_number'];
	$projectTaskOrder = $_POST['project_task_order'];
	$projectTaskDescription = $_POST['project_task_description'];
	//Get the id of the template
	$query = "INSERT into pm_process_project_task_defs set project_task_subject = '" .$projectTaskSubject ."'";
	$query .= ", project_task_id = '" .$projectTaskId ."' , project_task_status = '" .$projectTaskStatus . "'";
	$query .= ", project_task_priority = '" .$projectTaskPriority ."' , project_task_start_date = '" .$projectTaskStartDate . "'";
	$query .= ", project_task_end_date = '" .$projectTaskEndDate ."', assigned_user_id_project_task = '" .$projectTaskAssignedUser . "'";
	$query .= ", project_task_milestone = '" .$projectTaskMilestone ."'";
	$query .= ", project_task_task_number = '" .$projectTaskTaskNumber ."'";
	$query .= ", project_task_order = '" .$projectTaskOrder ."'";
	$query .= ", project_task_description = '" .$projectTaskDescription ."'";
	$query .= ", id =  '" .$process_project_task_defs_id ."' , project_id = '" .$focusID ."'";
	$focus->db->query($query,true);
	
	//Now update the related id in the task table
		$query = "Update pm_processmanagerstagetask set project_task_defs_id = '" .$process_project_task_defs_id ."' where id = '" .$focusID ."'";
		$focus->db->query($query,true);
}
//********************************************************************************
//Insert or Update the Call Task Defs file
//********************************************************************************
function updateTaskCallDefs($focus,$id){
	//Get the fields from the post/request
	$callSubject = $_POST['call_subject'];
	$callStartDateMinutesDelay = $_POST['call_due_date_delay_minutes'];
	if ($callStartDateMinutesDelay == "") {
		$callStartDateMinutesDelay = 0;
	}
	$callStartDateHoursDelay = $_POST['call_due_date_delay_hours'];
	if ($callStartDateHoursDelay == "") {
		$callStartDateHoursDelay = 0;
	}
	$callStartDateDaysDelay = $_POST['call_due_date_delay_days'];
	if ($callStartDateDaysDelay == "") {
		$callStartDateDaysDelay = 0;
	}
	//Months
	$callStartDateMonthsDelay = $_POST['call_due_date_delay_months'];
	if ($callStartDateMonthsDelay == "") {
		$callStartDateMonthsDelay = 0;
	}
	//Years
	$callStartDateYearsDelay = $_POST['call_due_date_delay_years'];
	if ($callStartDateYearsDelay == "") {
		$callStartDateYearsDelay = 0;
	}

	$callRemTime = $_POST['reminder_time'];
	if ($callRemTime == 0) {
			$_POST['reminder_time'] = -1;
			$callReminderTime = $_POST['reminder_time'];
	}
	else{
		$callReminderTime = $_POST['reminder_time'];
	}
	

	if(!isset($_POST['reminder_time'])){
		$_POST['reminder_time'] = $current_user->getPreference('reminder_time');
		if(empty($_POST['reminder_time'])){
			$_POST['reminder_time'] = -1;
			$callReminderTime = $_POST['reminder_time'];
		}
			
	}

	$callDescription = $_POST['call_description'];
	$startDelayType = $_POST['start_delay_type'];
	$assignedUserIdCall = $_POST['assigned_user_id_call'];
	if ($assignedUserIdCall == 'Please Specify') {
		$assignedUserIdCall = '';
	}
	
	//Escalation
	$callIsEscalatable = $_POST['is_escalatable_call_edit'];
	$callEscalationDelayMinutes = $_POST['escalation_delay_minutes_call'];
	if ($callEscalationDelayMinutes == '') {
		$callEscalationDelayMinutes = 0;
	}
	if ($callIsEscalatable == 'on') {
		$callIsEscalatable = 1;
	}
	else{
		$callIsEscalatable = 0;
	}
	
	//Get the id of the template
	$query = "Update pm_process_task_call_defs set call_subject = '" .$callSubject ."'";
	$query .= ", reminder_time = " .$callReminderTime ." , start_delay_minutes = " .$callStartDateMinutesDelay;
	$query .= ", start_delay_hours = " .$callStartDateHoursDelay .", start_delay_days = " .$callStartDateDaysDelay;
	$query .= ", start_delay_months = " .$callStartDateMonthsDelay .", start_delay_years = " .$callStartDateYearsDelay;
	$query .= ", call_description = '" .$callDescription ."'";
	$query .= ", start_delay_type = '" .$startDelayType ."'";
	$query .= ", assigned_user_id_call = '" .$assignedUserIdCall ."'";	
	$query .= ", is_escalatable_call = '" .$callIsEscalatable ."'";
	$query .= ", escalation_delay_minutes_call = '" .$callEscalationDelayMinutes ."'";	
	$query .= " where id = '" .$id ."'";
	$focus->db->query($query,true);
	
}

function insertTaskCallDefs($focus,$focusID){
	$process_task_call_defs_id = create_guid();
//Get the fields from the post/request
	$callSubject = $_POST['call_subject'];
	$callDescription = $_POST['call_description'];
	$callStartDateMinutesDelay = $_POST['call_due_date_delay_minutes'];
	if ($callStartDateMinutesDelay == "") {
		$callStartDateMinutesDelay = 0;
	}
	$callStartDateHoursDelay = $_POST['call_due_date_delay_hours'];
	if ($callStartDateHoursDelay == "") {
		$callStartDateHoursDelay = 0;
	}
	$callStartDateDaysDelay = $_POST['call_due_date_delay_days'];
	if ($callStartDateDaysDelay == "") {
		$callStartDateDaysDelay = 0;
	}
	//Months
	$callStartDateMonthsDelay = $_POST['call_due_date_delay_months'];
	if ($callStartDateMonthsDelay == "") {
		$callStartDateMonthsDelay = 0;
	}
	//Years	
	$callStartDateYearsDelay = $_POST['call_due_date_delay_years'];
	if ($callStartDateYearsDelay == "") {
		$callStartDateYearsDelay = 0;
	}	

	$callRemTime = $_POST['reminder_time'];
	if ($callRemTime == 0) {
			$_POST['reminder_time'] = -1;
			$callReminderTime = $_POST['reminder_time'];
	}
	else{
		$callReminderTime = $_POST['reminder_time'];
	}
	if(!isset($_POST['reminder_time'])){
		$_POST['reminder_time'] = $current_user->getPreference('reminder_time');
		if(empty($_POST['reminder_time'])){
			$_POST['reminder_time'] = -1;
			$callReminderTime = $_POST['reminder_time'];
		}
			
	}
	$startDelayType = $_POST['start_delay_type'];
	$assignedUserIdCall = $_POST["assigned_user_id_call"];
	if ($assignedUserIdCall == "Please Specify") {
		$assignedUserIdCall = '';
	}

	//Escalation
	$callIsEscalatable = $_POST['is_escalatable_call_edit'];
	$callEscalationDelayMinutes = $_POST['escalation_delay_minutes_call'];
	if ($callEscalationDelayMinutes == '') {
		$callEscalationDelayMinutes = 0;
	}	
	if ($callIsEscalatable == 'on') {
		$callIsEscalatable = 1;
	}
	else{
		$callIsEscalatable = 0;
	}	
	
	//Get the id of the template
	$query = "Insert into pm_process_task_call_defs set call_subject = '" .$callSubject ."'";
	$query .= ", reminder_time = " .$callReminderTime ." , start_delay_minutes = " .$callStartDateMinutesDelay;
	$query .= ", start_delay_hours = " .$callStartDateHoursDelay .", start_delay_days = " .$callStartDateDaysDelay;
	$query .= ", start_delay_months = " .$callStartDateMonthsDelay .", start_delay_years = " .$callStartDateYearsDelay;
	$query .= ", call_description = '" .$callDescription ."' , task_id = '" .$focusID ."'";
	$query .= ", start_delay_type = '" .$startDelayType ."'";
	$query .= ", id = '" .$process_task_call_defs_id ."'";
	$query .= ", assigned_user_id_call = '" .$assignedUserIdCall ."'";
	$query .= ", is_escalatable_call = '" .$callIsEscalatable ."'";
	$query .= ", escalation_delay_minutes_call = '" .$callEscalationDelayMinutes ."'";		
	$focus->db->query($query,true);
	
	//Now update the related id in the task table
		$query = "Update pm_processmanagerstagetask set calls_defs_id = '" .$process_task_call_defs_id ."' where id = '" .$focusID ."'";
		$focus->db->query($query,true);
}

//********************************************************************************
//Insert or Update the Meetings Task Defs file
//********************************************************************************
function updateTaskMeetingDefs($focus,$id){
	//Get the fields from the post/request
	$meetingSubject = $_POST['meeting_subject'];
	$meetingStartDelayMinutesDelay = $_POST['meeting_start_delay_minutes'];
	if ($meetingStartDelayMinutesDelay == "") {
		$meetingStartDelayMinutesDelay = 0;
	}
	$meetingStartDelayHoursDelay = $_POST['meeting_start_delay_hours'];
	if ($meetingStartDelayHoursDelay == "") {
		$meetingStartDelayHoursDelay = 0;
	}
	$meetingStartDelayDaysDelay = $_POST['meeting_start_delay_days'];
	if ($meetingStartDelayDaysDelay == "") {
		$meetingStartDelayDaysDelay = 0;
	}
	//Months
	$meetingStartDelayMonthsDelay = $_POST['meeting_start_delay_months'];
	if ($meetingStartDelayMonthsDelay == "") {
		$meetingStartDelayMonthsDelay = 0;
	}
	//Years
	$meetingStartDelayYearsDelay = $_POST['meeting_start_delay_years'];
	if ($meetingStartDelayYearsDelay == "") {
		$meetingStartDelayYearsDelay = 0;
	}

	
	
	if(isset($_POST['should_remind']) && $_POST['should_remind'] == '0'){
			$_POST['reminder_time_meeting'] = -1;
			$meetingReminderTime = $_POST['reminder_time_meeting'];
	}
	else{
		$meetingReminderTime = $_POST['reminder_time_meeting'];
	}
	if(!isset($_POST['reminder_time_meeting'])){
		$_POST['reminder_time_meeting'] = $current_user->getPreference('reminder_time_meeting');
		if(empty($_POST['reminder_time_meeting'])){
			$_POST['reminder_time_meeting'] = -1;
			$meetingReminderTime = $_POST['reminder_time_meeting'];
		}
			
	}

	$meetingDescription = $_POST['meeting_description'];
	$meetingLocation = $_POST['meeting_location'];
	$startDelayType = $_POST['start_delay_type'];
	$assignedUserIdMeeting = $_POST['assigned_user_id_meeting'];
	if ($assignedUserIdMeeting == 'Please Specify') {
		$assignedUserIdMeeting = '';
	}
	
	
	//Get the id of the template
	$query = "Update pm_process_task_meeting_defs set meeting_subject = '" .$meetingSubject ."'";
	$query .= ", reminder_time = " .$meetingReminderTime ." , start_delay_minutes = " .$meetingStartDelayMinutesDelay;
	$query .= ", start_delay_hours = " .$meetingStartDelayHoursDelay .", start_delay_days = " .$meetingStartDelayDaysDelay;
	$query .= ", start_delay_months = " .$meetingStartDelayMonthsDelay .", start_delay_years = " .$meetingStartDelayYearsDelay;
	$query .= ", meeting_description = '" .$meetingDescription ."'";
	$query .= ", meeting_location = '" .$meetingLocation ."'";
	$query .= ", start_delay_type = '" .$startDelayType ."'";
	$query .= ", assigned_user_id_meeting = '" .$assignedUserIdMeeting ."'";	
	$query .= " where id = '" .$id ."'";
	$focus->db->query($query,true);
	
}

//New Function to Support Meetings

function insertTaskMeetingDefs($focus,$focusID){
	$process_task_meeting_defs_id = create_guid();
	global $current_user;
//Get the fields from the post/request
	$meetingSubject = $_POST['meeting_subject'];
	$meetingDescription = $_POST['meeting_description'];
	$meetingStartDelayMinutesDelay = $_POST['meeting_start_delay_minutes'];
	if ($meetingStartDelayMinutesDelay == "") {
		$meetingStartDelayMinutesDelay = 0;
	}
	$meetingStartDelayHoursDelay = $_POST['meeting_start_delay_hours'];
	if ($meetingStartDelayHoursDelay == "") {
		$meetingStartDelayHoursDelay = 0;
	}
	$meetingStartDelayDaysDelay = $_POST['meeting_start_delay_days'];
	if ($meetingStartDelayDaysDelay == "") {
		$meetingStartDelayDaysDelay = 0;
	}
	//Months
	$meetingStartDelayMonthsDelay = $_POST['meeting_start_delay_months'];
	if ($meetingStartDelayMonthsDelay == "") {
		$meetingStartDelayMonthsDelay = 0;
	}
	//Years	
	$meetingStartDelayYearsDelay = $_POST['meeting_start_delay_years'];
	if ($meetingStartDelayYearsDelay == "") {
		$meetingStartDelayYearsDelay = 0;
	}	

	if(isset($_POST['should_remind']) && $_POST['should_remind'] == '0'){
			$_POST['reminder_time_meeting'] = -1;
			$meetingReminderTime = $_POST['reminder_time_meeting'];
	}
	else{
		$meetingReminderTime = $_POST['reminder_time_meeting'];
	}
	if(!isset($_POST['reminder_time_meeting'])){
		$_POST['reminder_time_meeting'] = $current_user->getPreference('reminder_time');
		if(empty($_POST['reminder_time_meeting'])){	
			$meetingReminderTime = -1;
		}
			
	}
	$startDelayType = $_POST['start_delay_type'];
	$assignedUserIdMeeting = $_POST["assigned_user_id_meeting"];
	if ($assignedUserIdMeeting == "Please Specify") {
		$assignedUserIdMeeting = '';
	}

	$meetingLocation = 	$_POST['meeting_location'];
	
	//Get the id of the template
	$query = "Insert into pm_process_task_meeting_defs set meeting_subject = '" .$meetingSubject ."'";
	$query .= ", reminder_time = " .$meetingReminderTime ." , start_delay_minutes = " .$meetingStartDelayMinutesDelay;
	$query .= ", start_delay_hours = " .$meetingStartDelayHoursDelay .", start_delay_days = " .$meetingStartDelayDaysDelay;
	$query .= ", start_delay_months = " .$meetingStartDelayMonthsDelay .", start_delay_years = " .$meetingStartDelayYearsDelay;
	$query .= ", meeting_description = '" .$meetingDescription ."' , task_id = '" .$focusID ."'";
	$query .= ", start_delay_type = '" .$startDelayType ."'";
	$query .= ", id = '" .$process_task_meeting_defs_id ."'";
	$query .= ", meeting_location = '" .$meetingLocation ."'";
	$query .= ", assigned_user_id_meeting = '" .$assignedUserIdMeeting ."'";
		
	$focus->db->query($query,true);
	
	//Now update the related id in the task table
		$query = "Update pm_processmanagerstagetask set meetings_defs_id = '" .$process_task_meeting_defs_id ."' where id = '" .$focusID ."'";
		$focus->db->query($query,true);
}

function updateCreateObjectDefs($focus,$id){
	$process_task_create_object_defs_id = create_guid();
	global $current_user;
//Get the fields from the post/request
	$newRecordType = $_POST['create_object_type'];
	$newRecordID = $_POST['create_object_id'];
	$description = $_POST['create_object_description'];
	//Minutes
	$newRecordStartDelayMinutesDelay = $_POST['create_object_delay_minutes'];
	if ($newRecordStartDelayMinutesDelay == "") {
		$newRecordStartDelayMinutesDelay = 0;
	}
	//Hours
	$newRecordStartDelayHoursDelay = $_POST['create_object_delay_hours'];
	if ($newRecordStartDelayHoursDelay == "") {
		$newRecordStartDelayHoursDelay = 0;
	}
	//Days
	$newRecordStartDelayDaysDelay = $_POST['create_object_delay_days'];
	if ($newRecordStartDelayDaysDelay == "") {
		$newRecordStartDelayDaysDelay = 0;
	}
	//Months
	$newRecordStartDelayMonthsDelay = $_POST['create_object_delay_months'];
	if ($newRecordStartDelayMonthsDelay == "") {
		$newRecordStartDelayMonthsDelay = 0;
	}
	//Years	
	$newRecordStartDelayYearsDelay = $_POST['create_object_delay_years'];
	if ($newRecordStartDelayYearsDelay == "") {
		$newRecordStartDelayYearsDelay = 0;
	}	
	$assignedUserIdCreateObject = $_POST["assigned_user_id_create_object"];
	if ($assignedUserIdCreateObject == "Please Specify") {
		$assignedUserIdCreateObject = '';
	}
	//Now get and set the check boxes
	$inheritParentData = $_POST["inherit_parent_data"];
	if ($inheritParentData == '') {
		$inheritParentData = 0;
	}
	$inheritParentRelationships = $_POST["inherit_parent_relationships"];
	if ($inheritParentRelationships == '') {
		$inheritParentRelationships = 0;
	}	
	//Get the id of the template
	$query = "Update pm_process_task_create_object_defs set create_object_type = '" .$newRecordType ."'";
	$query .= ",create_object_id = '" .$newRecordID ."' ,  create_object_delay_minutes = " .$newRecordStartDelayMinutesDelay;
	$query .= ", create_object_delay_hours = " .$newRecordStartDelayHoursDelay .", create_object_delay_days = " .$newRecordStartDelayDaysDelay;
	$query .= ", create_object_delay_months = " .$newRecordStartDelayMonthsDelay .", create_object_delay_years = " .$newRecordStartDelayYearsDelay;
	$query .= ", create_object_description = '" .$description ."' , inherit_parent_data = '$inheritParentData',inherit_parent_relationships = '$inheritParentRelationships'";
	$query .= ", assigned_user_id_create_object = '" .$assignedUserIdCreateObject ."'";
	$query .= " where id = '" .$id ."'";	
	$focus->db->query($query,true);
	
}

//**********************************************************************************
//Support for Creating Records
//*********************************************************************************

function insertCreateObjectDefs($focus,$focusID){
	$process_task_create_object_defs_id = create_guid();
	global $current_user;
//Get the fields from the post/request
	$newRecordType = $_POST['create_object_type'];
	$newRecordID = $_POST['create_object_id'];
	$description = $_POST['create_object_description'];
	//Minutes
	$newRecordStartDelayMinutesDelay = $_POST['create_object_delay_minutes'];
	if ($newRecordStartDelayMinutesDelay == "") {
		$newRecordStartDelayMinutesDelay = 0;
	}
	//Hours
	$newRecordStartDelayHoursDelay = $_POST['create_object_delay_hours'];
	if ($newRecordStartDelayHoursDelay == "") {
		$newRecordStartDelayHoursDelay = 0;
	}
	//Days
	$newRecordStartDelayDaysDelay = $_POST['create_object_delay_days'];
	if ($newRecordStartDelayDaysDelay == "") {
		$newRecordStartDelayDaysDelay = 0;
	}
	//Months
	$newRecordStartDelayMonthsDelay = $_POST['create_object_delay_months'];
	if ($newRecordStartDelayMonthsDelay == "") {
		$newRecordStartDelayMonthsDelay = 0;
	}
	//Years	
	$newRecordStartDelayYearsDelay = $_POST['create_object_delay_years'];
	if ($newRecordStartDelayYearsDelay == "") {
		$newRecordStartDelayYearsDelay = 0;
	}	
	$assignedUserIdCreateObject = $_POST["assigned_user_id_create_object"];
	if ($assignedUserIdCreateObject == "Please Specify") {
		$assignedUserIdCreateObject = '';
	}
	//Now get and set all the checkboxes
	//Now get and set the check boxes
	$inheritParentData = $_POST["inherit_parent_data"];
	if ($inheritParentData == '') {
		$inheritParentData = 0;
	}
	$inheritParentRelationships = $_POST["inherit_parent_relationships"];
	if ($inheritParentRelationships == '') {
		$inheritParentRelationships = 0;
	}		
	//Get the id of the template
	$query = "Insert into pm_process_task_create_object_defs set create_object_type = '" .$newRecordType ."'";
	$query .= ",create_object_id = '" .$newRecordID ."' ,  create_object_delay_minutes = " .$newRecordStartDelayMinutesDelay;
	$query .= ", create_object_delay_hours = " .$newRecordStartDelayHoursDelay .", create_object_delay_days = " .$newRecordStartDelayDaysDelay;
	$query .= ", create_object_delay_months = " .$newRecordStartDelayMonthsDelay .", create_object_delay_years = " .$newRecordStartDelayYearsDelay;
	$query .= ", create_object_description = '" .$description ."' , task_id = '" .$focusID ."'";
	$query .= ", id = '" .$process_task_create_object_defs_id ."', inherit_parent_data = '$inheritParentData', inherit_parent_relationships = '$inheritParentRelationships'";
	$query .= ", assigned_user_id_create_object = '" .$assignedUserIdCreateObject ."'";
		
	$focus->db->query($query,true);
	
}

?>
