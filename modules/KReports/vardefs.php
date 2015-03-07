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

$dictionary['KReport'] = array('table' => 'kreports', 
                               'edition' => 'basic',
                               'fields' => array (
   'report_module' => array(
          'name' => 'report_module', 
          'type' => 'enum',
          'options' => 'moduleList',
          'len' => '25',
          'vname' => 'LBL_MODULE',
          'massupdate' => false,
   ),
   'report_status' => array(
   		'name' => 'report_status',
   		'type' => 'enum',
        'options' => 'kreportstatus',
    	'len' => '1',
        'vname' => 'LBL_REPORT_STATUS'
   ),
   'union_modules' => array(
          'name' => 'union_modules', 
          'type' => 'text',
   ),   
   'reportoptions' => array(
      	  'name' => 'reportoptions', 
   		  'type' => 'text', 
   		  'vname' => 'LBL_REPORTOPTIONS'
   ),
   'listtype' => array(
          'name' => 'listtype', 
          'type' => 'varchar',
          'len' => '10',
          'vname' => 'LBL_LISTTYPE',
          'massupdate' => false,
   ),
   'listtypeproperties' => array(
          'name' => 'listtypeproperties', 
          'type' => 'text',
   ),
   'selectionlimit' => array(
          'name' => 'selectionlimit', 
          'type' => 'varchar',
          'len' => '25',
          'vname' => 'LBL_SELECTIONLIMIT',
           'massupdate' => false,
   ),
   'pdforientation' => array(
          'name' => 'pdforientation', 
          'type' => 'varchar',
          'len' => '10',
          'vname' => 'LBL_PDFORIENTATION',
          'massupdate' => false,
   ),
   'values_column' => array(
          'name' => 'values_column', 
          'type' => 'varchar',
          'len' => '25',
          'vname' => 'LBL_VALUES',
          'massupdate' => false,
   ),
   'values_function' => array(
          'name' => 'values_function', 
          'type' => 'multienum',
          // 'len' => '10',
   		  'options' => 'value_functions',
          'vname' => 'LBL_VALUES_FUNCTION',
          'massupdate' => false,
   ),
   'categories' => array(
          'name' => 'categories', 
          'type' => 'varchar',
          'len' => '25',
          'vname' => 'LBL_CATEGORIES',
          'massupdate' => false,
   ),
   
   'chart_layout' => array(
          'name' => 'chart_layout', 
          'type' => 'varchar',
          'len' => '6',
          'vname' => 'LBL_CHART_LAYOUTS',   
          'massupdate' => false,
   ),
   /*
   'chart_type' => array(
          'name' => 'chart_type', 
          'type' => 'enum',
          'len' => '25',
   		  'options' => 'chart_types',
          'vname' => 'LBL_CHART_TYPE',
          'massupdate' => false,
   ),*/
   'chart_params' => array(
          'name' => 'chart_params', 
          'type' => 'text',
          'vname' => 'LBL_CHART_PARAMS',
   ),
   'chart_params_new' => array(
          'name' => 'chart_params_new', 
          'type' => 'text',
          'vname' => 'LBL_CHART_PARAMS',
   ),
   /*
   'chart_width' => array(
          'name' => 'chart_width', 
          'type' => 'varchar',
          'len' => '4',
          'vname' => 'LBL_CHART_WIDTH',
   ),   */
   'chart_height' => array(
          'name' => 'chart_height', 
          'type' => 'varchar',
          'len' => '4',
          'vname' => 'LBL_CHART_HEIGHT',
   ),   
   /*
   'sql_statement' => array(
          'name' => 'sql_statement', 
          'type' => 'text',
          //'len' => '4',
          'vname' => 'LBL_SQL_STATEMENT',
   ),  
   */
   'wheregroups' => array(
   		  'name' => 'wheregroups', 
   		  'type' => 'text', 
   		  'vname' => 'LBL_WHEREGROUPS',
   		  'default' => '[]',
   ),   
   'whereconditions' => array(
   		  'name' => 'whereconditions', 
   		  'type' => 'text', 
   		  'vname' => 'LBL_WHERECONDITION',
   		  'default' => '[]',
   ),
  'listfields' => array(
   		  'name' => 'listfields', 
   		  'type' => 'text', 
   		  'vname' => 'LBL_LISTFIELDS'
   ),
  'unionlistfields' => array(
   		  'name' => 'unionlistfields', 
   		  'type' => 'text', 
   		  'vname' => 'LBL_UNIONLISTFIELDS'
   ),   
   'field_tree' => array(
   		  'name' => 'field_tree',
   	//	  'type' => 'varchar',
   		  'source' => 'non-db',
   ),
   'mapoptions' => array(
      	  'name' => 'mapoptions', 
   		  'type' => 'text', 
   		  'vname' => 'LBL_MAPOPTIONS'
   )
),
   'indices' => array (
       array('name' =>'idx_reminder_name', 'type'=>'index', 'fields'=>array('name')),
       // array('name' =>'idx_reminder_par_del', 'type'=>'index', 'fields'=>array('parent_id','parent_type','deleted')),
             )

        //This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,
                            );
VardefManager::createVardef('KReports','KReport', array('default', 'assignable',));

