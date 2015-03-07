<?php
/*********************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by KINAMU Business Solutions AG. All rights ar (c) 2010 by KINAMU Business
 * Solutions AG.
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of KINAMU Business Solutions AG
 *
 * You can contact KINAMU Business Solutions AG at Am Concordepark 2/F12
 * A-2320 Schwechat or via email at office@kinamu.com
 *
 ********************************************************************************/
 require_once('modules/KReports/KReport.php');
 require_once('include/MVC/Controller/SugarController.php');
 require_once('modules/Contacts/Contact.php');
 require_once('modules/KReports/utils.php');
 require_once('include/utils/db_utils.php');
 require_once('include/utils.php');


 class KReportsController extends SugarController
{

	/*
	 * handle the views
	 */
	function action_detailview(){
		// set the view controller
		if($this->bean->listtype == '') $this->bean->listtype = 'standard';
		$this->view = $this->bean->listtype . $GLOBALS['dictionary']['KReport']['edition'];
	}
	/*
	 * Custom Action for Soap Call to get Modules List
	 */
	function action_get_modules(){
		global $app_list_strings;
		foreach($app_list_strings['moduleList'] as $module => $description)
		{
			$returnArray[] = array('module' => $module, 'description' => $description);
		}

		print json_encode_kinamu($returnArray);
	}



	function action_get_reports(){
		global $app_list_strings, $db, $current_user;
		$queryArray = preg_split('/::/', $_REQUEST['node']);
			switch($queryArray[0])
			{
				case 'src':
					$returnArray[] = array('id' => 'favorites', 'text' => 'Favorites', 'expanded' => true);
					$returnArray[] = array('id' => 'modules', 'text' => 'by Module', 'expanded' => true);
					break;
				case 'modules':
					if(isset($_SESSION['KReports']['lastviewed'])) $lastViewedArray = preg_split('/::/', $_SESSION['KReports']['lastviewed']);
					$modulesQuery = 'SELECT distinct report_module FROM kreports ';

					// check if we have KINAMu orManagement Installed for Authorization Check
					if(file_exists('modules/KOrgObjects/KOrgObject.php'))
					{
						require_once('modules/KOrgObjects/KOrgObject.php');
						$thisKOrgObject = new KOrgObject();
						$modulesQuery .=  $thisKOrgObject->getOrgunitJoin('kreports', 'KReport', 'kreports', '1') . ' ';
					}

					$modulesQuery .= 'WHERE deleted =  \'0\' ORDER BY report_module ASC';

					$reportResults = $db->query($modulesQuery);

					while($moduleEntry = $db->fetchByAssoc($reportResults))
					{
						$returnArray[] = array('id' => 'module::' . $moduleEntry['report_module'], 'text' => $app_list_strings['moduleList'][$moduleEntry['report_module']], 'expanded' => (isset($lastViewedArray[0]) && $lastViewedArray[0] == $moduleEntry['report_module'] ) ? true : false);
					}
					break;
				case 'module':
					$moduleQuery = 'SELECT * FROM kreports ';

					if(file_exists('modules/KOrgObjects/KOrgObject.php'))
					{
						require_once('modules/KOrgObjects/KOrgObject.php');
						$thisKOrgObject = new KOrgObject();
						$moduleQuery .=  $thisKOrgObject->getOrgunitJoin('kreports', 'KReport', 'kreports', '1') . ' ';
					}

					$moduleQuery .= 'WHERE report_module = \'' .  $queryArray[1] . '\' AND deleted =  \'0\' ORDER BY report_module ASC';

					$reportResults = $db->query($moduleQuery);

					while($moduleEntry = $db->fetchByAssoc($reportResults))
					{
						$returnArray[] = array('id' => $moduleEntry['id'], 'leaf' => true, 'text' => $moduleEntry['name'], 'href' => 'index.php?module=KReports&action=DetailView&record=' . $moduleEntry['id'] );
					}
					break;
				case 'favorites':
					$returnArray[] = array('id' => 'last10', 'leaf' => false, 'text' => 'last 10');
					$returnArray[] = array('id' => 'top10', 'leaf' => false, 'text' => 'top 10');

					$reportResults = $db->query('SELECT * FROM kreportsfavorites WHERE user_id = \'' . $current_user->id . '\'  ORDER BY description ASC');
					while($moduleEntry = $db->fetchByAssoc($reportResults))
					{
						$returnArray[] = array('id' => $moduleEntry['report_id'], 'leaf' => true, 'text' => $moduleEntry['description'], 'href' => 'index.php?module=KReports&action=DetailView&record=' . $moduleEntry['report_id'] . '&favid=' . $moduleEntry['report_id']);
					}
					break;
				case 'last10':
					$reportResults = $db->query('SELECT report_id, name FROM kreportstats INNER JOIN kreports ON kreports.id = kreportstats.report_id  WHERE user_id = \'' . $current_user->id . '\' GROUP  BY report_id ORDER BY max(date) DESC');
					while($moduleEntry = $db->fetchByAssoc($reportResults))
					{
						$returnArray[] = array('id' => $moduleEntry['report_id'], 'leaf' => true, 'text' => $moduleEntry['name'], 'href' => 'index.php?module=KReports&action=DetailView&record=' . $moduleEntry['report_id']);
					}
					break;
				case 'top10':
					$reportResults = $db->query('SELECT report_id, name FROM kreportstats INNER JOIN kreports ON kreports.id = kreportstats.report_id  WHERE user_id = \'' . $current_user->id . '\' GROUP  BY report_id ORDER BY count(kreportstats.id) DESC');
					while($moduleEntry = $db->fetchByAssoc($reportResults))
					{
						$returnArray[] = array('id' => $moduleEntry['report_id'], 'leaf' => true, 'text' => $moduleEntry['name'], 'href' => 'index.php?module=KReports&action=DetailView&record=' . $moduleEntry['report_id']);
					}
					break;
		}
		print json_encode_kinamu($returnArray);
	}
	/*
	 * Custom Action for Soap Call to get Report Query
	 */

	function action_get_new_sql(){
		require_once('modules/KReports/KReport.php');
		require_once('modules/KReports/KReportQuery.php');

		$thisReport = new KReport();
		$thisReport->retrieve($_REQUEST['record']);

		if(isset($_REQUEST['whereConditions']))
		{
		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));
		}

	    $sqlArray = $thisReport->get_report_main_sql_query();

	    return $sqlArray['select'] . ' ' . $sqlArray['from'] . ' ' . $sqlArray['where'] . ' ' . $sqlArray['groupby'] . ' ' . $sqlArray['having'] . ' ' . $sqlArray['orderby'];

	}

	/*
	 * Custom Action for Soap Call to get Sna�pshots for a Report
	 */
	function action_get_snapshots(){
		require_once('modules/KReports/KReport.php');

		$thisReport = new KReport();
		$thisReport->retrieve($_REQUEST['requester']);

		print json_encode_kinamu($thisReport->getSnapshots());
	}

	/*
	 * Custom action to get schedules for Reports
	 */
	function action_get_report_schedule(){
		global $db, $current_user;


		switch($_REQUEST['api'])
		{
			case 'read':
				$schedulesResults = $db->query('SELECT * FROM kreportschedules WHERE user_id = \'' . $current_user->id . '\' AND report_id = \'' . $_REQUEST['requester'] . '\' AND deleted = \'0\'');

				while($thisScheduledResultentry = $db->fetchByAssoc($schedulesResults))
				{
					$resultArray['data'][] = array('jobid' => $thisScheduledResultentry['id'],
									   'month' => $thisScheduledResultentry['month'],
				                       'dayofweek' => $thisScheduledResultentry['dayofweek'],
				                       'dayofmonth' => $thisScheduledResultentry['dayofmonth'],
				                       'hour' => $thisScheduledResultentry['hour'],
				                       'minutes' => $thisScheduledResultentry['minutes'],
									   'action' => $thisScheduledResultentry['action'],
				                       'receipients' => $thisScheduledResultentry['receipients']
								);
				}

				// return the json encoded results
				print json_encode_kinamu($resultArray);
				break;
			case 'create':

				$thisRecord = json_decode(html_entity_decode_utf8($_REQUEST['data']));
				$newGuid = create_guid();

				$db->query('INSERT INTO kreportschedules SET ' .
							"id='" . $newGuid . "', " .
							"user_id='" . $current_user->id . "', " .
				            "report_id='" . $_REQUEST['requester'] . "', ".
						    "month='" . $thisRecord->month . "', ".
							"dayofmonth='" . $thisRecord->dayofmonth . "', ".
							"dayofweek='" . $thisRecord->dayofweek . "', ".
							"hour='" . $thisRecord->hour . "', ".
							"minutes='" . $thisRecord->minutes . "', ".
							"action='" . $thisRecord->action . "', ".
							"receipients='" . $thisRecord->receipients . "', ".
							"deleted = '0'"
				);


				print json_encode_kinamu(array('data' => array(array('jobid' => $newGuid,
									   'month' => $thisRecord->month,
				                       'dayofmonth' => $thisRecord->dayofmonth,
				  					   'dayofweek' => $thisRecord->dayofweek,
				                       'hour' => $thisRecord->hour,
				                       'minutes' => $thisRecord->minutes,
									   'action' => $thisRecord->action,
				                       'receipients' => $thisRecord->receipients
								)), 'success' => true));
				break;
			case 'update':
				$thisRecord = json_decode(html_entity_decode_utf8($_REQUEST['data']));
				$db->query('UPDATE  kreportschedules SET ' .
						    "month='" . $thisRecord->month . "', ".
							"dayofmonth='" . $thisRecord->dayofmonth . "', ".
							"dayofweek='" . $thisRecord->dayofweek . "', ".
							"hour='" . $thisRecord->hour . "', ".
							"minutes='" . $thisRecord->minutes . "', ".
							"action='" . $thisRecord->action . "', ".
							"receipients='" . $thisRecord->receipients . "' ".
							"WHERE id='" . $thisRecord->jobid . "'"
				);
				print json_encode_kinamu(array('success' => true));
				break;
			case 'destroy':
				$db->query('UPDATE  kreportschedules SET ' .
							"deleted='1' ".
							"WHERE id=" . html_entity_decode_utf8($_REQUEST['data'])
				);
				print json_encode_kinamu(array('success' => true));
				break;
				break;
		}
	}

	function action_get_report_massupdate(){
		global $beanFiles, $beanList, $app_list_strings;

		$retarray = array();
		$retarray['data'] = array();

		$thisReport = new KReport();
		$thisReport->retrieve($_REQUEST['requester']);

		$langArray = return_module_language('en_us', $thisReport->report_module);

		require_once($beanFiles[$beanList[$thisReport->report_module]]);
		$nodeModule = new $beanList[$thisReport->report_module];

		foreach($nodeModule->field_defs as $fieldname => $fielddefs)
		{
			if(isset($fielddefs['massupdate']) && $fielddefs['massupdate'] == true)
			{
				$retarray['data'][] = array(
					'fieldname' => $fieldname,
				    'fieldlabel' => isset($fielddefs['vname']) ? isset($langArray[$fielddefs['vname']]) ? $langArray[$fielddefs['vname']] : $fielddefs['vname'] : $fieldname,
					'fieldtype' => $fielddefs['type'],
					'fieldoptions' => isset($fielddefs['options']) ? json_encode($app_list_strings[$fielddefs['options']]) : ''
				);
			}
		}

		echo json_encode($retarray);
	}

	/*
	 * Custom Action for Soap Call to get Sna�pshots for a Report
	 */
	function action_get_listfields(){
		require_once('modules/KReports/KReport.php');

		$thisReport = new KReport();
		$thisReport->retrieve($_REQUEST['record']);

		print json_encode_kinamu($thisReport->getListfields());
	}

	/*
	 * Function lo load enum values
	 */

	function action_take_snapshot()
	{
		global $db;

		  require_once('modules/KReports/KReport.php');
		  require_once('include/utils.php');
		  $thisReport = new KReport();
		  $thisReport->retrieve($_REQUEST['record']);

		  $thisReport->takeSnapshot();
		  return true;

	}


	function action_export_to_excel()
	{
	  global $current_user;

	  require_once('modules/KReports/KReport.php');
	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);


	  // check if we have set dynamic Options
	  if(isset($_REQUEST['dynamicoptions']))
	  // Bugfix 2010-11-12 to handle dynamic options in Excel Export
	  //		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['dynamicoptions']));
	  			$_REQUEST['whereConditions'] = $_REQUEST['dynamicoptions'];

	  echo $thisReport->createCSV();

	}

	function action_check_isfavorite(){
		global $current_user, $db;

		print ($db->getRowCount($db->query('SELECT id FROM kreportsfavorites WHERE user_id = \'' . $current_user->id . '\' AND report_id = \'' . $_REQUEST['record'] . '\'')) > 0) ? 'true' : 'false';

	}
	// function to add to favorites
	function action_add_report_to_favorites()
	{
		global $current_user, $db;

		$db->query('INSERT INTO kreportsfavorites SET id=\'' . create_guid() . '\', user_id = \'' . $current_user->id . '\', report_id = \'' . $_REQUEST['record'] . '\', description = \'' . $_REQUEST['favorite_name'] . '\', report_where = \'' . $_REQUEST['report_where'] . '\'');

	}

	function action_remove_report_from_favorites()
	{
		global $current_user, $db;

		$db->query('DELETE FROM kreportsfavorites WHERE user_id = \'' . $current_user->id . '\' AND report_id = \'' . $_REQUEST['record'] . '\'');

	}

	/*
	 * Function to generate Target List
	 */
	function action_export_to_targetlist()
	{
	  global $current_user;


	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);

	  // check if we have set dynamic Options
	  if(isset($_REQUEST['whereConditions']))
	  		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));

	  $thisReport->createTargeList($_REQUEST['targetlist_name']);

	  return true;
	}

	function action_check_export_to_targetlist()
	{

	  global $current_user;

	  require_once('modules/KReports/KReport.php');
	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);
	  // $results = $thisReport->getSelectionResults();

	  	require_once('modules/ProspectLists/ProspectList.php');
	  	$newProspectList = new ProspectList();

	  	// fill with results:
	  	$newProspectList->load_relationships();

	  	$linkedFields = $newProspectList->get_linked_fields();

	  	$foundModule = false;

	  	foreach($linkedFields as $linkedField => $linkedFieldData)
	  	{
	  		if($newProspectList->$linkedField->_relationship->rhs_module == $thisReport->report_module)
	  		{
	  		 	$foundModule = true;
	  		}
	  	}

	    print ($foundModule) ? 'true' : 'false';


	}


	function action_check_access_level()
	{

	  global $current_user, $db;
	  require_once('modules/KReports/KReport.php');
	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);

	  require_once('modules/ACL/ACLController.php');

	  if(ACLController::checkAccess($thisReport->module_dir,'edit',  $thisReport->assigned_user_id == $current_user->id ? true : false))
	  {
		  if(ACLController::checkAccess($thisReport->module_dir,'delete', $thisReport->assigned_user_id == $current_user->id ? true : false))
		  		print 2;
		  else
		  		print 1;
	  }
	  else
	  {
      	print 0;
	  }

	}

	function action_get_userids()
	{
		global $db;

		$returnArray['count'] = $db->getRowCount($db->query('SELECT id, user_name FROM users WHERE deleted = \'0\' AND status = \'Active\' AND user_name like \''. $_REQUEST['query'] . '%\''));

		//if(isset($_REQUEST['query']) && $_REQUEST['query'] != '')
 		$usersResult = $db->query('SELECT id, user_name FROM users WHERE deleted = \'0\' AND status = \'Active\' AND user_name like \''. $_REQUEST['query'] . '%\' LIMIT ' .  $_REQUEST['start'] . ',' . $_REQUEST['limit']);
 		//else
 		//	$usersResult = $db->query('SELECT id, user_name FROM users WHERE deleted = \'0\' AND status = \'Active\'');

 		while($userRecord = $db->fetchByAssoc($usersResult))
 		{
 			// bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
 			$returnArray['data'][] = array('value' => $userRecord['id'], 'text' => $userRecord['user_name']);
 		}



 		echo json_encode($returnArray);
	}


	function action_get_enum()
	{

	 	global $app_list_strings, $beanFiles, $beanList, $db;

	 	// explode the path
	 	$pathArray = explode('::', $_REQUEST['path']);

	 	// get Field and Module from the path
	 	$fieldArray = explode(':',$pathArray[count($pathArray) - 1]);
	 	$moduleArray = explode(':',$pathArray[count($pathArray) - 2]);

	 	// load the parent module
	 	require_once($beanFiles[$beanList[$moduleArray[1]]]);
	 	$parentModule = new $beanList[$moduleArray[1]];

	 	if($moduleArray[0] == 'link')
	 	{
		 	// load the Relationshop to get the module
		 	$parentModule->load_relationship($moduleArray[2]);

		 	// load the Module
		 	$thisModuleName = $parentModule->$moduleArray[2]->getRelatedModuleName();
		 	require_once($beanFiles[$beanList[$parentModule->$moduleArray[2]->getRelatedModuleName()]]);
		 	$thisModule = new $beanList[$parentModule->$moduleArray[2]->getRelatedModuleName()];

		 	// pars the otpions into the return array
	 		switch($thisModule->field_name_map[$fieldArray[1]]['type'])
	 		{
	 			case 'enum':
	 			case 'multienum':
		 		 foreach($app_list_strings[$thisModule->field_name_map[$fieldArray[1]]['options']] as $value => $text)
				 	{
				 		$returnArray[] = array('value' => $value, 'text' => $text);
				 	}
			 	break;
	 			case 'user_name':
	 			case 'assigned_user_name':
	 				$returnArray[] = array('value' => 'current_user_id', 'text' => 'active user');
	 				$usersResult = $db->query('SELECT id, user_name FROM users WHERE deleted = \'0\' AND status = \'Active\'');
	 				while($userRecord = $db->fetchByAssoc($usersResult))
	 				{
	 					// bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
	 					$returnArray[] = array('value' => $userRecord['user_name'], 'text' => $userRecord['user_name']);
	 				}
	 				break;
	 		}

	 	}
	 	else
	 	{
	 		// we have the root module
	 		switch($parentModule->field_name_map[$fieldArray[1]]['type'])
	 		{
	 			case 'enum':
	 			case 'multienum':
		 		 foreach($app_list_strings[$parentModule->field_name_map[$fieldArray[1]]['options']] as $value => $text)
				 	{
				 		$returnArray[] = array('value' => $value, 'text' => $text);
				 	}
			 	break;
	 			case 'user_name':
	 			case 'assigned_user_name':
	 				$returnArray[] = array('value' => 'current_user_id', 'text' => 'active user');
	 				$usersResult = $db->query('SELECT id, user_name FROM users WHERE deleted = \'0\' AND status = \'Active\'');
	 				while($userRecord = $db->fetchByAssoc($usersResult))
	 				{
	 					// bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
	 					$returnArray[] = array('value' => $userRecord['user_name'], 'text' => $userRecord['user_name']);
	 				}
	 				break;
	 		}
	 	}


	 	print json_encode_kinamu($returnArray);
	}

	/*
	 * Custom Action to load the Report Data
	 * also gets called during paging limit and start currently only works for MySQL
	 * MSSQL needs adoption
	 */
	function action_load_report(){
		global $db;

		require_once('modules/KReports/KReport.php');

		$thisReport = new KReport();
		$thisReport->retrieve($_REQUEST['requester']);

		// set the override Where if set in the request
		if(isset($_REQUEST['whereConditions']))
		{
		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));
		}

		$totalArray = array();
		$totalArray['records'] = $thisReport->getSelectionResults(array('noFormat' => true , 'start' => isset($_REQUEST['start']) ? $_REQUEST['start'] : 0 , 'limit' => isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 0) , isset($_REQUEST['snapshotid']) ? $_REQUEST['snapshotid'] : '0', false);


		if(isset($_REQUEST['doCount']) && $_REQUEST['doCount'] == 'true')
		{
			 $totalArray['count'] = $thisReport->getSelectionResults(array('start' => $_REQUEST['start'], 'limit' => $_REQUEST['limit']), isset($_REQUEST['snapshotid']) ? $_REQUEST['snapshotid'] : '0', true);
		}
		else
		{
			 $totalArray['count'] = (count($totalArray['records']) < $_REQUEST['limit'] ? $_REQUEST['start'] + count($totalArray['records']) : $_REQUEST['start'] + $_REQUEST['limit'] + 1);
		}

		// jscon encode the result and return it
		$json_string = json_encode_kinamu($totalArray);
		echo $json_string;

		exit();
	}

	function action_load_report_count(){
		global $db;

		require_once('modules/KReports/KReport.php');

		$thisReport = new KReport();
		$thisReport->retrieve($_REQUEST['requester']);

		// set the override Where if set in the request
		if(isset($_REQUEST['whereConditions']))
		{
		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));
		}


		echo $thisReport->getSelectionResults(array('start' => $_REQUEST['start'], 'limit' => $_REQUEST['limit']), isset($_REQUEST['snapshotid']) ? $_REQUEST['snapshotid'] : '0', true);
	}


	function action_load_report_tree(){

		// set the header to JSON .. nic ebut not needed ..
		header('Content-Type: application/json');

		// processing
		require_once('modules/KReports/KReport.php');
		$thisReport = new KReport();
		$thisReport->retrieve($_REQUEST['requester']);

		$currentGroupLevel = 1;
		$filterArray = '';

		//build the filter for the node ..
		if(isset($_REQUEST['node']) && $_REQUEST['node'] != 'root')
		{
			$tmp_filterArray = preg_split('/::/', $_REQUEST['node']);
			foreach($tmp_filterArray as $ilterSeq => $filterDef)
			{
				$filterEntryArray = preg_split('/:/', $filterDef);
				$filterArray[$filterEntryArray[0]] = $filterEntryArray[1];
			}
			$currentGroupLevel = count($filterArray) + 1;
		}

		// get the results for the node
		//$maxGroupLevel = $thisReport->getMaxGroupLevel();

		// get the grouping fields
		$listTypeProperties =  json_decode(html_entity_decode($thisReport->listtypeproperties), true);
 		$thisReportGroupings = json_decode($listTypeProperties[0], true);

		//if($currentGroupLevel > $maxGroupLevel)
		if($currentGroupLevel < count($thisReportGroupings))
			$resultRecords = $thisReport->getSelectionResults(array('noFormat' => false, 'toPDF' => true) , '0', false,  $filterArray, array($thisReportGroupings[$currentGroupLevel - 1]['fieldid']));
		else
			$resultRecords = $thisReport->getSelectionResults(array('noFormat' => false, 'toPDF' => true) , '0', false,  $filterArray, array($thisReportGroupings[count($thisReportGroupings) - 1]['fieldid']));

		// now get the format ... first we did not format to keep original values for the later selection
		// need that for the ID
		// $formattedResultRecords =$thisReport->formatFields($resultRecords, false);

		//$levelFieldId = $thisReport->getGroupLevelId($currentGroupLevel);
		$levelFieldId = $thisReportGroupings[$currentGroupLevel - 1]['fieldid'];

		// get the list fields array since we need to check against that one
		$listFieldsAray = $thisReport->getListFieldsArray();

		foreach($resultRecords as $thisRecordId => $thisRecordData)
		{
			$returnArray = array();
			$returnArray['id'] = (isset($_REQUEST['node']) && $_REQUEST['node'] != 'root' ? $_REQUEST['node'] . '::' : '') . $levelFieldId . ':' .$thisRecordData[$levelFieldId];
			$returnArray['leaf'] = $currentGroupLevel == count($thisReportGroupings) /*$maxGroupLevel*/ ? true : false;

			// process all the other entry fields
			foreach($thisRecordData as $fieldId => $fieldValue)
			{

				if(count($thisReportGroupings) /*$maxGroupLevel*/ == $currentGroupLevel || (count($thisReportGroupings) /*$maxGroupLevel*/ > $currentGroupLevel &&  $listFieldsAray[$fieldId]['sqlfunction'] != '-'))
					$returnArray[$fieldId] = $thisRecordData[$fieldId];
				else
					$returnArray[$fieldId] = '';
			}

			// set the text if we still have a field
			if($levelFieldId != '')
			    $returnArray[$thisReportGroupings[count($thisReportGroupings) - 1]['fieldid']] = $thisRecordData[$thisReportGroupings[$currentGroupLevel - 1]['fieldid']];
				//$returnArray['text'] = $thisFormattedRecordData[$levelFieldId];


			$return[] = $returnArray;
		}

		//json encode an return
		print json_encode_kinamu($return);

	}
	/*
	 * Custom SOAP Function to get Nodes
	 * Called from extjs Framework to get further nodes for a selected module
	 */
	function action_get_nodes(){
		// main processing
		global $_REQUEST, $beanFiles, $beanList;
		if($_REQUEST['node'] != 'unionroot')
		{
			$nodeArray = explode(':', $_REQUEST['node']);

			$returnArray = array();

			if($nodeArray[0] == 'root' || preg_match('/union/',$nodeArray[0]) > 0)
			{
				print json_encode_kinamu($this->buildNodeArray($nodeArray['1'], 'TREE'));
			}
			if($nodeArray[0] == 'link')
			{
				require_once($beanFiles[$beanList[$nodeArray['1']]]);
				$nodeModule = new $beanList[$nodeArray['1']];
				$nodeModule->load_relationship($nodeArray['2']);

				$returnJArray = json_encode_kinamu($this->buildNodeArray($nodeModule->$nodeArray['2']->getRelatedModuleName(), 'TREE', $nodeModule->$nodeArray['2']));

				print $returnJArray;
			}

		}
		else
			echo '';
	}

	/*
	 * Custom Action to get the Fields for a Module
	 */
	function action_get_fields()
	{
		global $_REQUEST, $beanFiles, $beanList;

		$nodeArray = explode(':', $_REQUEST['nodeid']);

		$returnArray = array();

		// check if we have the root module or a union module ...
		if($nodeArray[0] == 'root' || preg_match('/union/', $nodeArray[0]) == 1)
		{
			print json_encode_kinamu($this->buildFieldArray($nodeArray['1']));
		}
		if($nodeArray[0] == 'link')
		{
			require_once($beanFiles[$beanList[$nodeArray['1']]]);
			$nodeModule = new $beanList[$nodeArray['1']];
			$nodeModule->load_relationship($nodeArray['2']);

			$returnJArray = json_encode_kinamu($this->buildFieldArray($nodeModule->$nodeArray['2']->getRelatedModuleName()));

			print $returnJArray;
		}

		if($nodeArray[0] == 'relationship')
		{
			require_once($beanFiles[$beanList[$nodeArray['1']]]);
			$nodeModule = new $beanList[$nodeArray['1']];
			$nodeModule->load_relationship($nodeArray['2']);
			$returnJArray = json_encode_kinamu($this->buildLinkFieldArray($nodeModule->$nodeArray['2']));

			print $returnJArray;
		}
	}
	/*
	 * Helper function to get the Fields for a module
	 */
	function buildFieldArray($module){
		global $beanFiles, $beanList;
		require_once('include/utils.php');
		require_once($beanFiles[$beanList[$module]]);
		$nodeModule = new $beanList[$module];
		foreach($nodeModule->field_name_map as $field_name => $field_defs)
		{
			if($field_defs['type'] != 'link'
				&& $field_defs['type'] != 'relate'
				&& ($field_defs['source'] != 'non-db'
					|| ($field_defs['source'] == 'non-db'
						&& $field_defs['type'] == 'kreporter')
					)
				)
			{
				$returnArray[] = array(
									'id' => 'field:' . $field_defs['name'],
									'text' => $field_defs['name'],
									// in case of a kreporter field return the report_data_type so operators ar processed properly
									'type' => ($field_defs['type'] == 'kreporter') ? $field_defs['report_data_type'] :  $field_defs['type'],
									'name' => (translate($field_defs['vname'],$module ) != '') ? translate($field_defs['vname'],$module ) : $field_defs['name'],
									'leaf' => true
							);
			}
		}
		return $returnArray;

	}

	/*
	 * Helper Function to build the nodes ...
	 */
	function buildNodeArray($module, $requester, $thisLink = ''){
		global $beanFiles, $beanList;
		require_once('include/utils.php');
		require_once($beanFiles[$beanList[$module]]);
		$nodeModule = new $beanList[$module];

		$nodeModule->load_relationships();

		// see if we have a link with Relationship Fields
		/* not released yet
		if($thisLink->_relationship->join_table != '')
		{
			$nodeId = $_REQUEST['node'];
			$newNodeId = preg_replace('/link/', 'relationship',$nodeId);

			$returnArray[] = array(
										'id' => $newNodeId,
										'text' => $thisLink->_relationship_name,
										'leaf' => true
								);
		}
		*/
		foreach($nodeModule->field_name_map as $field_name => $field_defs)
		{
			if($field_defs['type'] == 'link')
			{
				//BUGFIX 2010/07/13 to display alternative module name if vname is not maintained
				if(isset($field_defs['vname']))
					$returnArray[] = array(
										'id' => 'link:' . $module . ':' . $field_name,
										'text' => ((translate($field_defs['vname'], $module)) == "" ? ('[' . $field_defs['name'] . ']') : (translate($field_defs['vname'], $module))),
										'leaf' => false
								);
				elseif(isset($field_defs['module']))
					$returnArray[] = array(
										'id' => 'link:' . $module . ':' . $field_name,
										'text' => translate($field_defs['module'],$module),
										'leaf' => false
								);
				else
					$returnArray[] = array(
										'id' => 'link:' . $module . ':' . $field_name,
										'text' => get_class($nodeModule->$field_defs['relationship']->_bean),
										'leaf' => false
								);
			}
		}
		return $returnArray;
	}

	function buildLinkFieldArray($thisLink){

		global $db;

		$queryRes = $db->query('describe ' . $thisLink->_relationship->join_table);

		while($thisRow = $db->fetchByAssoc($queryRes))
		{
			$returnArray[] = array(
						'id' => 'field:' . $thisRow['Field'],
						'text' => $thisRow['Field'],
						// in case of a kreporter field return the report_data_type so operators ar processed properly
						'type' => 'varchar',
						'name' => $thisRow['Field'],
						'leaf' => true
				);
		}

		return $returnArray;
	}

	/*
	 * Helper to load the charts via AJAX
	 *
	 */
	function action_getcharthtml()
	{

	  global $current_user;

	  require_once('modules/KReports/KReport.php');
	  require_once('modules/KReports/KReportChart.php');
	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);

   	  // set the override Where if set in the request
	  if(isset($_REQUEST['whereConditions']))
	  {
		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));
	  }

	  $thisChartArray = new KReportChartArray($thisReport, json_decode_kinamu(html_entity_decode_utf8($thisReport->chart_params_new)), 300, $thisReport->chart_layout);

	  $chartDataXML = $thisChartArray->getUpdatedChartsXML();

	  print $chartDataXML;

	}

	/*
	 * Begin professional Versio
	 */

	function action_get_single_chart()
	{
	  global $current_user;

	  require_once('modules/KReports/KReport.php');
	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);
	  $thisReport->buildChartArray();
	  $result = $thisReport->renderChart($_REQUEST['chartindex'], $_REQUEST['height'], $_REQUEST['snapshot'], false, $_REQUEST['chartid'], true);

	  echo $result;
	}

	function action_get_trendchart()
	{
	  global $current_user;

	  require_once('modules/KReports/KReport.php');
	  require_once('modules/KReports/KReportChart.php');
	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);
	  $thisReportchart = new KReportChart($thisReport);

	  $result = $thisReportchart->renderTrendChart($_REQUEST['height'], $_REQUEST['dataSeriesFieldId'], $_REQUEST['dimensionsFieldId'], $_REQUEST['chartid'], $_REQUEST['chartType']);

	  echo $result;
	}

	/*
	 * function that returns the generated SQL Query
	 */
	function action_get_sql()
	{
	  global $current_user;

	  require_once('modules/KReports/KReport.php');
	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);

	  // set the override Where if set in the request
	  if(isset($_REQUEST['whereConditions']) && $_REQUEST['whereConditions'] != '')
	  {
		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));
	  }

	  //echo $thisReport->get_report_main_sql_query('', true, '');

	  $thisReport->get_report_main_sql_query('', true, '');
	  echo $thisReport->kQueryArray->countSelectString;

	  //$sqlArray = $thisReport->get_report_main_sql_query('', true, '');
	  //echo $sqlArray['select'] . ' ' . $sqlArray['from'] . ' ' . $sqlArray['where'] . ' ' . $sqlArray['groupby'] . ' ' . $sqlArray['having'] . ' ' . $sqlArray['orderby'];
	}

	function action_duplicate_report()
	{
	  global $current_user, $db, $beanList;

	  require_once('modules/KReports/KReport.php');

	  //$thisReport->retrieve($_REQUEST['record']);

	  $row = $db->fetchByAssoc($db->query('SELECT * FROM kreports WHERE id=\'' . $_REQUEST['record'] . '\''));

	  $thisReport = new KReport();
	  $thisReport->populateFromRow($row);
	  $thisReport->id = create_guid();
	  $thisReport->new_with_id = true;
	  $thisReport->name = $_REQUEST['newName'];
	  $thisReport->save();

	  if($beanList['KOrgObjects']) {
	      // also duplicate the privileges if korgobjects is installed
	      $resultSet = $db->query("SELECT * FROM korgobjects_beans WHERE bean_id = '" . $db->quote($_REQUEST['record']) . "' AND bean_name = 'KReport' AND deleted = 0");
	      while($row = $db->fetchByAssoc($resultSet)) {
	          $db->query("INSERT INTO korgobjects_beans (id, korgobject_id, bean_id, date_modified, bean_name, from_sap, deleted)
	                           VALUES ('" . create_guid() . "', '" . $row['korgobject_id'] . "', '" . $thisReport->id . "', '" . $row['date_modified'] . "', '" . $row['bean_name'] . "', '" . $row['from_sap'] . "', '" . $row['deleted'] . "')");
	      }
	  }
	}

	/*
	function action_get_charts(){
		require_once('modules/KReports/KReport.php');

		$thisReport = new KReport();
		$thisReport->retrieve($_REQUEST['requester']);
		print 'getcharts';
		//print json_encode_kinamu($thisReport->getCharts());
	}
	*/
	function action_export_to_pdf()
	{

		require_once('modules/KReports/pdf/basicTable.php');
		ob_clean();
		createTCPDF();
	}


	function action_export_to_pdf_x()
	{
		global $current_user;

		// include for PDF processing
		require_once('modules/KReports/fpdf/kinamufpdf.php');

		define('FPDF_FONTPATH','modules/KReports/fpdf/font/');

		// process the report to get header and rows
		 require_once('modules/KReports/KReport.php');
		  $thisReport = new KReport();
		  $thisReport->retrieve($_REQUEST['record']);

		  // go get the results
		  $results = $thisReport->getSelectionResults(array('toPDF' => true));

		  if(count($results > 0))
		  {

		  	$pdf=new kinamufpdf((substr($thisReport->pdforientation,0,1) != '' ? substr($thisReport->pdforientation,0,1) : 'L'), 'pt', 'A4');
		  	// get Sizes
		  	// A4 = 210 x 297
		  	$pageWidth = $pdf->wPt;// 297 * 72 * 0.0393700787;
		  	$pageHeigth = $pdf->hPt; //210;
		  	$pageBorder = 10 * 72 * 0.0393700787;
		  	$chartSpace = 5;

		  	// set Report Name
		  	$pdf->reportName = $thisReport->name;

			$pdf->AliasNbPages();

		  	  // get the width for the columns
			  $arrayList =  json_decode_kinamu( html_entity_decode_utf8($thisReport->listfields));
		 	  $fieldArray = '';
		 	  $fieldIdArray = array();
		 	  foreach($arrayList as $thisList){
		 	  	    if($thisList['display'] == 'yes')
		 	  	    {
		 				$fieldArray[] = array('label' => utf8_decode($thisList['name']), 'width' => (isset($thisList['width']) && $thisList['width'] != '' && $thisList['width'] != '0') ? $thisList['width'] : '100', 'display' => $thisList['display']);
						$fieldIdArray[] = $thisList['fieldid'];
		 	  	    }
		 	  }

		 	  // reset headers et.
			  $header = array();
			  $rows = array();
			  $i = 0;
			  foreach($results as $record)
			  {
			  	$getHeader = (count($header) == 0) ? true : false;
			  	foreach($record as $key => $value)
			  	{
			  		$arrayIndex = array_search($key, $fieldIdArray);

			  		if(array_search($key, $fieldIdArray) !== false)
			  		{
				  		if($getHeader) $header[] = $key;
				  		$rows[$i][] = iconv("UTF-8", $current_user->getPreference('default_export_charset'), $value);

			  		}
			  	}
			  	$i++;
			  }

			$pdf->AddPage();

			// add the chart we downloaded before
			if($_REQUEST['withchart'] == 'yes')
			{
				$chartCount = explode('x', $thisReport->chart_layout);

				// get the width for each Chart
				$graphWidth = ($pageWidth - (2 * $pageBorder) - (($chartCount[1] -  1) * $chartSpace) ) / $chartCount[1]; // * 0.0393700787 * 72;

				$imageSize = getimagesize('modules/KReports/KFC/images/' . str_replace('-','', $thisReport->id) . 'index1.jpg');

				$currentYPos = $pdf->GetY();

				for($j = 1; $j <= $chartCount[0]; $j++)
				{

					$imageYPos = $currentYPos + (($j -1) * (( $imageSize[1] * $graphWidth / $imageSize[0] ) + $chartSpace) );

					for($i = 1; $i <= $chartCount[1]; $i++)
					{

						$imageXPos = $pageBorder + (($graphWidth + $chartSpace ) * ($i - 1));// - ($imageWidth / 2);

						// output the image
			    		$pdf->Image('modules/KReports/KFC/images/' . str_replace('-','', $thisReport->id) . 'index' . ((($j - 1) * $chartCount[1]) + $i) . '.jpg', $imageXPos ,$imageYPos,($graphWidth > $imageSize[0]) ? 0 : $graphWidth,0 );

					}
				}

				// set the Y Position
				$pdf->SetXY($pageBorder, $imageYPos + (( $imageSize[1] * $graphWidth / $imageSize[0] ) + $chartSpace));

			}


			$pdf->FormatedTable($header, $rows, $fieldArray);

			ob_flush();

			// output the PDF
			$pdf->Output('kinamu_reporter.pdf', 'D');
		  }
	}

	// ENDE Pro
    function action_export_to_kml() {
		  $thisReport = new KReport();
		  $thisReport->retrieve($_REQUEST['record']);

		  // check if we have set dynamic Options
		  if(isset($_REQUEST['whereConditions']))
		  		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));

		  $xmlData = $thisReport->createKML();
		  echo $xmlData;

    }

    // for the maps integration
    function action_get_report_geocodes(){

      $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);

	  // check if we have set dynamic Options
	  if(isset($_REQUEST['whereConditions']))
	  		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));

	  echo $thisReport->getGeoCodes();
    }

	/*
	 * Function to generate Target List
	 */
	function action_geocode_report_results()
	{
	  global $current_user;


	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);

	  // check if we have set dynamic Options
	  if(isset($_REQUEST['whereConditions']))
	  		  $thisReport->whereOverride = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));

	  $thisReport->massGeoCode();

	  return true;
	}

	/*
	 * Function to save standard list layout
	 */

	function action_save_standard_layout()
	{
	  global $current_user;

	  $thisReport = new KReport();
	  $thisReport->retrieve($_REQUEST['record']);

	  $layoutParams = json_decode_kinamu(html_entity_decode($_REQUEST['layoutparams']));

	  $listFields =  json_decode_kinamu( html_entity_decode($thisReport->listfields));

	  // process the Fields
	  foreach($listFields as $thisFieldIndex => $thisListField)
	  {
	  	  reset($layoutParams);
	  	  foreach($layoutParams as $thisLayoutParam)
	  	  {
	  	  	if($thisLayoutParam['dataIndex'] == $thisListField['fieldid'])
	  	  	{
		  		$thisListField['width'] = $thisLayoutParam['width'];
		  		$thisListField['sequence'] =   (string)$thisLayoutParam['sequence'];
		  		$thisListField['display'] = $thisLayoutParam['isHidden'] ? 'no' : 'yes';
		  		$listFields[$thisFieldIndex] = $thisListField;
		  		break;
	  	  	}
	  	  }
	  }

	  usort($listFields, 'arraySortBySequence');

	  $thisReport->listfields = json_encode_kinamu($listFields);
	  echo $thisReport->save();
	  echo $thisReport->listfields;
	}
}

/*
 * function for array sorting
 */
function arraySortBySequence($a, $b)
{
    return ($a['sequence'] < $b['sequence']) ? -1 : 1;
}

?>