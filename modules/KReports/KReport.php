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
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


require_once('modules/KReports/utils.php');
require_once('modules/KReports/KReportQuery.php');

// Task is used to store customer information.

global $dictionary;
if($dictionary['KReport']['edition'] == 'premium')
{
	require_once('modules/KReports/KFC/FusionCharts_Gen.php');
	require_once('modules/KReports/KFC/KFC_Colors.php');
 	require_once('modules/KReports/BingMaps/BingMaps.php');
}

class KReport extends SugarBean {
    var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	var $team_id;

	var $description;
	var $name;
	var $status;
	var $assigned_user_name;

	var $team_name;

	var $table_name = "kreports";
	var $object_name = "KReport";
	var $module_dir = 'KReports';

	var $importable = true;
	// This is used to retrieve related fields from form posts.
	// var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_phone', 'contact_email', 'parent_name');

	/*
	//what we need to build the where join string
	var $tablePath;
	var $joinSegments;
	var $rootGuid;
	var $fromString;
	*/
	var $whereOverride;
	
	//2010-02-10 add Field name Mapping
	var $fieldNameMap;
	
	// the query Array
	var $kQueryArray;

	//2011-02-03 for the total values
	var $totalResult = '';
	
	
	// variable taht allows to turn off the evaluation of SQL Functions
	// needed if we let the Grid do this
	var $evalSQLFunctions = true;
	
	// varaible to hold the depth of the join tree
	var $maxDepth; 

	function KReport() {
		parent::SugarBean();
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
	
	function get_summary_text()
	{
		return $this->name;
	}
	
	/*
	 * Function to get the enum values for a field
	 */
	
	function getEnumValues($fieldId)
	{
		global $app_list_strings;
		/*
		// get the field list as array
		$arrayList =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));
		
		// loop through the array until we found our field
		$i = 0;
		while($arrayList[$i]['fieldid'] != $fieldId && $i < count($arrayList))
		   $i++;
		   
		// check if we have a match or just did reach the end of the arry woithout success
		if($arrayList[$i]['fieldid'] == $fieldId)
		{
			$fieldName = substr($arrayList[$i]['path'], strrpos($arrayList[$i]['path'], "::") + 2, strlen($arrayList[$i]['path']));
			$pathName = substr($arrayList[$i]['path'], 0, strrpos($arrayList[$i]['path'], "::"));
		
		    $fieldArray = explode(':', $fieldName);
			// we should have the path and the fieldname
			// sicne we processed the query before $this->joinsegments has to be populated
			if(isset($this->joinSegments[$pathName]) && $this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['type'] == 'enum' )
			{
				return $app_list_strings[$this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['options']];
			}
			else
			{
				return '';
			}*/
		// fix 2010-10-25 .. enums not found for charts
		if(isset($this->fieldNameMap[$fieldId]['fields_name_map_entry']['options']))	
		{
			return $app_list_strings[$this->fieldNameMap[$fieldId]['fields_name_map_entry']['options']];
		}
		else
		{
			return '';
		}
		
	}
	
	function fill_in_additional_detail_fields() 
	{
		parent::fill_in_additional_detail_fields();
		if($this->report_module != '')
		{
			//$sqlArray = $this->build_sql_string();
			
			//$this->sql_statement = $sqlArray['select'] . ' ' . $sqlArray['from'] . ' ' . $sqlArray['where'] . ' ' . $sqlArray['groupby'] . ' ' . $sqlArray['orderby'] ;
		}	
	}
	

	
	/*
	 * Function to return the Fielname from a given Path
	 */
	function getFieldNameFromPath($pathName)
	{
		return substr($pathName, strrpos($pathName, "::") + 2, strlen($pathName));
	}
	
	/*
	 * Function to return the Pathname from a given Path
	 */
	function getPathNameFromPath($pathName)
	{
		return substr($pathName, 0, strrpos($pathName, "::"));
	}
	
	function get_report_main_sql_query($evalSQLFunctions = true, $additionalFilter = '', $additionalGroupBy = array()){
		//global $db, $app_list_strings, $beanList, $beanFiles;
		
		// bugfix add ENT_QUOTES so we get proper translation of also single quotes 2010-25-12
		$arrayWhere =  json_decode_kinamu( html_entity_decode($this->whereconditions, ENT_QUOTES));
		$arrayList =  json_decode_kinamu( html_entity_decode($this->listfields, ENT_QUOTES));
		$arrayWhereGroups = json_decode_kinamu( html_entity_decode($this->wheregroups, ENT_QUOTES));
		$arrayUnionList = json_decode_kinamu(html_entity_decode($this->unionlistfields, ENT_QUOTES));
		
		// evaluate report Options and pass them along to the Query Array
		$reportOptions = json_decode_kinamu(html_entity_decode($this->reportoptions, ENT_QUOTES));
		
		if(isset($reportOptions['authCheck'])) $paramsArray['authCheck'] = $reportOptions['authCheck'];
		if(isset($reportOptions['showDeleted'])) $paramsArray['showDeleted'] = $reportOptions['showDeleted'];
		
		$this->kQueryArray = new KReportQueryArray($this->report_module, $this->union_modules, $evalSQLFunctions, $arrayList, $arrayUnionList, $arrayWhere, $additionalFilter, $arrayWhereGroups, $additionalGroupBy, $paramsArray);
		$sqlString = $this->kQueryArray->build_query_strings();
		$this->fieldNameMap = $this->kQueryArray->fieldNameMap;
		return $sqlString;
		// return array('select' => $this->kQueryArray->selectString, 'from' => $this->kQueryArray->fromString, 'where' => $this->kQueryArray->whereString ,'fields' => '', 'groupby' => $this->kQueryArray->groupbyString, 'having' => $this->kQueryArray->havingString , 'orderby' => $this->kQueryArray->orderbyString);

	}	
	/*
	 * build the SQL String
	 * deprecated will be removed
	 */
	
	function build_sql_string(){
		global $db, $app_list_strings, $beanList, $beanFiles;
		
		$arrayWhere =  json_decode_kinamu( html_entity_decode_utf8($this->whereconditions));
		$arrayList =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));
		$arrayWhereGroups = json_decode_kinamu( html_entity_decode_utf8($this->wheregroups));
		
		$kQuery = new KReportQuery($this->report_module, $this->evalSQLFunctions, $arrayList, $arrayWhere, $arrayWhereGroups);
		
		$kQuery->build_query_strings();
		$this->fieldNameMap = $kQuery->fieldNameMap;
		
		return array('select' => $kQuery->selectString, 'from' => $kQuery->fromString, 'where' => $kQuery->whereString ,'fields' => '', 'groupby' => $kQuery->groupbyString, 'orderby' => $kQuery->orderbyString);

	}
	// 2010-12-18 added function for formatting based on FieldType
	function getFieldTypeById($fieldID){
		if($this->fieldNameMap == null) $this->get_report_main_sql_query('', true, '');
		return $this->fieldNameMap[$fieldID]['type'];
	}
	
	function buildLinks($fieldArray, $excludeFields = array()){
		global $app_list_strings, $timedate;
		
		foreach($fieldArray as $fieldID => $fieldValue)
		{
			if(isset($this->fieldNameMap[$fieldID]) && $this->fieldNameMap[$fieldID]['islink'] && !in_array($fieldID, $excludeFields))
			{
				// swith if we have aunion query
				if(isset($fieldArray['unionid']))
					$fieldValue = '<a href="index.php?module=' . $this->kQueryArray->queryArray[$fieldArray['unionid']]['kQuery']->fieldNameMap[$fieldID]['module'] . '&action=DetailView&record=' . $fieldArray[$this->kQueryArray->queryArray[$fieldArray['unionid']]['kQuery']->fieldNameMap[$fieldID]['tablealias'] . 'id'] .'" target="_new" class="tabDetailViewDFLink">' . $fieldValue . '</a>';
				else
					$fieldValue = '<a href="index.php?module=' . $this->kQueryArray->queryArray['root']['kQuery']->fieldNameMap[$fieldID]['module'] . '&action=DetailView&record=' . $fieldArray[$this->fieldNameMap[$fieldID]['tablealias'] . 'id'] .'" target="_new" class="tabDetailViewDFLink">' . $fieldValue . '</a>';
			}
			$returnArray[$fieldID] = $fieldValue;
		}
		return $returnArray;
	}
	
	function calculateValueOfTotal($fieldArray)
	{
		// set the returnarray
		$returnArray = $fieldArray;
		
		// this is ugly .. whould bring this to the front
		foreach($this->kQueryArray->queryArray['root']['kQuery']->listArray as $thisFieldData)
		{
			if($thisFieldData['valuetype'] != '' && $thisFieldData['valuetype'] != '-'  && isset($this->totalResult[$thisFieldData['fieldid'] . '_total']) && $this->totalResult[$thisFieldData['fieldid'] . '_total'] > 0)
			{
				$valuetypeArray = split('OF', $thisFieldData['valuetype']);
				switch($valuetypeArray[0])
				{
					case 'P':
						// calculate the value
						$returnArray[$thisFieldData['fieldid']] = round((double)$returnArray[$thisFieldData['fieldid']] / (double)$this->totalResult[$thisFieldData['fieldid'] . '_total'] * 100, 2);
						
						// set the format to float so we interpret this as number
						$this->fieldNameMap[$thisFieldData['fieldid']]['type'] = 'float';
						$this->fieldNameMap[$thisFieldData['fieldid']]['format_suffix'] = '%';
						break;
				}
			}
		}
		
		// return the Results
	    return $returnArray;
	}
	
	function formatFields($fieldArray, $excludeFields = array()){
		require_once('modules/Currencies/Currency.php');
		
		global $app_list_strings, $timedate;
		
		foreach($fieldArray as $fieldID => $fieldValue)
		{
			// get the FieldDetails from the Query
			$fieldDetails = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);
			
			if(isset($this->fieldNameMap[$fieldID]) && !in_array($fieldID, $excludeFields) && (!isset($fieldDetails['customsqlfunction']) || (isset($fieldDetails['customsqlfunction']) && $fieldDetails['customsqlfunction'] == '')))
			{
				switch($this->fieldNameMap[$fieldID]['type'])
				{
					case 'currency':
						// 2010-12-16 right align the field if it is a currency field
						$fieldValue = '<div style="text-align:right;">' . currency_format_number($fieldValue, array('currency_id' => $fieldArray[$fieldID . '_curid'], 'currency_symbol' => true)) . '</div>';
						break;
					case 'int': 
					case 'float':
					case 'double':
					    // BUG 2010-10-29 diaplay number formatted properly
					    // $fieldValue = format_number($fieldValue, 0, 0);
						$fieldValue = currency_format_number($fieldValue, array('currency_symbol' => false));
						// see if we need to add a suffix (used for the percantage values)
						if(isset($this->fieldNameMap[$fieldID]['format_suffix']))
							$fieldValue .= $this->fieldNameMap[$fieldID]['format_suffix'];
					    break;
					case 'enum': 
						$fieldValue = $app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][$fieldValue];
						break;
					case 'multienum':
						// do not format if we have a function (Count ... etc ... )
						if($this->fieldNameMap[$fieldID]['sqlFunction'] == '')
						{
							$fieldArray = preg_split('/\^,\^/',$fieldValue);
							//bugfix 2010-09-22 if only one value is selected 
							if(is_array($fieldArray) && count($fieldArray) > 1)
							{
								$fieldValue = '';
								foreach($fieldArray as $thisFieldValue)
								{
									if($fieldValue != '') $fieldValue .= ', ';
									//bugfix 2010-09-22 trim the prefix since this is starting and ending with 
									$fieldValue .= 	$app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($thisFieldValue, '^')];
								}
							}
							else
							{
								$fieldValue = $app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($fieldValue, '^')];
							}
						}
						// $fieldValue = '<FONT style="COLOR: yellow"><B><I>' . $fieldValue . '</FONT></B></I>';
						break;
					case 'date':
						$fieldValue = $timedate->to_display_date($fieldValue);
						break;
					case 'datetime':
						$fieldValue = $timedate->to_display_date_time($fieldValue);
						break;
				}
				/* removed since we build links separate
				// check if we have to establish a link
				if($buildlinks && $this->fieldNameMap[$fieldID]['islink'])
				{
					$fieldValue = '<a href="index.php?module=' . $this->fieldNameMap[$fieldID]['module'] . '&action=DetailView&record=' . $fieldArray[$this->fieldNameMap[$fieldID]['tablealias'] . 'id'] .'" target="_new" class="tabDetailViewDFLink">' . $fieldValue . '</a>';
				}
				*/
			}
			
			
			$returnArray[$fieldID] = $fieldValue;
		}
		
		return $returnArray;
	}
	/*
	 * ónly render enums to the language depended values - if we do not format
	 */
	function formatEnums($fieldArray, $excludeFields = array()){
		require_once('modules/Currencies/Currency.php');
		
		global $app_list_strings, $timedate;
		
		foreach($fieldArray as $fieldID => $fieldValue)
		{
			// get the FieldDetails from the Query
			$fieldDetails = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);
			
			if(isset($this->fieldNameMap[$fieldID]) && !in_array($fieldID, $excludeFields) && (!isset($fieldDetails['customsqlfunction']) || (isset($fieldDetails['customsqlfunction']) && $fieldDetails['customsqlfunction'] == '')))
			{
				switch($this->fieldNameMap[$fieldID]['type'])
				{

					case 'enum': 
						$fieldValue = $app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][$fieldValue];
						break;
					case 'multienum':
						// do not format if we have a function (Count ... etc ... )
						if($this->fieldNameMap[$fieldID]['sqlFunction'] == '')
						{
							$fieldArray = preg_split('/\^,\^/',$fieldValue);
							//bugfix 2010-09-22 if only one value is selected 
							if(is_array($fieldArray) && count($fieldArray) > 1)
							{
								$fieldValue = '';
								foreach($fieldArray as $thisFieldValue)
								{
									if($fieldValue != '') $fieldValue .= ', ';
									//bugfix 2010-09-22 trim the prefix since this is starting and ending with 
									$fieldValue .= 	$app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($thisFieldValue, '^')];
								}
							}
							else
							{
								$fieldValue = $app_list_strings[$this->fieldNameMap[$fieldID]['fields_name_map_entry']['options']][trim($fieldValue, '^')];
							}
						}
						break;
				}
			}
			
			
			$returnArray[$fieldID] = $fieldValue;
		}
		
		return $returnArray;
	}

 	function getXtypeRenderer($fieldType, $fieldID = '')
 	{
 		global $current_user;
 		
 		// check if we have a custom SQL function -- then reset the value .. we do  not know how to format
 		$listFieldArray = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);
 		
 		// manage switching of Fieldtypes
 		if($listFieldArray['sqlfunction'] == 'COUNT') $fieldType = 'int';
 		if($listFieldArray['customsqlfunction'] != '') $fieldType = '';
 		if($listFieldArray['valuetype'] != '-' && $listFieldArray['valuetype'] != '') $fieldType = 'percentage';
 		
 		// process thee fieldtypes
 		switch($fieldType)
 				{
 					case 'currency':
 						$numberFormat = '0';
 						// check if we have a 1000 separator
 						if($_SESSION[$current_user->user_name . '_PREFERENCES']['global']['num_grp_sep'] != '')
 							$numberFormat .=  $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['num_grp_sep'] . '000';
 						//check if we hav significant digits
 						if( $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['default_currency_significant_digits'] > 0)
 						{
 						 $numberFormat .=  $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['dec_sep'];
 						 for($i=0; $i < $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['default_currency_significant_digits']; $i++) $numberFormat .= '0';
 						}
 						if($_SESSION[$current_user->user_name . '_PREFERENCES']['global']['dec_sep'] == ',') $numberFormat .= '/i';
 						// add a custom renderer for the currency
 						return ', renderer: function(value, metadata, record){if(value != null){if(record.data.' . $fieldID .'_curid == undefined) record.data.' . $fieldID .'_curid = -99; return Ext.util.Format.number(value, kreport_currencies[record.data.' . $fieldID .'_curid] + \'' . $numberFormat . '\'); }else return value;}, css: \'text-align:right;\'';
 						break;
 					case 'percentage': 
 						return ', renderer: function(value, metadata, record){if(value != null) return value + \'%\'; else return value;}, css: \'text-align:center;\'';
 						break;
 					case 'double':
 					case 'int':
 					case 'float':
 						return ', css: \'text-align:center;\'';
 						break;
 					case 'date': 
 						return ', xtype: \'datecolumn\', format: \'' . $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['datef'] . '\', css: \'text-align:center;\'';
 						break;
 					case 'datetime': 
 						// for date tiem we need a custom renderer
 						return ', renderer: function(value){if(value != null) return Ext.util.Format.date(value.split(\' \')[0], \'' . $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['datef'] . '\') + \' \' + value.split(\' \')[1]; else return value;}';
 						break;
 					case 'bool':
 						return ', xtype: \'booleancolumn\'';
 						break;
 					default: 
 						return '';
 						break;
 				}
 		
 		// if we end up here we return an empty string
 		return '';
 	}
 	
 	function getRendererFunction($fieldType, $fieldID = '')
 	{
 		global $current_user;
 		
 		// check if we have a custom SQL function -- then reset the value .. we do  not know how to format
 		$listFieldArray = $this->kQueryArray->queryArray['root']['kQuery']->get_listfieldentry_by_fieldid($fieldID);
 		
 		// manage switching of Fieldtypes
 		if($listFieldArray['sqlfunction'] == 'COUNT') $fieldType = 'int';
 		if($listFieldArray['customsqlfunction'] != '') $fieldType = '';
 		if($listFieldArray['valuetype'] != '-' && $listFieldArray['valuetype'] != '') $fieldType = 'percentage';
 		 		
 		// process thee fieldtypes
 		switch($fieldType)
 				{
 					case 'currency':
 						$numberFormat = '0';
 						// check if we have a 1000 separator
 						if($_SESSION[$current_user->user_name . '_PREFERENCES']['global']['num_grp_sep'] != '')
 							$numberFormat .=  $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['num_grp_sep'] . '000';
 						//check if we hav significant digits
 						if( $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['default_currency_significant_digits'] > 0)
 						{
 						 $numberFormat .=  $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['dec_sep'];
 						 for($i=0; $i < $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['default_currency_significant_digits']; $i++) $numberFormat .= '0';
 						}
 						if($_SESSION[$current_user->user_name . '_PREFERENCES']['global']['dec_sep'] == ',') $numberFormat .= '/i';
 						// add a custom renderer for the currency
 						return 'R' . $fieldID . 'Renderer = function(value){if(value != null) return Ext.util.Format.number(value, kreport_currencies[-99] + \'' . $numberFormat . '\'); else return value;};';
 						// return ', renderer: function(value, metadata, record){alert(value + \'/\' + record.data.' . $fieldID .'_curid);}, css: \'text-align:right;\'';
 						// return '';
 						break;
  					case 'percentage': 
 						return ', renderer: function(value, metadata, record){if(value != null) return value + \'%\'; else return value;}, css: \'text-align:center;\'';
 						break;
 					case 'date': 
 						return 'R' . $fieldID . 'Renderer = function(value){if(value != null) return Ext.util.Format.date(value, \'' . $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['datef'] . '\'); else return value;};';
 						break;
 					case 'double':
 					case 'int':
 					case 'float':
 						return ', css: \'text-align:center;\'';
 						break;
 					case 'datetime': 
 						// for date tiem we need a custom renderer
 						return 'R' . $fieldID . 'Renderer = function(value){if(value != null) return Ext.util.Format.date(value.split(\' \')[0], \'' . $_SESSION[$current_user->user_name . '_PREFERENCES']['global']['datef'] . '\') + \' \' + value.split(\' \')[1]; else return value;};';
 						break;
 					default: 
 						return 'R' . $fieldID . 'Renderer = function(value){return value;};';
 						break;
 				}
 		
 		// if we end up here we return an empty string
 		return 'R' . $fieldID . 'Renderer = function(value){return value;};';
 	}
 	
	
	function takeSnapshot(){
		  global $db;
		  
		  $snapshotID = create_guid();
		  
		  // go get the results
		  $results = $this->getSelectionResults(array('toPDF' => true, 'noFormat' => true));
		  
		  $i = 0;
		  foreach($results as $resultsrow)
		  {
			  $query = 'INSERT INTO kreportsnapshotsdata SET record_id=\'' . $i . '\', snapshot_id = \'' . $snapshotID . '\', data=\'' . json_encode_kinamu($resultsrow) . '\'' ;
	    	  $db->query($query);
	    	  $i++;
		  }
		  
		  // create the snapshot record
		  $query = 'INSERT INTO kreportsnapshots SET id=\'' . $snapshotID . '\', snapshotdate =\'' . gmdate('Y-m-d H:i:s') . '\', report_id=\'' . $this->id . '\'';
		  $db->query($query);
	}
	
	function createCSV(){
	  global $current_user;	
	
	  $header = '';
 	  $rows = '';
	  
	  // see if we need to filter dynamically	  
	  $results = $this->getSelectionResults(array('toPDF' => true), isset($_REQUEST['snapshotid']) ? $_REQUEST['snapshotid'] : '0');
	
	  $arrayList =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));
	  
	  $fieldArray = '';
	  $fieldIdArray = array();
	  foreach($arrayList as $thisList){
	 	    if($thisList['display'] == 'yes')
	  	    {
				$fieldArray[] = array('label' => utf8_decode($thisList['name']), 'width' => (isset($thisList['width']) && $thisList['width'] != '' && $thisList['width'] != '0') ? $thisList['width'] : '100', 'display' => $thisList['display']);
				$fieldIdArray[] = $thisList['fieldid'];
	  	    }
	  }
	  
	  if(count($results > 0))
	  {
		  foreach($results as $record)
		  {
		  	$getHeader = ($header == '') ? true : false;
		  	foreach($record as $key => $value)
		  	{
		  		
		  		//if($key != 'sugarRecordId')
			    $arrayIndex = array_search($key, $fieldIdArray);
			  	if(array_search($key, $fieldIdArray) !== false)		  			
		  		{
			  		if($getHeader) 
			  		{
			  			foreach($arrayList as $fieldId => $fieldArray)
			  				if($fieldArray['fieldid'] == $key) $header .= iconv("UTF-8", $current_user->getPreference('default_export_charset'), $fieldArray['name']) . $current_user->getPreference('export_delimiter');
			  		}
			  		
			  		$rows .= '"' . iconv("UTF-8", $current_user->getPreference('default_export_charset'), html_entity_decode($value, ENT_QUOTES)) . '"' . $current_user->getPreference(('export_delimiter')) ;
		  		}
		  	}
		  	if($getHeader) $header .= "\n";
		  	$rows .= "\n";
		  }
		  
	      $filename ="kinamureporter.csv";
	      header('Content-type: application/ms-excel');
	      header('Content-Disposition: attachment; filename='.$filename);
	      
		}
		
		return $header . $rows;
	}
	
	function createTargeList($listname)
	{
	  global $current_user;
	  
	  $results = $this->getSelectionResults();
	
	  if(count($results > 0))
	  {
	  	require_once('modules/ProspectLists/ProspectList.php');
	  	$newProspectList = new ProspectList();
	  	
	  	$newProspectList->name = $listname;
	  	$newProspectList->list_type = 'default';
	  	$newProspectList->assigned_user_id = $current_user->id;
	  	$newProspectList->save();
	  	
	  	// fill with results: 
	  	$newProspectList->load_relationships();
	  	
	  	$linkedFields = $newProspectList->get_linked_fields();
	  	
	  	foreach($linkedFields as $linkedField => $linkedFieldData)
	  	{
	  		if($newProspectList->$linkedField->_relationship->rhs_module == $this->report_module)
	  		{
	  			// success
	  			foreach($results as $thisRecord)
	  			{
	  				 $newProspectList->$linkedField->add($thisRecord['sugarRecordId']);
	  			}
	  		}
	  	}
	  }
	}
	
	function createKML(){
	    global $app_list_strings, $mod_strings;

        $serverName = dirname($_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);

        // get the report results
        $results = $this->getSelectionResults();
	
	  	if(count($results > 0))
	  	{
	  		  	foreach($results as $thisRecord)
	  			{
	  				 $elementRecord[] = $thisRecord['sugarRecordId'];
	  			}
	  	}
        
	  	switch($this->report_module){
	  		case 'Accounts':
	  			$accounts = $elementRecord;
	  			break;
	  		case 'Contacts':
	  			$contacts = $elementRecord;
	  			break;
	  		case 'Leads':
	  			$leads = $elementRecord;
	  			break;
	  	}

        $accounts = (is_array($accounts) ? $accounts : array($accounts));
        $contacts = (is_array($contacts) ? $contacts : array($contacts));
        $leads = (is_array($leads) ? $leads : array($leads));

        if(count($accounts) > 0 && !empty($serverName)) {

            // creating a KML file (see also google reference: http://code.google.com/intl/de-DE/apis/kml/documentation/kmlreference.html)
            $xml = new XMLWriter();
            $xml->openMemory();
            $xml->setIndent(true);
            $xml->setIndentString('   ');
            $xml->startDocument('1.0', $dst_encoding);

            $xml->startElement("kml"); // kml (root node)
            $xml->writeAttribute("xmlns", "http://www.opengis.net/kml/2.2");
            $xml->writeAttribute("xmlns:gx", "http://www.opengis.net/kml/2.2");

            $xml->startElement("Document"); // kml/Document

            // the different placemark styles here
            $xml->startElement("Style"); // kml/Document/Style
            $xml->writeAttribute("id", "accountPlacemark");
            $xml->startElement("IconStyle"); // kml/Document/Style/IconStyle
            $xml->startElement("Icon"); // kml/Document/Style/IconStyle/Icon
            //$xml->writeElement("href", "http://maps.google.com/mapfiles/kml/paddle/red-stars.png");
            $xml->writeElement("href", "http://" . $serverName . "/themes/default/images/Accounts.gif");
            $xml->endElement(); // kml/Document/Style/IconStyle/Icon
            $xml->endElement(); // kml/Document/Style/IconStyle
            $xml->endElement(); // kml/Document/Style
            $xml->startElement("Style"); // kml/Document/Style
            $xml->writeAttribute("id", "contactPlacemark");
            $xml->startElement("IconStyle"); // kml/Document/Style/IconStyle
            $xml->startElement("Icon"); // kml/Document/Style/IconStyle/Icon
            //$xml->writeElement("href", "http://maps.google.com/mapfiles/kml/paddle/red-stars.png");
            $xml->writeElement("href", "http://" . $serverName . "/themes/default/images/Contacts.gif");
            $xml->endElement(); // kml/Document/Style/IconStyle/Icon
            $xml->endElement(); // kml/Document/Style/IconStyle
            $xml->endElement(); // kml/Document/Style
            $xml->startElement("Style"); // kml/Document/Style
            $xml->writeAttribute("id", "leadPlacemark");
            $xml->startElement("IconStyle"); // kml/Document/Style/IconStyle
            $xml->startElement("Icon"); // kml/Document/Style/IconStyle/Icon
            //$xml->writeElement("href", "http://maps.google.com/mapfiles/kml/paddle/red-stars.png");
            $xml->writeElement("href", "http://" . $serverName . "/themes/default/images/Leads.gif");
            $xml->endElement(); // kml/Document/Style/IconStyle/Icon
            $xml->endElement(); // kml/Document/Style/IconStyle
            $xml->endElement(); // kml/Document/Style

            // end placemark style definition

            // mapping a placemark definition to a corresponding placemark situation
            $xml->startElement("StyleMap"); // kml/Document/StyleMap
            $xml->writeAttribute("id", "accountStyleMap");
            $xml->startElement("Pair"); // kml/Document/StyleMap/Pair
            $xml->writeElement("key", "normal");
            $xml->writeElement("styleUrl", "#accountPlacemark");
            $xml->endElement(); // kml/Document/StyleMap/Pair
            $xml->startElement("Pair"); // kml/Document/StyleMap/Pair
            $xml->writeElement("key", "highlight");
            $xml->writeElement("styleUrl", "#accountPlacemark");
            $xml->endElement(); // kml/Document/StyleMap/Pair
            $xml->endElement(); // kml/Document/StyleMap
            $xml->startElement("StyleMap"); // kml/Document/StyleMap
            $xml->writeAttribute("id", "contactStyleMap");
            $xml->startElement("Pair"); // kml/Document/StyleMap/Pair
            $xml->writeElement("key", "normal");
            $xml->writeElement("styleUrl", "#contactPlacemark");
            $xml->endElement(); // kml/Document/StyleMap/Pair
            $xml->startElement("Pair"); // kml/Document/StyleMap/Pair
            $xml->writeElement("key", "highlight");
            $xml->writeElement("styleUrl", "#contactPlacemark");
            $xml->endElement(); // kml/Document/StyleMap/Pair
            $xml->endElement(); // kml/Document/StyleMap
            $xml->startElement("StyleMap"); // kml/Document/StyleMap
            $xml->writeAttribute("id", "leadStyleMap");
            $xml->startElement("Pair"); // kml/Document/StyleMap/Pair
            $xml->writeElement("key", "normal");
            $xml->writeElement("styleUrl", "#leadPlacemark");
            $xml->endElement(); // kml/Document/StyleMap/Pair
            $xml->startElement("Pair"); // kml/Document/StyleMap/Pair
            $xml->writeElement("key", "highlight");
            $xml->writeElement("styleUrl", "#leadPlacemark");
            $xml->endElement(); // kml/Document/StyleMap/Pair
            $xml->endElement(); // kml/Document/StyleMap
            // end mapping placemark style

            require_once('modules/Accounts/Account.php');
            $account = new Account();
            foreach($accounts as $record) {
                if($account->retrieve($record)) {

                    if(empty($account->k_geo_longitude) || empty($account->k_geo_latitude)) {
                        // if the geo data are missing, we save the record to get them by the save hooks
                        /*
                    	if($account->save()) {
                            $account->retrieve($record);
                        }
                        */
                    }

                    if(!empty($account->k_geo_longitude) && !empty($account->k_geo_latitude)) {
                        $xml->startElement("Placemark"); // kml/Document/Placemark
                        $xml->writeElement("name", $account->name);
                        $xml->writeElement("styleUrl", "#accountStyleMap");
                        $xml->startElement("description"); // kml/Document/Placemark/Point/description
                        $xml->writeCData("<a href=\"http://" . $serverName . "/index.php?module=Accounts&action=DetailView&record=" . $account->id . "\">" . $mod_strings['LBL_DISPLAY_IN_SUGARCRM'] . "</a><br/>");
                        $xml->endElement(); // kml/Document/Placemark/Point/description
                        $address = $account->name . "<br/>" .
                                   $account->k_name2 . "<br/>" .
                                   $account->billing_address_street . " " . $account->billing_address_hsnm . "<br/>" .
                                   (!empty($account->billing_address_street_2) ? ($account->billing_address_street_2 . "<br/>") : "") .
                                   (!empty($account->billing_address_street_3) ? ($account->billing_address_street_3 . "<br/>") : "") .
                                   (!empty($account->billing_address_street_4) ? ($account->billing_address_street_4 . "<br/>") : "") .
                                   $account->billing_address_postalcode . " " . $account->billing_address_city . "<br/>" .
                                   $app_list_strings['sap_country_list'][$account->billing_address_country];
                        $xml->writeElement("address", $address);
                        $xml->startElement("Point"); // kml/Placemark/Point
                        $xml->writeElement("coordinates", $account->k_geo_longitude ."," . $account->k_geo_latitude);
                        $xml->endElement(); // kml/Document/Placemark/Point
                        $xml->endElement(); // kml/Document/Placemark
                    }
                }
            }

            require_once('modules/Contacts/Contact.php');
            $contact = new Contact();
            foreach($contacts as $record) {
                $contact->retrieve($record);
                if($contact->retrieve($record)) {

                    if(empty($contact->k_geo_longitude) || empty($contact->k_geo_latitude)) {
                        // if the geo data are missing, we save the record to get them by the save hooks
                        /*
                    	if($contact->save()) {
                            $contact->retrieve($record);
                        }
                        */
                    }

                    if(!empty($contact->k_geo_longitude) && !empty($contact->k_geo_latitude)) {
                        $xml->startElement("Placemark"); // kml/Document/Placemark
                        $xml->writeElement("name", $contact->full_name);
                        $xml->writeElement("styleUrl", "#contactStyleMap");
                        $xml->startElement("description"); // kml/Document/Placemark/Point/description
                        $xml->writeCData("<a href=\"http://" . $serverName . "/index.php?module=Contacts&action=DetailView&record=" . $contact->id . "\">" . $mod_strings['LBL_DISPLAY_IN_SUGARCRM'] . "</a><br/>");
                        $xml->endElement(); // kml/Document/Placemark/Point/description
                        $address = $contact->full_name . "<br/>" .
                                   $contact->primary_address_street . " " . $contact->primary_address_hsnm . "<br/>" .
                                   (!empty($contact->primary_address_street_2) ? ($contact->primary_address_street_2 . "<br/>") : "") .
                                   (!empty($contact->primary_address_street_3) ? ($contact->primary_address_street_3 . "<br/>") : "") .
                                   (!empty($contact->primary_address_street_4) ? ($contact->primary_address_street_4 . "<br/>") : "") .
                                   $contact->primary_address_postalcode . " " . $contact->primary_address_city . "<br/>" .
                                   $app_list_strings['sap_country_list'][$contact->primary_address_country];
                        $xml->writeElement("address", $address);
                        $xml->startElement("Point"); // kml/Placemark/Point
                        $xml->writeElement("coordinates", $contact->k_geo_longitude ."," . $contact->k_geo_latitude);
                        $xml->endElement(); // kml/Document/Placemark/Point
                        $xml->endElement(); // kml/Document/Placemark
                    }
                }
            }

            require_once('modules/Leads/Lead.php');
            $lead = new Lead();
            foreach($leads as $record) {
                if($lead->retrieve($record)) {

                    if(empty($lead->k_geo_longitude) || empty($lead->k_geo_latitude)) {
                        // if the geo data are missing, we save the record to get them by the save hooks
                        /*
                    	if($lead->save()) {
                            $lead->retrieve($record);
                        }
                        */
                    }

                    if(!empty($lead->k_geo_longitude) && !empty($lead->k_geo_latitude)) {
                        $xml->startElement("Placemark"); // kml/Document/Placemark
                        $xml->writeElement("name", $lead->name);
                        $xml->writeElement("styleUrl", "#leadStyleMap");
                        $xml->startElement("description"); // kml/Document/Placemark/Point/description
                        $xml->writeCData("<a href=\"http://" . $serverName . "/index.php?module=Leads&action=DetailView&record=" . $lead->id . "\">" . $mod_strings['LBL_DISPLAY_IN_SUGARCRM'] . "</a><br/>");
                        $xml->endElement(); // kml/Document/Placemark/Point/description
                        $address = $lead->full_name . "<br/>" .
                                   $lead->primary_address_street . "<br/>" .
                                   $lead->primary_address_postalcode . " " . $lead->primary_address_city . "<br/>" .
                                   $app_list_strings['sap_country_list'][$lead->primary_address_country];
                        $xml->writeElement("address", $address);
                        $xml->startElement("Point"); // kml/Placemark/Point
                        $xml->writeElement("coordinates", $lead->k_geo_longitude ."," . $lead->k_geo_latitude);
                        $xml->endElement(); // kml/Document/Placemark/Point
                        $xml->endElement(); // kml/Document/Placemark
                    }
                }
            }

            $xml->endElement(); // kml/Document

            $xml->endElement(); // kml (root node)

            header("Content-Disposition: attachment; filename=\"" . create_guid() . ".kml\";\n\n");
            header("Content-type: application/vnd.google-earth.kml+xml");
            return $xml->outputMemory();
        }
	}
	
	/*
	 * Parameters:  
	 * 	- grouping: set to off to not have grouping
	 *  - start: start from record
	 *  - limit: limit to n records from start
	 *  - addSQLFunction: array with fields and custom function that should be used to 
	 *    add/override the basic sql functions
	 *  - noFormat: no formatting done
	 *  - toPDF: formatting is doen but no links are built (not useful in PDF)
	 *  - dontFormat: array with fieldids that should not be formatted when returing 
	 *    e.g. nbeeded for geocoding
	 */
	function getSelectionResults($parameters, $snapshotid = '0', $getcount = false, $additionalFilter = '', $additionalGroupBy = array()){
		
		// parameter overrid listtype used for Charts
		global $db;
		
		// get the sql array or retrieve from snapshot if set
		if($snapshotid == '0' || $snapshotid == 'current')
		{
			if(isset($parameters['grouping']) &&  $parameters['grouping'] == 'off')
			{
				$query = $this->get_report_main_sql_query(false, $additionalFilter, $additionalGroupBy);
				//$query = $sqlArray['select'] . ' ' . $sqlArray['from'] . ' ' . $sqlArray['where'] . ' ' . $sqlArray['having'] . ' ' . $sqlArray['orderby'];
			} else {
				$query = $this->get_report_main_sql_query(true, $additionalFilter, $additionalGroupBy);
				//$query = $sqlArray['select'] . ' ' . $sqlArray['from'] . ' ' . $sqlArray['where'] . ' ' . $sqlArray['groupby'] . ' ' . $sqlArray['having'] . ' ' . $sqlArray['orderby'];
			}
			
			// cehck if we only need the count than we shortcut here
			if($getcount) 
			{
				// limit the query if a limit is set ... 
				switch($this->selectionlimit)
					{
						case 'top10':
							return $this->db->getRowCount($db->limitquery($query, 0, 10));
							break;
						case 'top20':
							return $this->db->getRowCount($queryResults = $db->limitquery($query, 0, 20));
							break;						
						case 'top50':
							return $this->db->getRowCount($queryResults = $db->limitquery($query, 0, 50));
							break;			
						case 'top250':
							return $this->db->getRowCount($queryResults = $db->limitquery($query, 0, 250));
							break;			
						default: 
							if($this->kQueryArray->countSelectString != '')
							{
								$queryResults = $db->fetchByAssoc($db->query($this->kQueryArray->countSelectString));
								return $queryResults['totalCount'];
							}
							else
								return $this->db->getRowCount($queryResults = $db->query($query));
							break; 
					}
			    //return $this->db->getRowCount($db->query($query));
			}
			
			// process seleciton limit and run the main query
			switch($this->selectionlimit)
			{
				case 'top10':
					$topLimit = 10;
					if(isset($parameters['limit']) && $parameters['limit'] != '' && isset($parameters['start']))
						if($parameters['limit'] < $topLimit) $topLimit = $parameters['limit'];
					$queryResults = $db->limitquery($query, $parameters['start'], $topLimit);
					break;
				case 'top20':
					$topLimit = 20;
					if(isset($parameters['limit']) && $parameters['limit'] != '' && isset($parameters['start']))
						if($parameters['limit'] < $topLimit) $topLimit = $parameters['limit'];
					$queryResults = $db->limitquery($query, $parameters['start'], $topLimit);
					break;						
				case 'top50':
					$topLimit = 50;
					if(isset($parameters['limit']) && $parameters['limit'] != '' && isset($parameters['start']))
						if($parameters['limit'] < $topLimit) $topLimit = $parameters['limit'];
					$queryResults = $db->limitquery($query, $parameters['start'], $topLimit);
					break;			
				case 'top250':
					$topLimit = 250;
					if(isset($parameters['limit']) && $parameters['limit'] != '' && isset($parameters['start']))
						if($parameters['limit'] < $topLimit) $topLimit = $parameters['limit'];
					$queryResults = $db->limitquery($query, $parameters['start'], $topLimit);
					break;			
				default: 
					if(isset($parameters['limit']) && $parameters['limit'] != '' && isset($parameters['start']))
					{
						$queryResults = $this->db->limitquery($query, $parameters['start'], $parameters['limit']);
					}		
					else
					{
						$queryResults = $this->db->query($query);
					}				
					//$queryResults = $this->db->limitquery($query, $parameters['start'], $parameters['limit']);
					break; 
			}

			// 2011-02-03 added for percentage calculation of total
			//see if we need to query the totals
			if($this->kQueryArray->totalSelectString != '')
			{
				$this->totalResult = $this->db->fetchByAssoc($this->db->query($this->kQueryArray->totalSelectString));
			}
			
			// get the restul rows and process them			
			while($queryRow = $this->db->fetchByAssoc($queryResults))
			{   
				// just the basic Row
				$formattedRow = $queryRow;
				
				// calculate the percentage or dealtavalues
				if($this->totalResult != '')
					$formattedRow = $this->calculateValueOfTotal($formattedRow); 
				
				// format the Fields
				if(!isset($parameters['noFormat']) || ( isset($parameters['noFormat']) && !$parameters['noFormat']))
				    $formattedRow = $this->formatFields($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array());
				else
					$formattedRow = $this->formatEnums($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array());
				
				//build the links 
				if(!isset($parameters['toPDF']) || (isset($parameters['toPDF']) && !$parameters['toPDF']))
					$formattedRow = $this->buildLinks($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array());    
				
				// return the formatted row
				$retArray[] = $formattedRow;
			}
		}
		else
		{
				$query = 'SELECT data FROM kreportsnapshotsdata WHERE snapshot_id = \'' . $snapshotid . '\'';
				
				// check if we only need the count than we shortcut here
				if($getcount) 
			   	 	return $this->db->getRowCount($db->query($query));
				
			   	// limit the query if requested
				if(isset($parameters['start']) && $parameters['start'] != '' )
				{
					$query .= ' AND record_id >= ' . $parameters['start'];
				}
	
				if(isset($parameters['limit']) && $parameters['limit'] != '')
				{
					$query .= ' AND record_id < ' . ($parameters['start'] + $parameters['limit']);
				}			
				
				$query .= ' ORDER BY record_id ASC';
				
				$snapshotResults = $db->query($query);
	
				// still need to process this to have all teh setting for theformat
				$sqlArray = $this->get_report_main_sql_query('', true, '');
				
				while($snapshotRecordData = $db->fetchByAssoc($snapshotResults))
				{
					// just the basic Row
					$formattedRow = json_decode_kinamu(html_entity_decode_utf8($snapshotRecordData['data']));
					
					// format the Fields
					if(!isset($parameters['noFormat']) || ( isset($parameters['noFormat']) && !$parameters['noFormat']))
					    $formattedRow = $this->formatFields($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array());
					
					//build the links unless we can conserve the ids with the snapshot this will not work ... 
					//if(!isset($parameters['toPDF']) || (isset($parameters['toPDF']) && !$parameters['toPDF']))
					//	$formattedRow = $this->buildLinks($formattedRow, isset($parameters['dontFormat']) ? $parameters['dontFormat'] : array());    
					
					// return the formatted row
					$retArray[] = $formattedRow;
				}
		}
		return $retArray;
		
	}
	
	// include('modules/KReports/KReportGeoCodeFunctions.php');
	
	function getSnapshots(){
		$query = 'SELECT id, snapshotdate FROM kreportsnapshots WHERE report_id = \'' . $this->id	. '\' ORDER BY snapshotdate DESC';
		
		$snapShotsResults = $this->db->query($query);
		
		$retArray[] = array('snapshot' => '0', 'description' => 'current');	
		
		while($thisSnapshot = $this->db->fetchByAssoc($snapShotsResults))
		{
			$retArray[] = array('snapshot' => $thisSnapshot['id'], 'description' => $thisSnapshot['snapshotdate']);	
		}
		return $retArray;
	}
	
	function getListFields(){
		
		// anlyze all the pathes we have
		//$this->build_path();
				
		// build the from clause and all join segments
		//$this->build_joinsegments();
	
		$arrayList =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));
		
		$retArray[] = array('fieldid' => '-', 'fieldname' => '-');	
		
		
		if(is_array($arrayList))
		{
			foreach($arrayList as $thisList)
			{
				//$pathName = $this->getPathNameFromPath($thisList['path']);
				//$fieldName = explode(':', $this->getFieldNameFromPath($thisList['path']));
				//if($this->joinSegments[$pathName]['object']->field_name_map[$fieldname[1]]->type == 'currency')
				     $retArray[] = array('fieldid' => $thisList['fieldid'], 'fieldname' => $thisList['name']);	
			}
		}	
		else
		{
			$retArray = '';
		}
		
		return $retArray;
	}
	
	function getListFieldsArray(){
		$fieldArray =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));
		
		foreach($fieldArray as $fieldCount => $fieldData)
			$returnArray[$fieldData['fieldid']] = $fieldData;
			
		return $returnArray;
	}
	/*
	function getGroupLevelId($groupLevel){
		$arrayList =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));
		
		if(is_array($arrayList))
		{
			foreach($arrayList as $thisList)
			{
				//manage the damned primary clause
				if($thisList['groupby'] == 'primary') $thisList['groupby'] = '1';
				
				if($thisList['groupby'] == $groupLevel)
				    return 	$thisList['fieldid'];
			}
	
		}	
	
		// not an array or not found
		return  '';
		
	}
	
	function getMaxGroupLevel(){
		$arrayList =  json_decode_kinamu( html_entity_decode_utf8($this->listfields));
		
		$maxGroupLevel = '';
		
		if(is_array($arrayList))
		{
			foreach($arrayList as $thisList)
			{
				//manage the damned primary clause
				if($thisList['groupby'] == 'primary') $thisList['groupby'] = '1';
				
				if($thisList['groupby'] != 'no' && $thisList['groupby'] != 'yes' && $thisList['groupby'] > $maxGroupLevel )
						$maxGroupLevel = $thisList['groupby'];
			}
		}	
	
		// not an array or not found
		return  $maxGroupLevel;
		
	}
	*/
	// for the GeoCoding
	function massGeoCode()
	{
		global $app_list_strings, $mod_strings, $beanList, $beanFiles;

		// flag to memorize if we hjave different beans for longitude and latiitude
		// not sure when this would happen buit it could happen
		$longlatDiff = false;
		
		// get the map details for the report
		$mapDetails = json_decode(html_entity_decode($this->mapoptions));
		
        $serverName = dirname($_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);

        // get the report results
        $results = $this->getSelectionResults();
        
        // get the ids for longitude and latitude
        $long_bean_id = $this->kQueryArray->queryArray['root']['kQuery']->joinSegments[$this->kQueryArray->fieldNameMap[$mapDetails->longitude]['path']]['alias'];
        $lat_bean_id = $this->kQueryArray->queryArray['root']['kQuery']->joinSegments[$this->kQueryArray->fieldNameMap[$mapDetails->latitude]['path']]['alias'];

        // get the beans
        $long_bean = $this->kQueryArray->queryArray['root']['kQuery']->joinSegments[$this->kQueryArray->fieldNameMap[$mapDetails->longitude]['path']]['object'];
        if($long_bean_id != $lat_bean_id)
        {
        	$longlatDiff = true;
        	$lat_bean = $this->kQueryArray->queryArray['root']['kQuery']->joinSegments[$this->kQueryArray->fieldNameMap[$mapDetails->latitude]['path']]['object'];
        }
        
		if(count($results) > 0)
		{
			

	  		$mapService = new kReportBingMaps();
	  		require_once('modules/Accounts/Account.php');
	  		
			foreach($results as $thisResult)
			{
				if(($thisResult[$mapDetails->latitude] == '' || $thisResult[$mapDetails->latitude] == null || $thisResult[$mapDetails->latitude] == '0,00') 
				    || 
				   ($thisResult[$mapDetails->longitude] == '' || $thisResult[$mapDetails->longitude] == null || $thisResult[$mapDetails->longitude] == '0,00'))
					{
					
						//$query = $thisResult[$mapDetails->geocodeStreet] . ', ' .  $thisResult[$mapDetails->geocodePostalcode] . ' ' .  $thisResult[$mapDetails->geocodeCity] . ' ' .  $thisResult[$mapDetails->geocodeCountry];
						$addressArray = array('AddressLine' => $thisResult[$mapDetails->geocodeStreet], 'PostalCode' => $thisResult[$mapDetails->geocodePostalcode], 'Locality' => $thisResult[$mapDetails->geocodeCity], 'CountryRegion' => $thisResult[$mapDetails->geocodeCountry]);
						$geoCodeResult = $mapService->geocode($addressArray);
						
						// update object 
						$long_bean->retrieve($thisResult[$long_bean_id . 'id']);
						$long_bean->{$this->kQueryArray->fieldNameMap[$mapDetails->longitude][fieldname]} = $geoCodeResult['longitude'];
						
						//2010-12-6 format numbers after mass geocode
						$long_bean->format_field($long_bean->field_defs[$this->kQueryArray->fieldNameMap[$mapDetails->longitude][fieldname]]);
						
						// see if we have different beans
						// should be the exceptionbut we never know
						if(!$longlatDiff)
						{
							$long_bean->{$this->kQueryArray->fieldNameMap[$mapDetails->latitude][fieldname]} = $geoCodeResult['latitude'];
							
							//2010-12-6 format numbers after mass geocode
							$long_bean->format_field($long_bean->field_defs[$this->kQueryArray->fieldNameMap[$mapDetails->latitude][fieldname]]);
						}
						else 
						{
							$lat_bean->retrieve($thisResult[$lat_bean_id . 'id']);
							$lat_bean->{$this->kQueryArray->fieldNameMap[$mapDetails->latitude][fieldname]} = $geoCodeResult['latitude'];
							
							//2010-12-6 format numbers after mass geocode
							$lat_bean->format_field($lat_bean->field_defs[$this->kQueryArray->fieldNameMap[$mapDetails->latitude][fieldname]]);
							
							$lat_bean->save();
						}
						
						$long_bean->save();
					}
			}
	  	}
		
	}

	
	
	function getGeoCodes(){
	    global $app_list_strings, $mod_strings;

	    $mapDetails = json_decode(html_entity_decode($this->mapoptions));
	    // $jsonerror = json_last_error();
	    
	    $returnArray = array();
	    
        // get the report results
        $results = $this->getSelectionResults(array('dontFormat' => array($mapDetails->longitude, $mapDetails->latitude)));
	
        $categoryArray = array();
        $categoryCount = 1;
        
        $mapBounds = array(
         	'topLeft' => array(
        			'x' => 0,
                    'y' => 0
        		),
            'bottomRight' => array(
        			'x' => 0,
                    'y' => 0
        		)
         );
        
	  	if(count($results > 0))
	  	{
	  		  	foreach($results as $thisRecord)
	  			{
	  				//see if we have a category
	  				if(isset($mapDetails->type) && $mapDetails->type != '' && isset($thisRecord[$mapDetails->type]) && $thisRecord[$mapDetails->type] != '')
	  				{
	  					if(!isset($categoryArray[$thisRecord[$mapDetails->type]]))
	  					{
	  						$categoryArray[$thisRecord[$mapDetails->type]] = $categoryCount;
	  						$categoryCount++;
	  					}
	  					$returnArray['data'][] = array(
                        	'id' => $thisRecord['sugarRecordId'],
                        	'geox' => $thisRecord[$mapDetails->longitude],
                        	'geoy' => $thisRecord[$mapDetails->latitude],
	  					    'category_id' => (string)$categoryArray[$thisRecord[$mapDetails->type]],
	  				 		'category' => $thisRecord[$mapDetails->type], 
                        	'line1' => /*$thisRecord[$mapDetails->longitude] . '/' . $thisRecord[$mapDetails->latitude] . '<br>' .*/
	  								   $thisRecord[$mapDetails->line1] . '<br>' . 
	  				 				   $thisRecord[$mapDetails->line2] . '<br>' .
	  				 				   $thisRecord[$mapDetails->line3] . '<br>' .
	  				 				   $thisRecord[$mapDetails->line4] . '<br>');
	  				}
	  				else 
	  				{
	  				 // $elementRecord[] = $thisRecord['sugarRecordId'];
	  				 $returnArray['data'][] = array(
                        	'id' => $thisRecord['sugarRecordId'],
                        	'geox' => $thisRecord[$mapDetails->longitude],
                        	'geoy' => $thisRecord[$mapDetails->latitude],
	  				 		'category' => '', 
                        	'line1' => /*$thisRecord[$mapDetails->longitude] . '/' . $thisRecord[$mapDetails->latitude] . '<br>' .*/
	  				 				   $thisRecord[$mapDetails->line1] . '<br>' . 
	  				 				   $thisRecord[$mapDetails->line2] . '<br>' .
	  				 				   $thisRecord[$mapDetails->line3] . '<br>' .
	  				 				   $thisRecord[$mapDetails->line4] . '<br>');
	  				}
	  				
	  				// set bounds
	  				if(floatval($thisRecord[$mapDetails->longitude]) != 0 && floatval($thisRecord[$mapDetails->latitude]) != 0)
	  				{
		  				if($mapBounds['topLeft']['x'] == 0 || floatval($thisRecord[$mapDetails->longitude]) < floatval($mapBounds['topLeft']['x']))
		  					$mapBounds['topLeft']['x'] = floatval ($thisRecord[$mapDetails->longitude]);
		  					
		  				if($mapBounds['topLeft']['y'] == 0 || floatval($thisRecord[$mapDetails->latitude]) > floatval($mapBounds['topLeft']['y']))
		  					$mapBounds['topLeft']['y'] = floatval ($thisRecord[$mapDetails->latitude]);	
		  				
		  				if($mapBounds['bottomRight']['x'] == 0 || floatval($thisRecord[$mapDetails->longitude]) > floatval($mapBounds['bottomRight']['x']))
		  					$mapBounds['bottomRight']['x'] = floatval($thisRecord[$mapDetails->longitude]);
		  					
		  				if($mapBounds['bottomRight']['y'] == 0 || floatval($thisRecord[$mapDetails->latitude]) < floatval($mapBounds['bottomRight']['y']))
		  					$mapBounds['bottomRight']['y'] = floatval($thisRecord[$mapDetails->latitude]);	
	  				}
	  			}
	  			
	  			// add two record for the bounds
	  			$returnArray['data'][] = array(
                        	'id' => 'topLeft',
                        	'geox' => $mapBounds['topLeft']['x'],
                        	'geoy' => $mapBounds['topLeft']['y'],
	  				 		'category' => 'TL', 
                        	'line1' => 'topLeft' . $mapBounds['topLeft']['x'] . '/' . $mapBounds['topLeft']['y']);
	  			
	  			$returnArray['data'][] = array(
                        	'id' => 'bottomRight',
                        	'geox' => $mapBounds['bottomRight']['x'],
                        	'geoy' => $mapBounds['bottomRight']['y'],
	  				 		'category' => 'BR', 
                        	'line1' => 'bottomRight' . $mapBounds['bottomRight']['x'] . '/' . $mapBounds['bottomRight']['y']);
	  	}
       
        return json_encode($returnArray);

	}
}