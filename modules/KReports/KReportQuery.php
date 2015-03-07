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

global $dictionary;
if($dictionary['KReport']['edition'] == 'premium')
{
	require_once('modules/KReports/KFC/FusionCharts_Gen.php');
	require_once('modules/KReports/KFC/KFC_Colors.php');
}
require_once('modules/KReports/utils.php');
require_once('modules/ACL/ACLController.php');

// class for the query Array if we have multiple query we join
class KReportQueryArray {
	var $thisKReport;
	
	var $root_module;
	var $union_modules;
	var $listArray;
	var $whereArray;
	var $whereAddtionalFilter;
	var $whereGroupsArray;
	var $groupsByLimit;
	var $additionalGroupBy;
	var $evalSQLfunctions;
	var $whereOverrideArray;
	var $unionListArray;
	var $fielsNameMap;

	// the selct strings
	var $selectString; 
	var $countSelectString;
	var $totalSelectString; 
	var $fromString;
	var $whereString;
	var $groupbyString;
	var $havingString;
	var $orderbyString;	

	var $addParams;
	
	var $queryArray;
	
	function KReportQueryArray($rootModule, $unionModules, $evalSQLFunctions, $listFields, $unionListFields, $whereFields, $additonalFilter = '', $whereGroupFields,  $additionalGroupBy = array(), $addParams = array()){
		// set the various Fields
		$this->root_module = $rootModule;
		$this->union_modules = $unionModules;
		$this->listArray = $listFields;
		$this->unionListArray = $unionListFields;
		$this->whereArray = $whereFields; 
		$this->whereAddtionalFilter = $additonalFilter;
		$this->whereGroupsArray = $whereGroupFields;
		// $this->groupByLimit = $groupByLimit;
		$this->additionalGroupBy = $additionalGroupBy;
		$this->evalSQLFunctions = $evalSQLFunctions;
		
		$this->addParams = $addParams;
		
		// handle Where Override
		// need to think about moving this
		if(isset($_REQUEST['whereConditions']))
		{
		  $this->whereOverrideArray = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));
		} 
	}
	
	function build_query_strings(){
		if($this->union_modules != '')
		{
			// handle root module
			// filter the array to only have root
			$i = 0;
			while($i < count($this->whereArray))
			{
				if($this->whereArray[$i]['unionid'] == 'root') $this->queryArray['root']['whereArray'][] = $this->whereArray[$i];
				$i++;
			}
			
			$i = 0;
			while($i < count($this->whereGroupsArray))
			{
				if($this->whereGroupsArray[$i]['unionid'] == 'root') $this->queryArray['root']['whereGroupsArray'][] = $this->whereGroupsArray[$i];
				$i++;
			}		
			
			$this->queryArray['root']['kQuery'] =  new KReportQuery($this->root_module, $this->evalSQLFunctions, $this->listArray, $this->queryArray['root']['whereArray'], $this->whereAddtionalFilter, $this->queryArray['root']['whereGroupsArray'], $this->additionalGroupBy, $this->addParams);
			
			// set union ID & groupings as well as order clause to be by ID
			$this->queryArray['root']['kQuery']->unionId = 'root';
			$this->queryArray['root']['kQuery']->orderByFieldID = true;
			$this->queryArray['root']['kQuery']->groupByFieldID = true;
			
			// build the query Strings for the root Query
			$this->queryArray['root']['kQuery']->build_query_strings();
			$this->fieldNameMap = $this->queryArray['root']['kQuery']->fieldNameMap;
		
			//hanlde union 
			$unionArrayNew = json_decode(html_entity_decode($this->union_modules), true);
			// $unionArray = preg_split('/;/', $this->union_modules);
			foreach($unionArrayNew as $thisUnionArrayEntry)
			{
				/*
				$thisUnionDetails = preg_split('/:/', $thisUnionArrayEntry);
				$thisUnionId = preg_replace('/union/', '', $thisUnionDetails[0]);
				$thisUnionModule = $thisUnionDetails[1];
				*/
				$thisUnionId = $thisUnionArrayEntry['unionid'];
				$thisUnionModule = $thisUnionArrayEntry['module'];
				
				//filter where and where groups
				$i = 0;
				while($i < count($this->whereArray))
				{
					if($this->whereArray[$i]['unionid'] == $thisUnionId) 
					{
						$this->queryArray[$thisUnionId]['whereArray'][] = $this->whereArray[$i];
						// replace the beginning of the string to make it root 
						$this->queryArray[$thisUnionId]['whereArray'][count($this->queryArray[$thisUnionId]['whereArray']) - 1]['path'] = preg_replace('/unionroot::union[A-Za-z0-9]*:/', 'root:', $this->queryArray[$thisUnionId]['whereArray'][count($this->queryArray[$thisUnionId]['whereArray']) - 1]['path']);
						
					}
					$i++;
				}
				
				$i = 0;
				while($i < count($this->whereGroupsArray))
				{
					if($this->whereGroupsArray[$i]['unionid'] == $thisUnionId) $this->queryArray[$thisUnionId]['whereGroupsArray'][] = $this->whereGroupsArray[$i];
					$i++;
				}	
				
				//build the list array for this union
				$i = 0;
				while($i < count($this->listArray))
				{
					$this->queryArray[$thisUnionId]['listArray'][$i] = $this->listArray[$i];

					foreach($this->unionListArray as $thisUnionListEntryId => $thisUnionListEntry)
					{
						if($thisUnionListEntry['joinid'] == $thisUnionId && $thisUnionListEntry['fieldid'] == $this->listArray[$i]['fieldid'])
						{
							// we have a match
							if($thisUnionListEntry['unionfieldpath'] != '' && (!isset($thisUnionListEntry['fixedvalue']) || $thisUnionListEntry['fixedvalue'] == ''))
							{
								// also replace the union id with the new root in the fieldpath ...
								// the union entry is root in the new subquery
								$this->queryArray[$thisUnionId]['listArray'][$i]['path'] = preg_replace('/union' .  $thisUnionId . '/', 'root', $thisUnionListEntry['unionfieldpath']);
							}
							else
							{
								//reset the path in any case
								$this->queryArray[$thisUnionId]['listArray'][$i]['path'] = '';
								
								// set a fixed value to '-' if we do not have a fixed value
								// TODO: change query logic to adopt to empty field if no path is set and then take the fixed value ''
								if(isset($thisUnionListEntry['fixedvalue']) && $thisUnionListEntry['fixedvalue'] != '')
										$this->queryArray[$thisUnionId]['listArray'][$i]['fixedvalue'] = $thisUnionListEntry['fixedvalue'];
									else
										$this->queryArray[$thisUnionId]['listArray'][$i]['fixedvalue'] = '-';
							}	
							
						}
					}
					
					$i++;
					// find the entry in the unionlist fields array
				}
				
				$this->queryArray[$thisUnionId]['kQuery'] =  new KReportQuery($thisUnionModule, $this->evalSQLFunctions, $this->queryArray[$thisUnionId]['listArray'], $this->queryArray[$thisUnionId]['whereArray'], $this->whereAddtionalFilter, $this->queryArray[$thisUnionId]['whereGroupsArray'], $this->additionalGroupBy, $this->addParams);
				
				// set the unionid & grouping as well as order clause
				$this->queryArray[$thisUnionId]['kQuery']->unionId = $thisUnionId;
				$this->queryArray[$thisUnionId]['kQuery']->orderByFieldID = true;
				$this->queryArray[$thisUnionId]['kQuery']->groupByFieldID = true;
				
				// build the query strings
				$this->queryArray[$thisUnionId]['kQuery']->build_query_strings();
			}
			
			// enrich the kqueries by all joinsegments and reporcess the select to get all join segments in (nned that for the ids for the various records
			$totalJoinSegments = array();
			foreach($this->queryArray as $thisUnionId => $thisUnionQuery)
			{
				foreach($thisUnionQuery['kQuery']->joinSegments as $thisPath => $thisPathProperties)
				{
					$totalJoinSegments[$thisPathProperties['alias']] = array('level' => $thisPathProperties['level'],  'path' => $thisPath, 'unionid' => $thisUnionId);
				}
			}
			
			// revuild all select strings
			// first for root
			$this->queryArray['root']['kQuery']->build_select_string($totalJoinSegments);
			// then for all the joins
			foreach($unionArrayNew as $thisUnionArrayEntry)
			{
				$this->queryArray[$thisUnionArrayEntry['unionid']]['kQuery']->build_select_string($totalJoinSegments);
			}
			
			/*
			$this->selectString =  $this->queryArray['root']['kQuery']->selectString;
			$this->fromString = $this->queryArray['root']['kQuery']->fromString;
			$this->whereString = $this->queryArray['root']['kQuery']->whereString;
			$this->groupbyString = $this->queryArray['root']['kQuery']->groupbyString;
			$this->havingString =  $this->queryArray['root']['kQuery']->havingString;
			$this->orderbyString = $this->queryArray['root']['kQuery']->orderbyString;
			*/
			
			// build the root string 
		    $queryString = '';
		    foreach($this->queryArray as $id => $queryArrayData)
		    {
		    	if($queryString != '') $queryString .= ' UNION ';
				$queryString .= $queryArrayData['kQuery']->selectString . ' ' . $queryArrayData['kQuery']->fromString . ' ' . $queryArrayData['kQuery']->whereString;
		    }
			$queryString .= ' ' . $this->queryArray['root']['kQuery']->groupbyString . ' ' . $this->queryArray['root']['kQuery']->havingString . ' ' . $this->queryArray['root']['kQuery']->orderbyString;
			// build the unions
			return $queryString;
			
			// return $this->selectString . ' ' . $this->fromString . ' ' . $this->whereString . ' ' . $this->groupbyString . ' ' . $this->havingString . ' ' . $this->orderbyString;
		}
		else
		{
			// handle root module
			// filter the array to only have root
			$i = 0;
			while($i < count($this->whereArray))
			{
				if($this->whereArray[$i]['unionid'] == 'root') $this->queryArray['root']['whereArray'][] = $this->whereArray[$i];
				$i++;
			}
			
			$i = 0;
			while($i < count($this->whereGroupsArray))
			{
				if($this->whereGroupsArray[$i]['unionid'] == 'root') $this->queryArray['root']['whereGroupsArray'][] = $this->whereGroupsArray[$i];
				$i++;
			}		
			
			$this->queryArray['root']['kQuery'] =  new KReportQuery($this->root_module, $this->evalSQLFunctions, $this->listArray, $this->queryArray['root']['whereArray'], $this->whereAddtionalFilter, $this->queryArray['root']['whereGroupsArray'], $this->additionalGroupBy, $this->addParams);
			//temp see if this works
			
			$this->queryArray['root']['kQuery']->build_query_strings();
			$this->fieldNameMap = $this->queryArray['root']['kQuery']->fieldNameMap;

			$this->selectString =  $this->queryArray['root']['kQuery']->selectString;
			$this->fromString = $this->queryArray['root']['kQuery']->fromString;
			$this->whereString = $this->queryArray['root']['kQuery']->whereString;
			$this->groupbyString = $this->queryArray['root']['kQuery']->groupbyString;
			$this->havingString =  $this->queryArray['root']['kQuery']->havingString;
			$this->orderbyString = $this->queryArray['root']['kQuery']->orderbyString;
			
			if($this->queryArray['root']['kQuery']->totalSelectString != '')
				$this->totalSelectString = $this->queryArray['root']['kQuery']->totalSelectString . ' ' . $this->fromString . ' ' . $this->whereString;
			
			if($this->queryArray['root']['kQuery']->countSelectString != '')
				$this->countSelectString = 'SELECT COUNT(sugarRecordId) as totalCount from (' . $this->queryArray['root']['kQuery']->countSelectString . ' ' . $this->fromString . ' ' . $this->whereString . ' ' . $this->groupbyString . ') as origCountSQL';
				
			return $this->selectString . ' ' . $this->fromString . ' ' . $this->whereString . ' ' . $this->groupbyString . ' ' . $this->havingString . ' ' . $this->orderbyString;
		}
	}
	
}

// basic class for the query itself
class KReportQuery {
	
	/*
	 * Min things to know
	 * first initialize the class
	 * call build_ath to explode the various fields we might look at and build the path
	 * call build_from_string to build all the join_segments and build the from string
	 * after tha you can call the other functions
	 */
	
	var $root_module;
	var $unionId = '';
	
	var $whereArray;
	var $whereAddtionalFilter = '';
	var $whereOverrideArray;
	var $listArray;
	var $whereGroupsArray;

	var $fieldNameMap;
	
	var $tablePath;
	
	var $rootGuid;
	var $joinSegments;
	var	$maxDepth;
	
	var $queryID = '';
	var $orderByFieldID = false;
	var $groupByFieldID = false;
	
	// parts of the SQL Query
	var $selectString;
	var $countSelectString;
	var $totalSelectString;
	var $fromString;
	var $whereString;	
	var $havingString;
	var $groupbyString;
	var $additionalGroupBy;
	var $orderbyString;
	
	// Parameters
	var $evalSQLFunctions = true;
	
	// auth Check level (full, top, none)
	var $authChecklevel = 'full';
	var $showDeleted = false;
	
	// constructor
	/*
	 *  Additonal Filter = array with Fieldid and value .. wich is then applied to the where clause
	 *  $addParams - AuthCheckLevel = full, top none
	 *  			 showDeleted = true, false
	 */
	function KreportQuery($rootModule, $evalSQLFunctions, $listFields, $whereFields, $additonalFilter = '', $whereGroupFields, $additionalGroupBy = array(), $addParams = array()){
		// set the various Fields
		$this->root_module = $rootModule;
		$this->listArray = $listFields;
		$this->whereArray = $whereFields; 
		$this->whereAddtionalFilter = $additonalFilter;
		$this->whereGroupsArray = $whereGroupFields;
		$this->additionalGroupBy= $additionalGroupBy;
		$this->evalSQLFunctions = $evalSQLFunctions;
		
		
		// handle additional parameters
		if(isset($addParams['authChecklevel']))
			$this->authCheckLevel = $addParams['authChecklevel'];
			
		if(isset($addParams['showDeleted']))
			$this->showDeleted = $addParams['showDeleted'];
		
		// handle Where Override
		// need to think about moving this
		if(isset($_REQUEST['whereConditions']))
		{
		  $this->whereOverrideArray = json_decode_kinamu( html_entity_decode_utf8($_REQUEST['whereConditions']));
		} 

	}
	
	function build_query_strings(){
		$this->build_path();
		$this->build_from_string();
		$this->build_select_string();
		$this->build_where_string();
		$this->build_orderby_string();
		$this->build_groupby_string($this->additionalGroupBy);
	}

	
	/*
	 * Function to build the JOin Type form th type in the Report
	 */
	function switchJoinType($jointype){
		// TODO handle not existing join type
		switch($jointype){
			case "optional":
				return ' LEFT JOIN ';
				break;
			case "required":
				return ' INNER JOIN ';
				break;
			case "notexisting":
				return ' LEFT JOIN ';
				break;
		}
	}
	
	/*
	 * Build Path:
	 * function to extract all the path informatios out of the JSON Array we store
	 */
	
	function build_path(){
		
		if((is_array($this->whereArray) && count($this->whereArray) > 0) || (is_array($this->listArray) && count($this->listArray) > 0) )
		{
			/*
			 * Build the path array with all valid comkbinations (basically joins we can meet
			 */
			// collect Path entries for the Where Clauses
			foreach($this->whereArray as $thisWhere)
			{
				// $this->addPath($thisWhere['path'], $this->switchJoinType($thisWhere['jointype']));
				
				// check if the group this belongs to is a notexits group
				$flagNotExists = false;
				if(is_array($this->whereGroupArray)) 
				{
					reset($this->whereGroupArray);
					foreach($this->whereGroupArray as $thisWhereGroupEntry)
					{
						if($thisWhereGroupEntry['id'] == $thisWhere['groupid'] && $thisWhereGroupEntry['notexists'] == '1')
							$flagNotExists = false;
					}
				}
				// if the flag is set -> LEFT JOIN ... 
				//$this->addPath($thisWhere['path'], ($flagNotExists) ? 'LEFT JOIN' : 'INNER JOIN');
				// revert .. 
				$this->addPath($thisWhere['path'], $this->switchJoinType($thisWhere['jointype']));
			}
			
			// same for the List Clauses
			foreach($this->listArray as $thisListEntry)
			{
				$this->addPath($thisListEntry['path'], $this->switchJoinType($thisListEntry['jointype']));
			}
		}
	}
	
	/*
	 * Helper function to add the path we found
	 * 
	 */
	function addPath($path, $jointype){
		if($path != '')
		{
			// require_once('include/utils.php');
			$fieldPos = strpos($path, "::field");
			$path = substr($path, 0, $fieldPos);
			
			if(!isset($this->tablePath[$path]))
			{
				$this->tablePath[$path] = $jointype;
			}
			else
			{
				// if we have an inner join now ... upgrade ..
				if($this->tablePath[$path] == 'LEFT JOIN' && $jointype == 'INNER JOIN')
					$this->tablePath[$path] = $jointype;
			}
			
			// check if we have more to add
			// required if we have roiutes where there is no field used in between
			// search for a separator from the end and add the path if we do not yet know it
			// the join build will pick this up in the next step
			while($sepPos = strrpos($path, "::"))
			{
				// cut down the path
				$path = substr($path, 0, $sepPos);
				
				// see if we have to add the path
				if(!isset($this->tablePath[$path]))
				{
					$this->tablePath[$path] = $jointype;
				}
				else
				{
					// if we have an inner join now ... upgrade ..
					if($this->tablePath[$path] == 'LEFT JOIN' && $jointype == 'INNER JOIN')
						$this->tablePath[$path] = $jointype;
				}
			}
		}
	}
	
	/*
	 * Function that evaluates the path and then build the join segments 
	 * need that later on to identify the segmets of the select statement
	 */
	function build_from_string(){
		global $db, $app_list_strings, $beanList, $beanFiles, $current_user;
		
		// Create a root GUID
		$this->rootGuid = randomstring();
		
		$this->joinSegments = array();
		$this->maxDepth = 0;
		
		$kOrgUnits = false;

		//check if we do the Org Check
		if(file_exists('modules/KOrgObjects/KOrgObject.php'))
		{
			require_once('modules/KOrgObjects/KOrgObject.php');
			$thisKOrgObject = new KOrgObject();
			$kOrgUnits = true;
		}
		
		/*
		 * Build the array for the joins based on the various Path we have
		 */
		foreach($this->tablePath as $thisPath => $thisPathJoinType)
		{
			// Process backcutting until we have found the node going upwards
			// in the segments array or we are on the root segment 
			// (when no '::' can be found)
			if(substr_count($thisPath, '::') > $this->maxDepth) $this->maxDepth = substr_count($thisPath, '::');
			
			while(strpos($thisPath, '::') && !isset($this->joinSegments[$thisPath]))
			{
				// add the segment to the segments table
				$this->joinSegments[$thisPath] = array( 'alias' => randomstring(), 'level' => substr_count($thisPath, '::'), 'jointype' => $thisPathJoinType);
				
				// find last occurence of '::' in the string and cut off there
				$thisPath = substr($thisPath, strrpos($thisPath, "::"));
			}
		}
		
		// Get the main Table we select from
		$this->fromString = 'from ' . $this->get_table_for_module($this->root_module) .' as ' . $this->rootGuid;
		// check if this is an array so we need to add joins ... 
		

	    // add an entry for the root Object ... 
		// needed as reference for the GUID
		$this->joinSegments['root:' . $this->root_module] = array( 'alias' => $this->rootGuid, 'level' => 0);
		 
		// get ther root Object 
		require_once($beanFiles[$beanList[$this->root_module]]);
		$this->joinSegments['root:' . $this->root_module]['object'] = new $beanList[$this->root_module]();
			
		// check for Custom Fields
		if($this->joinSegments['root:' . $this->root_module]['object']->hasCustomFields())
		{
		     $this->joinSegments['root:' . $this->root_module]['customjoin'] = randomstring();
		     $this->fromString .= ' LEFT JOIN ' . $this->get_table_for_module($this->root_module) . '_cstm as ' . $this->joinSegments['root:' . $this->root_module]['customjoin'] . '  ON ' . $this->rootGuid . '.id = ' . $this->joinSegments['root:' . $this->root_module]['customjoin'] . '.id_c';
		}
		
	    if($kOrgUnits && $this->authChecklevel != 'none')
        {
	        $this->fromString .=  $thisKOrgObject->getOrgunitJoin($this->joinSegments['root:' . $this->root_module]['object']->table_name, $this->joinSegments['root:' . $this->root_module]['object']->object_name, $this->rootGuid, '1');
        }
		
		// Index to iterate through the join table building the joins
		// from the root object outward going
		$levelCounter = 1;		
		
		if(is_array($this->joinSegments))
		{
		  
		  while($levelCounter <= $this->maxDepth)
		  {
		  	// set the array back to the first element in the array
		  	reset($this->joinSegments);
		  	
		  	foreach($this->joinSegments as $thisPath => $thisPathDetails)
		  	{
		  		// process only entries for the respective levels
		  		if($thisPathDetails['level'] == $levelCounter)
		  		{
		  			// get the last enrty and the one before and the relevant arrays
		  			$rightPath = substr($thisPath, strrpos($thisPath, "::") + 2, strlen($thisPath));
		  			$leftPath = substr($thisPath, 0, strrpos($thisPath, "::"));
		  			
		  			// explode into the relevant arrays
		  			$rightArray = explode(':',$rightPath);
		  			$leftArray = explode(':', $leftPath);
		  			
		  			//left Path Object must be set since we process from the top 
					if(!($this->joinSegments[$leftPath]['object'] instanceof $beanList[$rightArray[1]])) 
					{
						die('fatal Error in Join');
					}

					// load the relationship .. resp link
					$this->joinSegments[$leftPath]['object']->load_relationship($rightArray[2]);
					// set aliases for left and right .. will be processed properly anyway in the build of the link 
					// ... funny enough so
					$join_params = array(
						'join_type' => $thisPathDetails['jointype'], 
						'right_join_table_alias' => $this->joinSegments[$leftPath]['alias'], 
						'left_join_table_alias' => $this->joinSegments[$leftPath]['alias'], 
						'join_table_link_alias' => randomstring() , 
						'join_table_alias' => $this->joinSegments[$thisPath]['alias']					
					);
					
					//2010-09-09 Bug to handle left side join relationship
					if(isset($this->joinSegments[$leftPath]['object']->field_defs[$rightArray[2]]['side']) && $this->joinSegments[$leftPath]['object']->field_defs[$rightArray[2]]['side'] == 'left' && !$this->joinSegments[$leftPath]['object']->$rightArray[2]->_swap_sides)
						$this->joinSegments[$leftPath]['object']->$rightArray[2]->_swap_sides = true;
						
					$linkJoin = $this->joinSegments[$leftPath]['object']->$rightArray[2]->getJoin($join_params);
					
					$this->fromString .= ' ' . $linkJoin;
					
					// load the module on the right hand side
		 			require_once($beanFiles[$beanList[$this->joinSegments[$leftPath]['object']->$rightArray[2]->getRelatedModuleName()]]);
		 			$this->joinSegments[$thisPath]['object'] = new $beanList[$this->joinSegments[$leftPath]['object']->$rightArray[2]->getRelatedModuleName()]();
					
		 			//bugfix 2010-08-19, respect ACL role access for owner reuqired in select
		  			if($this->joinSegments[$leftPath]['object']->bean_implements('ACL') && ACLController::requireOwner($this->joinSegments[$leftPath]['object']->module_dir, 'list') )
			    	{
			    		$this->whereString .= ' AND ' . $this->joinSegments[$leftPath]['alias'] . '.assigned_user_id=\'' . $current_user->id . '\'';
			    	}
		 			
		  			// check for Custom Fields
					if($this->joinSegments[$thisPath]['object']->hasCustomFields())
					{
					     $this->joinSegments[$thisPath]['customjoin'] = randomstring();
					     $this->fromString .= ' LEFT JOIN ' . $this->joinSegments[$thisPath]['object']->table_name . '_cstm as ' . $this->joinSegments[$thisPath]['customjoin'] . ' ON ' . $this->joinSegments[$thisPath]['alias'] . '.id = ' . $this->joinSegments[$thisPath]['customjoin'] . '.id_c';
					}
		 			
		  			// append join for Orgobjects if Object is OrgManaged
					if($kOrgUnits && ($this->authChecklevel != 'none' && $this->authChecklevel != 'top'))
					{
				 		$this->fromString .= $thisKOrgObject->getOrgunitJoin($this->joinSegments[$thisPath]['object']->table_name, $this->joinSegments[$thisPath]['object']->object_name, $this->joinSegments[$thisPath]['alias'],  '1');
					}					
		  		}
		  	}
		  	
		  	// increase Counter to tackle next level
		  	$levelCounter++;
		  }
		  
		}
	}	
	/*
	 * function that build the selct string
	 * parameter unionJoinSegments to hand in more join segments to include
	 * in select stamenet when we are in a union join mode - then this function gets
	 * processed twice
	 */
	function build_select_string($unionJoinSegments = ''){
			// require_once('include/utils.php');
		global $db, $app_list_strings, $beanList, $beanFiles;
		/*
		 * Block to build the selct clause with all fields selected
		 */
		// build select 
		$this->selectString = 'SELECT ' . $this->rootGuid . '.id as sugarRecordId';
		
		// 2011-02-03 for Performance add a count Query
		// just for the count
		$this->countSelectString = 'SELECT ' . $this->rootGuid . '.id as sugarRecordId';
		
		// see if we are in a union statement then we select the unionid as well
		if($this->unionId != '')
			$this->selectString .= ', \'' . $this->unionId . '\' as unionid';
		
		// select the ids for the various linked tables
		// check if we have joins for a union passed in ... 
		if($unionJoinSegments != '' && is_array($unionJoinSegments))
		{
			foreach($unionJoinSegments as $thisAlias => $thisJoinIdData)
			{
				if($thisJoinIdData['unionid'] == $this->unionId)
				{
					// this is for this join ... so we select the id 
					$this->selectString .= ', ' . $thisAlias . '.id as ' . $thisAlias . 'id';
				}
				else
				{
					// this is for another join ... so we select an empty field 
					$this->selectString .= ', \'\' as ' . $thisAlias . 'id';
				}
			}
		}
		else
		// standard processing ... we simply loop throgh the joinsegments
		{
			foreach($this->joinSegments as $joinsegment)
			{
				$this->selectString .= ', ' . $joinsegment['alias'] . '.id as ' . $joinsegment['alias'] . 'id';
			}
		}
		
		if(is_array($this->listArray))
		{
			foreach($this->listArray as $thisListEntry)
			{
				// $this->addPath($thisList['path'], $this->switchJoinType($thisList['jointype']));
					$fieldName = substr($thisListEntry['path'], strrpos($thisListEntry['path'], "::") + 2, strlen($thisListEntry['path']));
		  			$pathName = substr($thisListEntry['path'], 0, strrpos($thisListEntry['path'], "::"));
		  			
		  			$fieldArray = explode(':', $fieldName);
		  			
		  			
		  			// process an SQL Function if one is set and the eval trigger is set to true
		  			// if we have a fixed value select that value
		  			if(isset( $thisListEntry['fixedvalue']) && $thisListEntry['fixedvalue'] != '' )
		  			{
		  				//if($thisListEntry['sqlfunction'] != '-' && $this->evalSQLFunctions )
			  			//	$this->selectString .= ', ' . $thisListEntry['sqlfunction'] . '(' . $thisListEntry['fixedvalue'] . ') as ' . $thisListEntry['fieldid'];
			  			// else
			  				$this->selectString .= ", '" . $thisListEntry['fixedvalue'] . "' as '" . $thisListEntry['fieldid'] . "'";
			  				
			  			// add this to the fieldName Map in case we link a fixed 
			  			 $this->fieldNameMap[$thisListEntry['fieldid']] = array('fieldname' => '', 'path' => '', 'islink' => ($thisListEntry['link'] == 'yes') ? true : false,  'sqlFunction' => '', 'tablealias' => $this->rootGuid, 'fields_name_map_entry' =>'' , 'type' => 'fixedvalue', 'module' => $this->root_module);
		  			}
		  			else
		  			{
			  			if($thisListEntry['sqlfunction'] != '-' && $this->evalSQLFunctions && ($this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['type'] != 'kreporter' || ($this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['type'] == 'kreporter' && $this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['evalSQLFunction'] == 'X')))
			  				$this->selectString .= ', ' . $thisListEntry['sqlfunction'] . '(' . $this->get_field_name($pathName, $fieldArray[1], $thisListEntry['fieldid'], ($thisListEntry['link'] == 'yes') ? true : false, $thisListEntry['sqlfunction']) . ')';
			  			else
			  			{
			  				//if(isset($thisListEntry['customsqlfunction']) && $thisListEntry['customsqlfunction'] != '')
			  				//	$this->selectString .= ', ' . str_replace('$', $this->get_field_name($pathName, $fieldArray[1], $thisListEntry['fieldid'], ($thisListEntry['link'] == 'yes') ? true : false), $thisListEntry['customsqlfunction']);
			  				//else
			  					$this->selectString .= ', ' . $this->get_field_name($pathName, $fieldArray[1], $thisListEntry['fieldid'], ($thisListEntry['link'] == 'yes') ? true : false);
			  			}
			  				
			  			
			  			
			  				
			  			if(isset($thisListEntry['fieldid']) && $thisListEntry['fieldid'] != '' )
			  			{
			  				$this->selectString .= " as '" . $thisListEntry['fieldid'] . "'";
			  			}
			  			
			  			// 2010-12-18 handle currencies if value is set in vardefs
			  			if($this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['type'] == 'currency' || $this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['kreporttype'] == 'currency')
			  			{
			  				// if we have a currency id and no SQL function select the currency .. if we have an SQL fnction select -99 for the system currency
			  				if(isset($this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['currency_id']) && ($thisListEntry['sqlfunction'] == '-' || strtoupper($thisListEntry['sqlfunction']) == 'SUM'))
			  					$this->selectString .= ", " . $this->joinSegments[$pathName]['alias'] . "." . $this->joinSegments[$pathName]['object']->field_name_map[$fieldArray[1]]['currency_id'] . " as '" . $thisListEntry['fieldid'] . "_curid'";
			  				else 
			  					$this->selectString .= ", '-99' as '" . $thisListEntry['fieldid'] . "_curid'";
			  			}
			  			
			  			//2011-02-03 for calculating percentages
			  			if(isset($thisListEntry['valuetype']) && $thisListEntry['valuetype'] != '' && $thisListEntry['valuetype'] != '-')
			  			{
			  				// first part of value is calulated what to do with the alue ... 2nd part is SQL function we need
			  				// 'OF' separates
			  				$funcArray = split('OF', $thisListEntry['valuetype']); 
			  				if($this->totalSelectString == '') $this->totalSelectString = 'SELECT '; else $this->totalSelectString .= ', ';
			  				$this->totalSelectString .= ' ' . $funcArray[1] . '(' . $this->get_field_name($pathName, $fieldArray[1], $thisListEntry['fieldid'], ($thisListEntry['link'] == 'yes') ? true : false) . ")  as '" . $thisListEntry['fieldid'] . "_total'";
			  			}
		  			}
		  			
		  			// whatever we need this for? 
		  			// TODO: check if we still need this and if what for 
		  			//$selectFields[] = trim($thisListEntry['name'], ':');
			}
		}
		else 
		{
			$this->selectString .= '*';
		}

	}
	
	/*
	 * Function to build the where String
	 */
	function build_where_string()
	{
		global $db, $app_list_strings, $beanList, $beanFiles, $current_user;
		
		/*
		 * Block to build the Where Clause 
		 */		
		// see if we need to ovveride
		if(is_array($this->whereOverrideArray))
		{
			foreach($this->whereOverrideArray as $overrideKey => $overrideData)
			{
				reset($this->whereArray);
				foreach($this->whereArray as $originalKey => $originalData)
				{
					if($originalData['fieldid'] == $overrideData['fieldid'])
					{
						$this->whereArray[$originalKey] = $overrideData;
						// need to exit the while loop
					}
				}
			}
		}
		
		// build the where String for each Group
		foreach($this->whereGroupsArray as $whereGroupIndex => $thisWhereGroup)
		{
			$thisWhereString = '';
			// reset the Where fields and loop over all fields to see if any is in our group
			reset($this->whereArray);
			foreach($this->whereArray as $thisWhere)
			{
				// check if this is for the current group
				// 2011--01-24 add ignore filter
				if($thisWhere['groupid'] == $thisWhereGroup['id'] && $thisWhere['operator'] != 'ignore')
				{

					// if we have an where string already concetanate with the type for the group AND or OR
					if($thisWhereString != '')
						$thisWhereString .= ' ' . $thisWhereGroup['type']  . ' (';
					else
						$thisWhereString .= '(';
					
					// process the Field and link with the joinalias
					$fieldName = substr($thisWhere['path'], strrpos($thisWhere['path'], "::") + 2, strlen($thisWhere['path']));
		  			$pathName = substr($thisWhere['path'], 0, strrpos($thisWhere['path'], "::"));
		  			$fieldArray = explode(':', $fieldName);
					
					if($thisWhere['jointype'] != 'notexisting')
					{			
						//getWhereOperatorClause($operator, $fieldname, $alias,  $value, $valuekey, $valueto)
						//$thisWhereString .= $this->getWhereOperatorClause($thisWhere['operator'], $fieldArray[1], $this->joinSegments[$pathName]['alias'],  $thisWhere['value'], $thisWhere['valuekey'], $thisWhere['valueto']);
						$thisWhereString .= $this->getWhereOperatorClause($thisWhere['operator'], $fieldArray[1], $thisWhere['fieldid'], $pathName,  $thisWhere['value'], $thisWhere['valuekey'], $thisWhere['valueto']);
						
					}
					else
					{
						// we have a not esists clause
						$thisWhereString .= 'not exists(';
						
			  			// get the last enrty and the one before and the relevant arrays
			  			$rightPath = substr($pathName, strrpos($pathName, "::") + 2, strlen($pathName));
			  			$leftPath = substr($pathName, 0, strrpos($pathName, "::"));
			  			
			  			// explode into the relevant arrays
			  			$rightArray = explode(':',$rightPath);
			  			$leftArray = explode(':', $leftPath);
			  			
						// set aliases for left and right .. will be processed properly anyway in the build of the link 
						// ... funny enough so
						$join_params = array(
							'right_join_table_alias' => $this->joinSegments[$leftPath]['alias'], 
							'left_join_table_alias' => $this->joinSegments[$leftPath]['alias'], 
							'join_table_link_alias' => $this->randomstring() , 
							'join_table_alias' => $this->joinSegments[$pathName]['alias']					
						);
						
						$thisWhereString .= $this->joinSegments[$leftPath]['object']->$rightArray[2]->getWhereExistsStatement($join_params);
						
						// add the standard Where Clause
						// $thisWhereString .= $this->getWhereOperatorClause($thisWhere['operator'], $fieldArray[1], $this->joinSegments[$pathName]['alias'],  $thisWhere['value'], $thisWhere['valuekey'], $thisWhere['valueto']);
						$thisWhereString .= 'AND ' . $this->getWhereOperatorClause($thisWhere['operator'], $fieldArray[1], $thisWhere['fieldid'], $pathName,  $thisWhere['value'], $thisWhere['valuekey'], $thisWhere['valueto']);
						
						// close the select clause
						$thisWhereString .= ')';
						
					}
			  			
					// close this condition
					$thisWhereString .= ')';
					
					
				}
			}
			$thisWhereGroup['whereClause'] = $thisWhereString;
			
			// write into an array with the id as index in the array (will need that tobuild the hierarchy
			$arrayWhereGroupsIndexed[$thisWhereGroup['id']] = $thisWhereGroup;
		}
		
		// process now topDown
		$this->whereString = $this->buildWhereClauseForLevel($arrayWhereGroupsIndexed['root'], $arrayWhereGroupsIndexed);
		
		// 2010-07-18 additonal Filter mainly for the treeview
		if(is_array($this->whereAddtionalFilter))
		{
			foreach($this->whereAddtionalFilter as $filterFieldId => $filterFieldValue)
			{
				//special treatment for fied values where we do not have a path
				if($this->get_fieldname_by_fieldid($filterFieldId) == '')
				{
					($this->havingString == '') ? $this->havingString = 'HAVING ' : $this->havingString .= ' AND ';
					$this->havingString .= $filterFieldId . " = '" . $filterFieldValue . "'"; 
				}
				else
				{
					if($this->whereString != '') $this->whereString .= ' AND ';
					$this->whereString .= $this->getWhereOperatorClause('equals', $this->get_fieldname_by_fieldid($filterFieldId), $filterFieldId, $this->get_fieldpath_by_fieldid($filterFieldId),  $filterFieldValue, '', '');
				}
				// $this->whereString .= ' ' . $this->fieldNameMap[$filterFieldId]['tablealias'] . '.' . $this->fieldNameMap[$filterFieldId]['fieldname'] . ' = \'' . $filterFieldValue . '\''; 
			}
		}
		
		// bugfix 2010-06-14 exclude deleted items
		// add feature fcheck if we shod show deleted records
		if(!$this->showDeleted)
		{
			if($this->whereString != '')
				$this->whereString = 'WHERE ' . $this->rootGuid . '.deleted = \'0\' AND '. $this->whereString ;
			else
				$this->whereString = 'WHERE ' . $this->rootGuid . '.deleted = \'0\'';
		}
		else
		{
			if($this->whereString != '')
				$this->whereString = 'WHERE ' . $this->whereString ;
			else
				$this->whereString = '';
		}
		
		// bugfix 2010-08-19, respect ACL access for owner required
		//check for Role based access on root module
		if(!$current_user->is_admin && $this->joinSegments['root:' . $this->root_module]['object']->bean_implements('ACL') && ACLController::requireOwner($this->joinSegments['root:' . $this->root_module]['object']->module_dir, 'list') )
    	{
    		$this->whereString .= ' AND ' . $this->rootGuid . '.assigned_user_id=\'' . $current_user->id . '\'';
    	}
			
	}
	
	/*
	 * Function to build the Where Clause for one level
	 * calls build for Children and get calls recursively
	 */
	function buildWhereClauseForLevel($thisLevel, $completeArray = array())
	{
		$whereClause = '';
		
		//find Children
		foreach($completeArray as $currentEntry)
		{
			if($currentEntry['parent'] == $thisLevel['id'])
			{
				$thisLevel['children'][$currentEntry['id']] = $currentEntry;
			}
		}
		
		// if we have Children build the Where Clause for the Children
		if(is_array($thisLevel['children']))
			$whereClauseChildren = $this->buildWhereClauseForChildren($thisLevel['children'], $thisLevel['type'], $completeArray);
		else
			$whereClauseChildren = '';
			
		// build the combined Whereclause
		if(isset($thisLevel['whereClause']) && $thisLevel['whereClause'] != '')
		{
				$whereClause = $thisLevel['whereClause'];
		}	
		
		// add the Children Where Clauses if there is any
		if($whereClauseChildren != '')
		{
			if($whereClause != '')
				$whereClause .= ' ' . $thisLevel['type'] . ' ' . $whereClauseChildren;
			else
				$whereClause = $whereClauseChildren;
		}
		
		// if there is a where clause encapsulate it
		if($whereClause != '')
			$whereClause = '(' . $whereClause . ')';
			
		// return whatever we have built
		return  $whereClause;

		
	}
	
	/*
	 * Function to build the Where Clause for the Children and return it
	 */
	function buildWhereClauseForChildren($thisChildren, $thisOperator, $completeArray)
	{
		$whereClause = '';
		foreach($thisChildren as $thisChild)
		{
			// recursively build the clause for this level and if we have
			// children we get called again ... loop top down ... 
			$childWhereClause = $this->buildWhereClauseForLevel($thisChild, $completeArray);
			
			// check if there is something to add
			if($childWhereClause != '')
			{
				if($whereClause != '')
					$whereClause .= ' ' . $thisOperator . ' ' . $childWhereClause;
				else
					$whereClause = $childWhereClause;
			}
		}
		
		// check if we have a where Clause at all and if encapsulate
		return $whereClause;
	}
	
	/*
	 * process the where operator
	 */
	function getWhereOperatorClause($operator, $fieldname, $fieldid, $path,  $value, $valuekey, $valueto)
	{
			global $current_user;
		
			// add ignore Operator 2011-01-24
			// in this case we simply jump back out returning an empty string.
			if($operator == 'ignore') return '';
			
  			$thisWhereString .= $this->get_field_name($path, $fieldname, $fieldid);
  			
  			//change if valuekey is set
  			if(isset($valuekey) && $valuekey != '' && $valuekey != 'undefined') $value = $valuekey;
  			
  			// replace the current _user_id if that one is set
  			// bugfix 2010-09-28 since id was asisgned and not user name ..  no properly evaluates active user
  			if($value == 'current_user_id') $value = $current_user->user_name;
  			
  			// process the operator
  			switch($operator)
  			{
  				case 'equals':
  					$thisWhereString .= ' = \'' . $value . '\'';
  					break;
  				case 'notequal':
  					$thisWhereString .= ' <> \'' . $value . '\'';
  					break;
  				case 'greater':
  					$thisWhereString .= ' > \'' . $value . '\'';
  					break;	
  				case 'after':
  					$thisWhereString .= ' > \'' . $value . '\'';
  					break;	  					
				case 'less':
  					$thisWhereString .= ' < \'' . $value . '\'';
  					break;	
				case 'before':
  					$thisWhereString .= ' < \'' . $value . '\'';
  					break;						
  				case 'greaterequal':
  					$thisWhereString .= ' >= \'' . $value . '\'';
  					break;	
				case 'lessequal':
  					$thisWhereString .= ' <= \'' . $value . '\'';
  					break;
  				case 'starts':
  					$thisWhereString .= ' LIKE \'' . $value . '%\'';
  					break;
  				case 'notstarts':
  					$thisWhereString .= ' NOT LIKE \'' . $value . '%\'';
  					break;  					
   				case 'contains':
  					$thisWhereString .= ' LIKE \'%' . $value . '%\'';
  					break;
   				case 'notcontains':
  					$thisWhereString .= ' NOT LIKE \'%' . $value . '%\'';
  					break;  					
   				case 'between':
   					$thisWhereString .= ' >= \'' . $value . '\' AND ' . $this->get_field_name($path, $fieldname, $fieldid) . '<=\'' . $valueto . '\'';
   					break;
  				case 'isempty':
  					$thisWhereString .= ' = \'\'';
  					break;
   				case 'isemptyornull':
  					$thisWhereString .= ' = \'\' OR ' . $this->get_field_name($path, $fieldname, $fieldid) . ' IS NULL';
  					break;
   				case 'isnull':
  					$thisWhereString .= ' IS NULL';
  					break;  					
	  			case 'isnotempty':
	  				$thisWhereString .= ' <> \'\' AND ' . $this->get_field_name($path, $fieldname, $fieldid) .' is not null';
	  				break;		
	  			case 'oneof':
	  				if($this->fieldNameMap[$fieldid]['type'] == 'multienum')
	  				{
	  					$valueArray = preg_split('/,/', $value);
	  					$multienumWhereString = '';
	  					foreach($valueArray as $thisMultiEnumValue)
	  					{
	  						if($multienumWhereString != '') 
	  							$multienumWhereString .= ' OR ' . $this->get_field_name($path, $fieldname, $fieldid);
							
	  						$multienumWhereString .= ' LIKE \'%' . $thisMultiEnumValue . '%\'';
	  					}
	  					$thisWhereString .= $multienumWhereString;
	  				}
	  				else
	  				{
	  					$thisWhereString .= ' IN (\''. str_replace(',', '\',\'', $value) . '\')';
	  				}
	  				break;	  					
	  			case 'oneofnot':
	  				if($this->fieldNameMap[$fieldid]['type'] == 'multienum')
	  				{
	  					$valueArray = preg_split('/,/', $value);
	  					$multienumWhereString = '';
	  					foreach($valueArray as $thisMultiEnumValue)
	  					{
	  						if($multienumWhereString != '') 
	  							$multienumWhereString .= ' OR ' . $this->get_field_name($path, $fieldname, $fieldid);
							
	  						$multienumWhereString .= ' NOT LIKE \'%' . $thisMultiEnumValue . '%\'';
	  					}
	  					$thisWhereString .= $multienumWhereString;
	  				}
	  				else
	  				{
	  					$thisWhereString .= ' NOT IN (\''. str_replace(',', '\',\'', $value) . '\')';
	  				}
	  				break;
	  			case 'oneofnotornull':
	  				if($this->fieldNameMap[$fieldid]['type'] == 'multienum')
	  				{
	  					$valueArray = preg_split('/,/', $value);
	  					$multienumWhereString = '';
	  					foreach($valueArray as $thisMultiEnumValue)
	  					{
	  						if($multienumWhereString != '') 
	  							$multienumWhereString .= ' OR ' . $this->get_field_name($path, $fieldname, $fieldid);
							
	  						$multienumWhereString .= ' NOT LIKE \'%' . $thisMultiEnumValue . '%\'';
	  					}
	  					$thisWhereString .= $multienumWhereString . 'OR ' . $this->get_field_name($path, $fieldname, $fieldid) . ' IS NULL';
	  				}
	  				else
	  				{	  				
	  					$thisWhereString .= ' NOT IN (\''. str_replace(',', '\',\'', $value) . '\') OR ' . $this->get_field_name($path, $fieldname, $fieldid) . ' IS NULL'; 
	  				}
	  				break;	  				
	  			case 'thismonth':
	  				$dateArray = getdate();
	  				$fromDate = date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'], 1, $dateArray['year']));
	  				$toDate = (($dateArray['mon'] + 1) > 12) ? date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'] - 11, 1, $dateArray['year'] + 1)): date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'] + 1, 1, $dateArray['year']));
	  				$thisWhereString .= ' >= \'' . $fromDate . ' 00:00:00\' AND ' . $this->get_field_name($path, $fieldname, $fieldid) .' < \'' . $toDate . ' 00:00:00\'';
	  				break;	   	
	  			case 'next3month':
	  				$dateArray = getdate();
	  				$fromDate = date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'], 1, $dateArray['year']));
	  				$toDate = (($dateArray['mon'] + 3) > 12) ? date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'] - 8, 1, $dateArray['year'] + 1)): date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'] + 3, 1, $dateArray['year']));
	  				$thisWhereString .= ' >= \'' . $fromDate . ' 00:00:00\' AND ' . $this->get_field_name($path, $fieldname, $fieldid) .' < \'' . $toDate . ' 00:00:00\'';
	  				break;	 
	  			case 'thisyear':
	  				$dateArray = getdate();
	  				$fromDate = date('Y-m-d',mktime(0, 0, 0, 1, 1, $dateArray['year']));
	  				$toDate = date('Y-m-d',mktime(0, 0, 0, 1, 1, $dateArray['year'] + 1));
	  				$thisWhereString .= ' >= \'' . $fromDate . ' 00:00:00\' AND ' . $this->get_field_name($path, $fieldname, $fieldid) .' < \'' . $toDate . ' 00:00:00\'';
	  				break;		  					  				
	  			case 'lastmonth':
	  				$dateArray = getdate();
	  				$fromDate= (($dateArray['mon'] - 1) < 1) ? date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'] + 11, 1, $dateArray['year'] - 1)) : date('Y-m-d h:i:s',mktime(0, 0, 0, $dateArray['mon'] - 1, 1, $dateArray['year']));
	  				$toDate= date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'], 1, $dateArray['year']));
	  				$thisWhereString .= ' >= \'' . $fromDate . ' 00:00:00\' AND ' . $this->get_field_name($path, $fieldname, $fieldid) .' < \'' . $toDate . ' 00:00:00\'';
	  				break;	
	  			case 'last3month':
	  				$dateArray = getdate();
	  				$fromDate= (($dateArray['mon'] - 3) < 1) ? date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'] + 8, 1, $dateArray['year'] - 1)) : date('Y-m-d h:i:s',mktime(0, 0, 0, $dateArray['mon'] - 3, 1, $dateArray['year']));
	  				$toDate= date('Y-m-d',mktime(0, 0, 0, $dateArray['mon'], 1, $dateArray['year']));
	  				$thisWhereString .= ' >= \'' . $fromDate . ' 00:00:00\' AND ' . $this->get_field_name($path, $fieldname, $fieldid) .' < \'' . $toDate . ' 00:00:00\'';
	  				break;	
	  			case 'lastyear':
	  				$dateArray = getdate();
	  				$fromDate = date('Y-m-d',mktime(0, 0, 0, 1, 1, $dateArray['year'] - 1));
	  				$toDate = date('Y-m-d',mktime(0, 0, 0, 1, 1, $dateArray['year']));
	  				$thisWhereString .= ' >= \'' . $fromDate . ' 00:00:00\' AND ' . $this->get_field_name($path, $fieldname, $fieldid) .' < \'' . $toDate . ' 00:00:00\'';
	  				break;		  					
  			}
  			
  			return $thisWhereString;
	}
	
	/*
	 * function to biuild the Group By Clause
	 */
	function build_groupby_string($additionalGroupBy = array()){
		global $db, $app_list_strings, $beanList, $beanFiles;

		/*
		 * Block to build the Group By Clause
		 */
		// build Group By
		reset($this->listArray);
		
		// empty String
		$this->groupbyString = '';
		if(is_array($additionalGroupBy))
		{
		foreach($additionalGroupBy as $thisFieldData)
 			$groupedFields[] = $thisFieldData['fieldid'];
		}
		else
			$additionalGroupBy = array();
		
		if(is_array($this->listArray))
		{
			
			foreach($this->listArray as $thisList)
			{
				if($thisList['groupby'] != 'no' || in_array($thisList['fieldid'], $additionalGroupBy))
				{
					
					// if we are first add GROUP By to the Group By String else a comma
					if($this->groupbyString == '')
						$this->groupbyString .= 'GROUP BY ';
					else
						$this->groupbyString .= ', ';
						
					// $this->addPath($thisList['path'], $this->switchJoinType($thisList['jointype']));
					$fieldName = substr($thisList['path'], strrpos($thisList['path'], "::") + 2, strlen($thisList['path']));
		  			$pathName = substr($thisList['path'], 0, strrpos($thisList['path'], "::"));
		  			
		  			$fieldArray = explode(':', $fieldName);
		  			
		  			// if we have a fixed value or we simply group by the fields
		  			if((isset( $thisList['fixedvalue']) && $thisList['fixedvalue'] != '') || $this->groupByFieldID )
		  				$this->groupbyString .=  $thisList['fieldid'];
		  			else
		  			{
		  					// process custom SQL functions here
		  					//if(isset($thisList['customsqlfunction']) && $thisList['customsqlfunction'] != '')
			  				//	$this->groupbyString .= str_replace('$', $this->get_field_name($pathName, $fieldArray[1], $fieldArray[0]), $thisList['customsqlfunction']);
			  				//else
			  					$this->groupbyString .= $this->get_field_name($pathName, $fieldArray[1], $thisList['fieldid']);
		  			}
		  			
		  			// $this->groupbyString .= $this->get_field_name($pathName, $fieldArray[1], $fieldArray[0]);
		  			
		  			//$groupbyString .=  (isset($thisList['name'])) ? "'" . trim($thisList['name'], ':') . "'" : $this->joinSegments[$pathName]['alias'] . '.' . $fieldArray[1];
					
				}
			}
		}
	}
	
	function build_orderby_string(){
		global $db, $app_list_strings, $beanList, $beanFiles;

		/*
		 * Block to Build the ORder by Clause
		 */
		
		$sortArray = array();
		
		
		// build Order By
		reset($this->listArray);
		
		// empty String
		$this->orderbyString = '';
		
		if(is_array($this->listArray))
		{
			
			foreach($this->listArray as $thisList)
			{
				if($thisList['sort'] == 'asc' || $thisList['sort'] == 'desc')
				{
					
					$fieldName = substr($thisList['path'], strrpos($thisList['path'], "::") + 2, strlen($thisList['path']));
		  			$pathName = substr($thisList['path'], 0, strrpos($thisList['path'], "::"));
		  			
		  			$fieldArray = explode(':', $fieldName);
					
					if($thisList['sortpriority'] != '') 
					{
						// check if we should build a sort string based on ID (mainly for the Union Joins)
						if($this->orderByFieldID)
						{
							$sortArray[$thisList['sortpriority']][]  = $thisList['fieldid'] . ' ' . strtoupper($thisList['sort']);
						}
						else
						{
							if($thisList['sqlfunction'] == '-'  || !$this->evalSQLFunctions)
								$sortArray[$thisList['sortpriority']][] =  $this->get_field_name($pathName, $fieldArray[1], $fieldArray[0]) . ' ' . strtoupper($thisList['sort']);
				  			else
				  				$sortArray[$thisList['sortpriority']][] =  $thisList['sqlfunction'] . '(' . $this->get_field_name($pathName, $fieldArray[1], $fieldArray[0]) . ')' . ' ' . strtoupper($thisList['sort']);
						}
					}
					else
					{
						if($this->orderByFieldID)
						{
							$sortArray['100'][]  = $thisList['fieldid'] . ' ' . strtoupper($thisList['sort']);
						}
						else
						{
							if($thisList['sqlfunction'] == '-' || !$this->evalSQLFunctions)
				  				$sortArray['100'][] =  $this->get_field_name($pathName, $fieldArray[1], $fieldArray[0]) . ' ' . strtoupper($thisList['sort']);
				  			else
				  				$sortArray['100'][] =  $thisList['sqlfunction'] . '(' . $this->get_field_name($pathName, $fieldArray[1], $fieldArray[0]) . ')' . ' ' . strtoupper($thisList['sort']);
						}
					}
				}
			}
			
			if(is_array($sortArray))
			{
				// set flag since we are first Entry
				$isFirst = true;
				
				// sort the array by the sort priority
				ksort($sortArray);
				
				foreach($sortArray as $sortStrings)
				{
					foreach($sortStrings as $sortString)
					{
						if($isFirst)
						{
							$this->orderbyString .= 'ORDER BY ' . $sortString;
							$isFirst = false;
						}
						else
						{
							$this->orderbyString .= ', ' . $sortString;
						}
					}
				}
				
			}
		}
	}
	
	/*
	 * Function that gets the table for a module
	 */
	function get_table_for_module($module){
		global $beanList, $beanFiles;
		require_once($beanFiles[$beanList[$module]]);
		$thisModule = new $beanList[$module];
		return $thisModule->table_name;
	}	
	
	/*
	 * Helper function to get the Field name
	 */
	function get_field_name($path, $field, $fieldid, $link = false, $sqlFunction = '')
	{
			// if we do not have a path we have a fixed value field so do not return a name
			if($path != '')
			{
				// normal processing
			    $thisAlias = ($this->joinSegments[$path]['object']->field_name_map[$field]['source'] == 'custom_fields') ? $this->joinSegments[$path]['customjoin'] : $this->joinSegments[$path]['alias'];
			
			    global $beanList;
			    // 2010-25-10 replace the -> object name with get_class function to handle also the funny aCase obejcts
			    $thisModule = array_search(get_class($this->joinSegments[$path]['object']), $beanList);
			    
			    // set the FieldName Map entries
			    if(!isset($this->fieldNameMap[$fieldid]))
			    	$this->fieldNameMap[$fieldid] = array('fieldname' => $field, 'path' => $path, 'islink' => $link,  'sqlFunction' => $sqlFunction, 'tablealias' => $thisAlias, 'fields_name_map_entry' =>$this->joinSegments[$path]['object']->field_name_map[$field] , 'type' => ($this->joinSegments[$path]['object']->field_name_map[$field]['type'] == 'kreporter') ? $this->joinSegments[$path]['object']->field_name_map[$field]['kreporttype'] : $this->joinSegments[$path]['object']->field_name_map[$field]['type'], 'module' => $thisModule);
			    
			    // check for custom function
			    $thisFieldIdEntry = $this->get_listfieldentry_by_fieldid($fieldid);
			    /*
			    		  					// process custom SQL functions here
		  					if(isset($thisList['customsqlfunction']) && $thisList['customsqlfunction'] != '')
			  					$this->groupbyString .= str_replace('$', $this->get_field_name($pathName, $fieldArray[1], $fieldArray[0]), $thisList['customsqlfunction']);
			  				else
			  					$this->groupbyString .= $this->get_field_name($pathName, $fieldArray[1], $fieldArray[0]);	
			    */
			    	
			    	
		  		if($this->joinSegments[$path]['object']->field_name_map[$field]['type'] == 'kreporter')
	  			{
	  				 // 2010-12-06 add � for custom Fields to be evaluated
	  				 return '(' . str_replace('§', $this->joinSegments[$path]['customjoin'],  str_replace('$', $thisAlias,  $this->joinSegments[$path]['object']->field_name_map[$field]['eval'])) . ')';
	  			}
	  			elseif(isset($thisFieldIdEntry['customsqlfunction']) && $thisFieldIdEntry['customsqlfunction'] != '')
	  			{
	  				return '(' . str_replace('$', $thisAlias . '.' . $field , $thisFieldIdEntry['customsqlfunction']) . ')';
	  			}
	  			else
	  			{
	  				return  $thisAlias . '.' . $field;
	  			}
			}
			else
				return $fieldid;
	}
	
	function get_fieldname_by_fieldid($fieldid)
	{
		return isset($this->fieldNameMap[$fieldid]) ? $this->fieldNameMap[$fieldid]['fieldname'] : '';
	}
	
	function get_fieldpath_by_fieldid($fieldid)
	{
		return isset($this->fieldNameMap[$fieldid]) ? $this->fieldNameMap[$fieldid]['path'] : '';
	}

	function get_listfieldentry_by_fieldid($fieldid)
	{
		foreach($this->listArray as $thisIndex => $listFieldEntry)
		{
			if($listFieldEntry['fieldid'] == $fieldid)	
				return $listFieldEntry;
		}
	}	
}
?>