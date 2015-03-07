<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/TimeDate.php');
global $previousTaskId;
global $runningProcessManager;
class ProcessManagerEngine1 extends ProcessManagerEngine {


	var $object_name = "ProcessManageEngine1";
	var $module_dir = 'ProcessManager';

	function ProcessManagerEngine1() {
		global $sugar_config;
		parent::SugarBean();
	}

	var $new_schema = true;


	//**************************************************************************************************
	//This is the main control block for the process manager engine for all process that are of type
	//modify and not create.
	//**************************************************************************************************
	function processManagerMain1($focusObjectId,$focusObjectType,$focusObjectEvent,$isDefault,$thisprocessManagerMain1){
		//First thing we are going to do is see if the focus object has a process setup for
		//a non create event and is a default process
		$doesObjectHaveNonCreateProcess = $this->checkObjectProcessNonCreate($focusObjectId,$focusObjectType,$focusObjectEvent,true);
		return;
	}
//**************************************************************************************************
//Here we are querying the pm_process_mgr_table to see if the object has a non create process
//**************************************************************************************************
function checkObjectProcessNonCreate($focusObjectId,$focusObjectType,$focusObjectEvent,$isDefault){
	$query = "Select * from pm_processmanager where process_object = '" .$focusObjectType ."'";
	$query .=" and start_event = 'Modify' and status = 'Active' and deleted = 0";
	$result = $this->db->query($query);
	//If we end up with a non create process then we must also have a filter table entry
		$counter = 1;
		while($row = $this->db->fetchByAssoc($result)){
			$process_id = $row['id'];
			$process_event = $row['start_event'];
			$cancel_on_event = $row['cancel_on_event'];
			$processObject = $row['process_object'];
			//***********************************************
			//Is this a cancel on event and if so then
			//call the function to go and remove all pending
			//tasks or stages
			//***********************************************
			
			if ($cancel_on_event != '--None--') {
				if ($cancel_on_event == 'Delete') {
					//See if the focus object has been set to deleted and if so then cancel out all process's
					if($this->getFocusObjectDeletedFlag($focusObjectId,$focusObjectType)){
					    //First cancel all stages and tasks waiting todo
					   
					    $this->cancelProcess($focusObjectId,$focusObjectType,$process_id);
						next($result);
					}
				}
				else{
				//Here we are going to see if the object qualifies for a cancel event
				//on the modification of the object
					$focusFieldsArray = array();
					$field = $row['process_object_cancel_field'];
					$value = $row['process_object_cancel_field_value'];
					$operator = $row['process_object_cancel_field_operator'];
					$focusFieldsArray[$field] = $field;
					$arrayFieldsFromFocusObject = $this->getFocusObjectFields($focusObjectId,$focusObjectType,$focusFieldsArray);
					$fieldValueFromFocusObect = $arrayFieldsFromFocusObject[$field];
					//Now compare the values
					$passCancelTest = true;
					if($field != '' && $value != '' && $field != 'Please Specify'){
					$passCancelTest = $this->getFilterTestResult($passCancelTest,$field,$operator,$value,$focusObjectId,$focusObjectType,$focusFieldsArray);
						if($passCancelTest){
								$this->cancelProcess($focusObjectId,$focusObjectType,$process_id);
								next($result);
						}
					}
					
				}
			}
			
			//Done with the checks for Cancel On Event
				$isDefaultProcessAlreadyDone = $this->checkIfDefaultProcessAlreadyDone($focusObjectId,$process_id,$this);		
				if (!$isDefaultProcessAlreadyDone) {				
					$resultProcessFilterTable = $this->getProcessFilterTableEntry($process_id);			
					$passFilterTest = true;
					while($rowProcessFilterTable =  $this->db->fetchByAssoc($resultProcessFilterTable)){						
						$rowProcessFilterTableID = $rowProcessFilterTable['id'];
						//If we have a process filter table entry then see if the field value pair is equal
						//to the focus object field value pair and if so then run the process - else exit
						//We use the function getFocusObjectFields
						if($rowProcessFilterTableID != ''){										
							$focusFieldsArray = array();
							$field = $rowProcessFilterTable['field_name'];
							$value = $rowProcessFilterTable['field_value'];
							//Get the Filter Operator: equal, not equal, less than, greater than
							$fieldOperator = $rowProcessFilterTable['field_operator'];							
							$focusFieldsArray[$field] = $field;
							//If we are checking for a custom field then call the function to get the 
							//custom fields for the object - else call the original getFocusObjectFields						
							//Dont do anything is both the field name and value are blank
							if ($field != '') {
								$passFilterTest = $this->getFilterTestResult($passFilterTest,$field,$fieldOperator,$value,$focusObjectId,$focusObjectType,$focusFieldsArray);
							}
						}
					}							
					//So we have a default process - now we are going to check to see if 
					//we are finally ready to enter the steps to check for stages and tasks
					//If there were any filters and all filter conditions passed then we are true
					//otherwise we would be false
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
		}
	
}	
	
//***********************************************************************************************
//This function will return the deleted flag for the given focus object
//***********************************************************************************************

function getFocusObjectDeletedFlag($focusObjectId,$focusObjectType){
	$query = "Select deleted from $focusObjectType where id = '" .$focusObjectId ."'";
	$result = $this->db->query($query);
	$row = $this->db->fetchByAssoc($result);
	$isDeleted = $row['deleted'];
	if ($isDeleted == 1) {
		return true;
	}
	else{
		return false;
	}
}

//************************************************************************************************
//This function will purge all stages and tasks that are in the waiting todo table for the given
//object.
//************************************************************************************************

function cancelProcess($focusObjectId,$focusObjectType,$process_id){
	$this->purgePendingStagesAndTasks($focusObjectId,$process_id);
}

function purgePendingStagesAndTasks($focusObjectId,$process_id){
		//First get the stages
		$queryDeleteStages = "Delete from pm_process_stage_waiting_todo where object_id = '";
		$queryDeleteStages .= $focusObjectId;
		$queryDeleteStages .= "' and process_id = '$process_id'";
		$this->db->query($queryDeleteStages, true);
		
		//Now get the tasks
		$queryDeleteTasks = "Delete from pm_process_task_waiting_todo where object_id = '";
		$queryDeleteTasks .= $focusObjectId;
		$queryDeleteTasks .= "'";
		$this->db->query($queryDeleteTasks, true);	
	
}

function convertMonth($month){
	switch ($month) {
		case "January":
		return "1";
		break;
		case "February":
		return "2";
		break;
		case "March":
		return "3";
		break;
		case "April":
		return "4";
		break;
		case "May":
		return "5";
		break;
		case "June":
		return "6";
		break;
		case "Jule":
		return "7";
		break;
		case "August":
		return "8";
		break;
		case "September":
		return "9";
		break;
		case "October":
		return "10";
		break;
		case "November":
		return "11";
		break;
		case "December":
		return "12";
		break;
	}


}

function convertday($n){

	switch ($n){
		case "Mon": return "Monday";break;
		case "Tue": return "Tuesday";break;
		case "Wed": return "Wednesday";break;
		case "Thu": return "Thursday";break;
		case "Fri": return "Friday";break;
		case "Sat": return "Saturday";break;
		case "Sun": return "Sunday";break;
	};

}

function curdate(){

	$ret = $this->convertday(date("D"));

	return $ret;
}

//************************************************************************************
//Functions to support the new feature for creating objects/records
//************************************************************************************

function createRecord($focusObjectType,$focusObjectId,$rowTask){
		//Go and get the defs record from pm_process_task_object_defs
		global $current_user;
		global $previousTaskId;
		global $newBean;
		$taskId = $rowTask['id'];
		//Get the defs file	
		$queryTaskCreateRecordDefs = "Select * from pm_process_task_create_object_defs where task_id = '" .$taskId ."'";
		$resultTaskCreateRecordDefs = $this->db->query($queryTaskCreateRecordDefs);
		$rowTaskCreateRecordDefs = $this->db->fetchByAssoc($resultTaskCreateRecordDefs);
		//Go and get the template to be used
		$createObjectType = $rowTaskCreateRecordDefs['create_object_type'];
		$createObjectId  = $rowTaskCreateRecordDefs['create_object_id'];
		$inheritParent = $rowTaskCreateRecordDefs['inherit_parent_data'];
		$inheritParentRelationships = $rowTaskCreateRecordDefs['inherit_parent_relationships'];
		$newBean = $this->createNewBean($createObjectType,$newBean);
		if ($inheritParent == 0) {
			//Use the template
			$newBean = $this->getCreateRecordTemplate($createObjectType,$createObjectId,$rowTaskCreateRecordDefs);
		}
		else{
			//Go and get the parent record
			$queryParentRecord = "Select * from $focusObjectType where id = '$focusObjectId'";
			$resultParentRecord = $this->db->query($queryParentRecord);
			$rowParentRecord = $this->db->fetchByAssoc($resultParentRecord);
			//Now go and set any values from the parent
			$newBean = $this->createRecordSetParentValues($newBean,$createObjectType,$rowParentRecord,$rowTaskCreateRecordDefs);			
		}
		//Now save the new bean
		$newBean->save(false);

		//Update the date fields for quotes and opps
		$newBean = $this->updateNewBeanDateAndDefaultFields($newBean,$createObjectType,$rowTaskCreateRecordDefs);
		//Now that we have the new record inserted we go and establish any mandatory relationships
		$this->buildMandatoryRelationships($newBean,$rowParentRecord,$createObjectType);
		//Now check to see if we need to add any non mandatory relationships 
		if ($inheritParentRelationships == 1) {
			//Use the template
			$newBean = $this->buildParentRelationships($newBean,$rowParentRecord,$createObjectType);
		}
}


//*******************************************************************************
//This function will first create the initial bean
//*******************************************************************************

function createNewBean($createObjectType,$newBean){
		//Determine the type of query filter  we are using
	//Default is name for query filter
	$beanName = '';
	$beanInstance = '';
	global $newBean;
	$focusObjectType = '';
	//Create an instance of the bean
	switch ($createObjectType) {
	    case "leads":
	        $beanName = 'Leads';
	        $beanInstance = 'Lead.php';
	        require_once("modules/$beanName/$beanInstance");
	       	$newBean = new Lead();
	        break;
	    case "contacts":
	        $beanName = 'Contacts';
	        $beanInstance = 'Contact.php';
	        require_once("modules/$beanName/$beanInstance");
	        $newBean = new Contact();
	        break;
	    case "quotes":
	        $beanName = 'Quotes';
	        $beanInstance = 'Quote.php';
	        require_once("modules/$beanName/$beanInstance");
	        $newBean = new Quote();
	        break;
	   case "opportunities":
	        $beanName = 'Opportunities';
	        $beanInstance = 'Opportunity.php';
	        require_once("modules/$beanName/$beanInstance");
	        $newBean = new Opportunity();
	        break;
	   case "cases":
	        $beanName = 'Cases';
	        $beanInstance = 'aCase.php';
	        require_once("modules/$beanName/Case.php");
	        $newBean = new aCase();
	        break;
	   case "bugs":
	        $beanName = 'Bugs';
	        $beanInstance = 'Bug.php';
	        require_once("modules/$beanName/$beanInstance");
	        $newBean = new Bug();
	        break; 
	   case "products":
	        $beanName = 'Products';
	        $beanInstance = 'Product.php';
	        require_once("modules/$beanName/$beanInstance");
	        $newBean = new Product();
	        break;
	   case "productbundle":
	        $beanName = 'ProductBundles';
	        $beanInstance = 'ProductBundle.php';
	        require_once("modules/$beanName/$beanInstance");
	        $newBean = new ProductBundle();
	        break;
	//Custom Module
	$focusObjectType = $this->checkIfCustomModule($createObjectType);
	if ($focusObjectType != '') {
			require_once("$focusObjectType");
			$createObjectType = $createObjectType ."()";
			$newBean = new $createObjectType;
			
	}
	                
	}
	return $newBean;
}

//*********************************************************************************
//This function will go and get the record that is used as the template
//********************************************************************************

function getCreateRecordTemplate($createObjectType,$createObjectId,$rowTaskCreateRecordDefs){

	
	$queryFilter = 'name';
	if ($createObjectType == 'quotes') {
		$queryFilter = 'quote_num';
	}
	if ($createObjectType == 'cases') {
		$queryFilter = 'case_number';
	}
	//Get the template record
	$queryRecordTemplate = "Select * from $createObjectType where $queryFilter = '$createObjectId'";
	$resultRecordTemplate = $this->db->query($queryRecordTemplate);
	$rowRecordTemplate = $this->db->fetchByAssoc($resultRecordTemplate);	

	$newBean = $this->setNewRecordValues($newBean,$createObjectType,$rowRecordTemplate);
	return $newBean;
	
}

//******************************************************************************
//If we are creating an opportunity or quote then we set the 
//*****************************************************************************
function createRecordSetParentValues($newBean,$createObjectType,$rowParentRecord,$rowTaskCreateRecordDefs){
	global $newBean;
	$newBean = $this->setNewRecordValues($newBean,$createObjectType,$rowParentRecord);
	//For some beans we need to set the data fields here and not just transfer the data over
	switch ($createObjectType) {
	    case "leads":
	        break;
	    case "contacts":
	        break;
	    case "quotes":
	    	//We add one to the system_id
	    	//$systemID = $newBean->system_id;
	    	//$systemID++;
	    	//Get the total number of quotes there are for this quote and increment
	    	$systemID = $this->getTotalQuotes($rowParentRecord);
	    	$newBean->system_id = $systemID;  
	        break;
	   	case "opportunities":
	        break;
	   	case "cases":
	   		//Get the next case 
	   		$nextCaseId = $this->getTableCount("cases");
	   		$newBean->case_number = $nextCaseId;  
	        break;
	   	case "bugs":
	        break; 	         	            
	}
	return $newBean;
}

//*********************************************************************************
//This function will take the record from the parent or template
//and also a result set from show fields for MYSQL and create all the
//fields values in the new bean
//***********************************************************************************
function setNewRecordValues($newBean,$createObjectType,$parentTemplateRecord){
	global $newBean;
	//Now show all the fields from this table
	$queryShowFields = "Show fields from $createObjectType";
	$resultShowFields = $this->db->query($queryShowFields);
	//Now for each record get the Field column
	while($rowShowFields = $this->db->fetchByAssoc($resultShowFields))
	{	
		$field = $rowShowFields['Field'];
		$array_dont_include = $this->dontIncludeInFieldList();
		if (in_array($field,$array_dont_include) === false) {
			//Get the Value from the template
			$value = $parentTemplateRecord[$field];
			//Now set the field for the new record
			$newBean->$field = $value; 
		}
	}
	return $newBean;
}

//*************************************************************
//This function holds thoses fields that we do not
//want to set - but instead let the save bean create the values
//*************************************************************
function dontIncludeInFieldList(){
		$array_fields_dont_include = array();
		$array_fields_dont_include[] = "id";
		$array_fields_dont_include[] = "date_entered";
		$array_fields_dont_include[] = "date_modified";
		return $array_fields_dont_include;
}

function getDueDate($startDelayDays,$startDelayHours,$startDelayMinutes,$startDelayMonths,$startDelayYears){
	$timezone = date('Z') / 3600;
	$timezone = substr($timezone,1);
	$today = date('Y-m-d H:i:s', time() + $timezone * 60 * 60);
	//First thing to do is to see if the delay is in days - if so add that many days to 
	//Create Date
	if ($startDelayDays != 0) {
		$hour = $hour + $startDelayHours;
	}		
	//Date Entered for Focus Object in this format - 2005-11-23 18:34:00
	$timedate = new TimeDate();
	$timezone = date('Z') / 3600;	
	//So we know the users timezone - and this value is a value like -8
	//So remove the - and get the offset.
	$timezone = substr($timezone,1);	
	$focusObjectCreateDateNew = date($today, time() - 8 * 60 * 60);	 
	list ($year, $month, $day, $hour, $min, $sec) = split ('[- :]', $today);	
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
	$callStartDateWhole = date("Y-m-d",mktime ($hour, $min, $sec, $month, $day, $year));
	return $callStartDateWhole;	
}


//******************************************************************************
//This function updates a problem with setting the due dates for quotes
//*****************************************************************************
function updateNewBeanDateAndDefaultFields($newBean,$createObjectType,$rowTaskCreateRecordDefs){
	global $newBean;
	$newBeanId = $newBean->id;
	$dueDateDelayMinutes = $rowTaskCreateRecordDefs['create_object_delay_minutes'];
	$dueDateDelayHours = $rowTaskCreateRecordDefs['create_object_delay_hours'];
	$dueDateDelayDays = $rowTaskCreateRecordDefs['create_object_delay_days'];
	$dueDateDelayMonths = $rowTaskCreateRecordDefs['create_object_delay_months'];
	$dueDateDelayYears = $rowTaskCreateRecordDefs['create_object_delay_years'];
	$dueDate = $this->getDueDate($dueDateDelayDays,$dueDateDelayHours,$dueDateDelayMinutes,$dueDateDelayMonths,$dueDateDelayYears);	
	//For some beans we need to set the data fields here and not just transfer the data over
	switch ($createObjectType) {
	    case "leads":
	        break;
	    case "contacts":
	        break;
	    case "quotes":
	    	//Calculate the due date
	    	$queryUpdate = "update quotes set quote_stage = 'Draft', date_quote_expected_closed = '$dueDate' where id = '$newBeanId'";
	    	$this->db->query($queryUpdate); 
	        break;
	   	case "opportunities":
	    	$queryUpdate = "update opportunities set sales_stage = 'Prospecting', date_closed = '$dueDate' where id = '$newBeanId'";
	    	$this->db->query($queryUpdate); 	   		
	        break;
	   	case "cases":
	        break;
	   	case "bugs":
	        break; 	         	            
	}
	return $newBean;
}

//************************************************************************************
//This function will take the newly created bean and establish mandatory relationships
//************************************************************************************

function buildMandatoryRelationships($newBean,$rowParentRecord,$createObjectType){
		$newBeanId = $newBean->id;
		$parentRecordId = $rowParentRecord['id'];
		switch ($createObjectType) {
	    case "leads":
	        break;
	    case "contacts":
	        break;
	    case "quotes":
	    	$relatedID = $this->getRelatedID('quotes_accounts','quote_id',$parentRecordId,'account_id');
	    	$this->insertRelationship('quotes_accounts','quote_id',$newBeanId,'account_id',$relatedID,'account_role','Bill To');
	        break;
	    //Accounts are mandatory    
	   	case "opportunities":
	    	$relatedID = $this->getRelatedID('accounts_opportunities','opportunity_id',$parentRecordId,'account_id');
	    	$this->insertRelationship('accounts_opportunities','opportunity_id',$newBeanId,'account_id',$relatedID,'','');	   		
	        break;
	   	case "cases":
	        break;
	   	case "bugs":
	        break; 	         	            
	}
}

//***********************************************************************************
//Build Parent Relationships -
//An array lists the relationships that we support
//***********************************************************************************
function buildParentRelationships($newBean,$rowParentRecord,$createObjectType){
	//First go and get the array of relationships
	$newBeanId = $newBean->id;
	$parentRecordId = $rowParentRecord['id'];
	$relationships = $this->getRelationshipArray($createObjectType);
	foreach ($relationships as $relationshipName=>$relationshipNameRightSide){
			switch ($createObjectType) {
			    case "leads":
			        break;
			    case "contacts":
			        break;
			    case "quotes":
			    	//strip out the quotes from relationship name and you end up with the target table name
			    	$relatedID = $this->getRelatedID($relationshipName,'quote_id',$parentRecordId,$relationshipNameRightSide);
			    	if ($relationshipName == 'quotes_contacts') {
			    		$this->insertRelationship($relationshipName,'quote_id',$newBeanId,$relationshipNameRightSide,$relatedID,'contact_role','Ship To');
			    	}
			    	else{
			    		$this->insertRelationship($relationshipName,'quote_id',$newBeanId,$relationshipNameRightSide,$relatedID,'','');
			    	}
			        break;
			    //Accounts are mandatory    
			   	case "opportunities":
			    	$relatedID = $this->getRelatedID($relationshipName,'opportunity_id',$parentRecordId,$relationshipNameRightSide);
			    	if ($relationshipName == 'opportunities_contacts') {
			    		$this->insertRelationship($relationshipName,'opportunity_id',$newBeanId,$relationshipNameRightSide,$relatedID,'contact_role','Bill To');
			    	}
			    	else{
			    		$this->insertRelationship($relationshipName,'opportunity_id',$newBeanId,$relationshipNameRightSide,$relatedID,'','');
			    	} 					 		
			        break;
			   	case "cases":
			        break;
			   	case "bugs":
			        break; 	         	            
		}	
	}
		//Now if the object is a quote then check for product bundles
		//Now go and build out the product bundles
		//First thing is to build each bundle
		if ($createObjectType == 'quotes') {
			$resultQuoteBundles = $this->checkQuoteBundles($parentRecordId);
			while($rowQuoteBundles = $this->db->fetchByAssoc($resultQuoteBundles))
			{	
				//Now go and get the specific product_bundle for the given product bundle quote
				$rowBundle = $this->getProductBundle($rowQuoteBundles);
				$newProductBundleBean = $this->createNewBean('productbundle',$newProductBundleBean);
				$newProductBundleBean = $this->createRecordSetParentValues($newProductBundleBean,'product_bundles',$rowBundle,'');
				$newProductBundleBean->save(false);
				//Now create the relationship between the quote and the product bundle
				//This is the entry in the product_bundle_quote
				$newProductBundleBeanId = $newProductBundleBean->id;
				$this->insertRelationship('product_bundle_quote','bundle_id',$newProductBundleBeanId,'quote_id',$newBeanId,'bundle_index',$rowQuoteBundles['bundle_index']);
				//Now go and and get each product for this bundle and build new
				//Check to see if there are any line items for the quote and if so then build them
	    		$resultProductBundleProduct = $this->getProductBundleProduct($rowBundle['id']);
				while($rowProductBundleProduct = $this->db->fetchByAssoc($resultProductBundleProduct))
				{	
					$productID = $rowProductBundleProduct['product_id'];
					//Get the current product - we return the row for the specific product
					$rowQuoteProducts = $this->getProduct($productID);
					$newProductBean = $this->createNewBean('products',$newProductBean);
					$newProductBean = $this->createRecordSetParentValues($newProductBean,'products',$rowQuoteProducts,'');
					$newProductBean->save(false);
					$newProductId = $newProductBean->id;
					$this->insertRelationship('product_bundle_product','bundle_id',$newProductBundleBeanId,'product_id',$newProductId,'product_index',$rowProductBundleProduct['product_index']);				
				}
			}
		}
}

function getRelationshipArray($createObjectType){
	$arrayRelationships = array();
	switch ($createObjectType) {
	    case "leads":
	        break;
	    case "contacts":
	        break;
	    case "quotes":
	    	$arrayRelationships['quotes_contacts'] = 'contact_id';
	    	$arrayRelationships['quotes_opportunities'] = 'opportunity_id';
	        break;
	   	case "opportunities":
	   		$arrayRelationships['opportunities_contacts'] = 'contact_id';
	   		$arrayRelationships['quotes_opportunities'] = 'quote_id';		
	        break;
	   	case "cases":
	        break;
	   	case "bugs":
	        break; 	         	            
	}
	return $arrayRelationships;
}

//****************************************************************************
//This function will get the related id from the relationship table for the given
//relationship for the new bean.
//****************************************************************************

function getRelatedID($tableName,$parentRecordColumn,$parentRecordId,$relatedColumnID){
	$queryRelatedID = "Select $relatedColumnID from $tableName where $parentRecordColumn = '$parentRecordId'";
	$resultRelatedRecord = $this->db->query($queryRelatedID);
	$rowRelatedRecord = $this->db->fetchByAssoc($resultRelatedRecord);
	$relatedID = $rowRelatedRecord[$relatedColumnID];
	return $relatedID;
}

//******************************************************************************
//This function takes two record id's along with the related table name
//and builds a new entry for the related table
//******************************************************************************

function insertRelationship($tableName,$fieldLeftName,$fieldLeftValue,$rightFieldName,$rightFieldValue,$additionalField,$additionalFieldValue){
	$timezone = date('Z') / 3600;
	$timezone = substr($timezone,1);
	$today = date('Y-m-d H:i:s', time() + $timezone * 60 * 60);
	$newGuid = create_guid();
	$queryInsertRelationship = "Insert into $tableName set id = '$newGuid', $fieldLeftName = '$fieldLeftValue', $rightFieldName = '$rightFieldValue', date_modified = '$today'";
	if ($additionalField != '') {
		$queryInsertRelationship .= " , $additionalField = '$additionalFieldValue'";
	}
	$this->db->query($queryInsertRelationship);
}

//This functio will get the product bundles
function checkQuoteBundles($parentRecordId){
	$queryBundles = "Select * from product_bundle_quote where quote_id  = '$parentRecordId'";
	$resultQuoteBundles = $this->db->query($queryBundles);
	return $resultQuoteBundles;
}

function getProductBundleProduct($bundleId){
	$queryProductBundleProduct = "Select * from product_bundle_product where bundle_id = '$bundleId'";
	$resultProductBundleProduct = $this->db->query($queryProductBundleProduct);
	return $resultProductBundleProduct;	
}

//Function to get the product bundle when passed the product bundle quote
function getProductBundle($rowQuoteBundles){
	$productBundleId = $rowQuoteBundles['bundle_id'];
	$query = "Select * from product_bundles where id = '$productBundleId'";
	$result = $this->db->query($query);
	$row = $this->db->fetchByAssoc($result);
	return $row;
}

function getTableCount($table){
	$quoteName = $rowParentRecord['name'];
	$query = "select count() from $table ";
	$result = $this->db->query($query);
	$row = $this->db->fetchByAssoc($result);
	$count = $row['count(*)'];
	return $count;
}

//This function will find out how many quotes there are for a given name and return the count/
//Used to determint the system id

function getTotalQuotes($rowParentRecord){
	$quoteName = $rowParentRecord['name'];
	$query = "select id from quotes where name = '$quoteName'";
	$result = $this->db->query($query);
	$count = 1;
	while($row = $this->db->fetchByAssoc($result)){
		$count++;
	}
	return $count;
}

//**********************************************************************
//This function will return the results of the products for the parent quote
//**********************************************************************

function getProduct($parentRecordId){
	$queryProducts = "Select * from products where id  = '$parentRecordId'";
	$resultQuoteProducts = $this->db->query($queryProducts);
	$rowProduct = $this->db->fetchByAssoc($resultQuoteProducts);
	return $rowProduct;
}

}
?>