<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * Copyright: SierraCRM, Inc. 2009
 * Portions created by SierraCRM are Copyright (C) SierraCRM, Inc.
 * The contents of this file are subject to the SierraCRM, Inc. End User License Agreement
 * You may not use this file except in compliance with the License. 
 * You may not rent, lease, lend, or in any way distribute or transfer any rights or this file or Process Manager or Marketing Campaigns
 * registrations (purchased licenses) to third parties without SierraCRM, Inc. written approval, and subject to
 * agreement by the recipient of the terms of this EULA.
 * Process Manager and Marketing Campaigns for SugarCRM is owned by SierraCRM, Inc. and is protected by international and local copyright laws and
 * treaties. You must not remove or alter any copyright notices on any copies of Process Manager or Marketing Campaigns for SugarCRM. 
 * You may not use, copy, or distribute Process Manager or Marketing Campaigns for SugarCRM, except as granted by SierraCRM, Inc.
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


class insertIntoPmEntryTable extends SugarBean {

function insertIntoPmEntryTable() {
	global $sugar_config;
	parent::SugarBean();
	}
	
function setPmEntryTable(&$bean, $event, $arguments) {
	//We need the id for the record and the table name and object
	$recordID = $bean->id;
	$objectName = $bean->object_name;
	$tableName = $bean->table_name;
	$dateEntered = 	$bean->date_entered;
	//Was this a create of modify?
	//We know if this is a create or modify by looking for the date_entered key in the bean
	//First check to see if this is a delete event
	if ($event == 'after_delete') {
		$create = false;
		$update_or_insert = 'modify';
	}
	elseif ($dateEntered != '') {
		$create = true;
		$update_or_insert = 'insert';
	}
	else{
		$create = false;
		$update_or_insert = 'modify';
	}
	$this->insertIntoProcessMgrEntryTable($tableName,$recordID,$update_or_insert);
  }
  
function insertIntoProcessMgrEntryTable($tableName,$objectId,$update_or_insert){
		
	$entryTableId = create_guid();
	$query = "Insert into pm_processmanager_entry_table set id = '" .$entryTableId ."'";
	$query .= ", object_id = '" .$objectId."', object_type = '" .$tableName ."'";
	$query .=", object_event = '" .$update_or_insert ."'";
	$result = $this->db->query($query);	
	}  
	
}


?>
